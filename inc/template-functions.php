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
