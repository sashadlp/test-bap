<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Les_Renardiere
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function les_renardiere_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Le thème n'a pas de barre latérale.
	$classes[] = 'no-sidebar';

	return $classes;
}
add_filter( 'body_class', 'les_renardiere_body_classes' );

/**
 * Adresse d'une page publiée à partir de son slug (ex. « contact »).
 *
 * Utilise le vrai permalien de la page : le lien reste bon si WordPress est dans un sous-dossier
 * ou si la structure des permaliens change.
 *
 * @param string $slug Slug (ou chemin, ex. « parent/enfant ») de la page.
 * @return string Adresse de la page, ou chaîne vide si elle n'existe pas ou n'est pas publiée.
 */
function les_renardiere_page_url( $slug ) {
	$page = get_page_by_path( $slug );

	// Page rangée sous une autre (ex. « le-college/travaux-des-eleves ») : recherche par identifiant seul.
	if ( ! $page && false === strpos( $slug, '/' ) ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'name'           => $slug,
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'no_found_rows'  => true,
			)
		);
		$page  = $pages ? $pages[0] : null;
	}

	if ( ! $page || 'publish' !== get_post_status( $page ) ) {
		return '';
	}

	return get_permalink( $page );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function les_renardiere_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'les_renardiere_pingback_header' );

/**
 * Menus (en-tête et pied de page) : marque le lien de la page actuelle.
 *
 * Les liens du menu sont souvent des adresses personnalisées, donc WordPress ne sait pas
 * lequel correspond à la page affichée. On compare le chemin du lien à celui de la page :
 * le lien reçoit aria-current="page" et son <li> la classe « is-current »
 * (la rubrique parente est repérée en CSS avec :has(), voir _header.scss).
 *
 * @param string $block_content HTML du bloc.
 * @param array  $block         Bloc (nom et attributs).
 * @return string
 */
function les_renardiere_nav_lien_actuel( $block_content, $block ) {
	if ( ! in_array( $block['blockName'], array( 'core/navigation-link', 'core/navigation-submenu' ), true ) || empty( $block['attrs']['url'] ) ) {
		return $block_content;
	}

	$chemin_lien = wp_parse_url( $block['attrs']['url'], PHP_URL_PATH );
	$chemin_page = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';

	if ( ! $chemin_lien || ! $chemin_page || trailingslashit( $chemin_lien ) !== trailingslashit( $chemin_page ) ) {
		return $block_content;
	}

	$html = new WP_HTML_Tag_Processor( $block_content );
	if ( $html->next_tag( 'li' ) ) {
		$html->add_class( 'is-current' );
		if ( $html->next_tag( 'a' ) ) {
			$html->set_attribute( 'aria-current', 'page' );
		}
	}

	return $html->get_updated_html();
}
add_filter( 'render_block', 'les_renardiere_nav_lien_actuel', 10, 2 );

/**
 * Rubrique principale d'un article : sa catégorie la plus précise (une sous-catégorie si
 * possible, ex. « SI Américaine » plutôt que « Pédagogie »), hors catégorie par défaut et « Non classé ».
 *
 * @param int|WP_Post|null $post Article (par défaut : l'article courant).
 * @return WP_Term|null
 */
function les_renardiere_rubrique_principale( $post = null ) {
	$post       = get_post( $post );
	$par_defaut = (int) get_option( 'default_category' );
	$categories = $post ? get_the_category( $post->ID ) : array();
	$categories = array_values(
		array_filter(
			$categories,
			function ( $categorie ) use ( $par_defaut ) {
				return $categorie->term_id !== $par_defaut && ! in_array( $categorie->slug, array( 'non-classe', 'uncategorized' ), true );
			}
		)
	);

	foreach ( $categories as $categorie ) {
		if ( $categorie->parent ) {
			return $categorie;
		}
	}

	return $categories ? $categories[0] : null;
}

/**
 * Temps de lecture estimé (200 mots par minute), ex. « 2 min de lecture ».
 *
 * @param int|WP_Post|null $post Article (par défaut : l'article courant).
 * @return string
 */
function les_renardiere_temps_lecture( $post = null ) {
	$texte   = trim( wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', $post ) ) ) );
	$mots    = '' === $texte ? 0 : count( preg_split( '/\s+/u', $texte ) );
	$minutes = max( 1, (int) ceil( $mots / 200 ) );

	/* translators: %s: nombre de minutes. */
	return sprintf( __( '%s min de lecture', 'les-renardiere' ), number_format_i18n( $minutes ) );
}

/**
 * Texte d'un article seul : retire les paragraphes vides (souvent laissés par un copier-coller)
 * et met en forme la signature finale (dernier paragraphe très court, ex. « A.S »).
 *
 * @param string $content Contenu de l'article.
 * @return string
 */
function les_renardiere_contenu_article( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$content = preg_replace( '#<p>(?:\s|&nbsp;|&\#160;|\x{00A0}|<br\s*/?>)*</p>#iu', '', $content );

	if ( ! preg_match_all( '#<p>(.*?)</p>#is', $content, $paragraphes, PREG_OFFSET_CAPTURE ) ) {
		return $content;
	}

	$dernier = end( $paragraphes[0] );
	$debut   = $dernier[1];
	$apres   = substr( $content, $debut + strlen( $dernier[0] ) );
	$texte   = trim( str_replace( "\xC2\xA0", ' ', html_entity_decode( wp_strip_all_tags( $dernier[0] ), ENT_QUOTES, 'UTF-8' ) ) );
	$mots    = '' === $texte ? 0 : count( preg_split( '/\s+/u', $texte ) );

	// Rien après ce paragraphe, texte court, sans ponctuation de fin de phrase (sauf des initiales « A.S. »).
	$initiales = (bool) preg_match( '/^(\p{Lu}\.\s?)+\p{Lu}?\.?$/u', $texte );
	$signature = '' === trim( wp_strip_all_tags( $apres ) )
		&& $mots > 0 && $mots <= 6 && mb_strlen( $texte ) <= 40
		&& ( $initiales || ! preg_match( '/[.!?:…]$/u', $texte ) );

	if ( $signature ) {
		$content = substr_replace( $content, '<p class="article-signature">', $debut, 3 );
	}

	return $content;
}
add_filter( 'the_content', 'les_renardiere_contenu_article', 20 );
