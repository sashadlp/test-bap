<?php
/**
 * Projets, technologies et compteur de vues.
 * Repris de l'ancien thème à blocs « les-renardiere » (préfixe mon_theme_fse_ renommé).
 *
 * @package Les_Renardiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recalcule les permaliens quand on active ou désactive le thème (pour /projets/ et /technologie/).
 */
function les_renardiere_flush_rewrite() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'les_renardiere_flush_rewrite' );
add_action( 'switch_theme', 'les_renardiere_flush_rewrite' );

/**
 * Type de contenu « Projet ».
 */
function les_renardiere_projets_cpt() {
	register_post_type(
		'projet',
		array(
			'labels'       => array(
				'name'          => 'Projets',
				'singular_name' => 'Projet',
				'add_new_item'  => 'Ajouter un projet',
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'projets' ),
		)
	);
}
add_action( 'init', 'les_renardiere_projets_cpt' );

/**
 * Taxonomie « Technologies » des projets.
 */
function les_renardiere_technologies_taxonomy() {
	register_taxonomy(
		'technologie',
		'projet',
		array(
			'labels'            => array(
				'name'          => 'Technologies',
				'singular_name' => 'Technologie',
				'search_items'  => 'Rechercher une technologie',
				'all_items'     => 'Toutes les technologies',
				'edit_item'     => 'Modifier une technologie',
				'update_item'   => 'Mettre à jour une technologie',
				'add_new_item'  => 'Ajouter une technologie',
				'new_item_name' => 'Nouvelle technologie',
				'not_found'     => 'Aucune technologie trouvée',
				'menu_name'     => 'Technologies',
			),
			'public'            => true,
			'hierarchical'      => true, // Cases à cocher dans l'éditeur, comme les catégories.
			'show_admin_column' => true, // Colonne dans la liste des projets.
			'show_in_rest'      => true, // Obligatoire pour l'éditeur de blocs.
			'rewrite'           => array( 'slug' => 'technologie' ),
		)
	);
}
add_action( 'init', 'les_renardiere_technologies_taxonomy' );

/**
 * Champs personnalisés des projets : client, année, lien.
 */
function les_renardiere_projets_meta() {
	register_post_meta(
		'projet',
		'client',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	register_post_meta(
		'projet',
		'annee',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	register_post_meta(
		'projet',
		'lien_projet',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'esc_url_raw',
		)
	);
}
add_action( 'init', 'les_renardiere_projets_meta' );

/**
 * La visite doit-elle compter ?
 *
 * Non pour : les rédacteurs et admins (ils relisent leurs projets), les aperçus,
 * les requêtes autres que GET (HEAD des vérificateurs de liens…), le préchargement
 * du navigateur et les robots (moteurs de recherche, aperçus de liens des réseaux sociaux…).
 *
 * Les rechargements de page d'un même visiteur comptent encore : les ignorer demanderait
 * un cookie, donc un bandeau de consentement (CNIL).
 *
 * @return bool
 */
function les_renardiere_visite_compte() {
	if ( is_preview() || current_user_can( 'edit_posts' ) ) {
		return false;
	}

	$methode = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
	if ( 'GET' !== $methode ) {
		return false;
	}

	// Préchargement (Chrome : Sec-Purpose, autres : Purpose / X-Moz).
	foreach ( array( 'HTTP_SEC_PURPOSE', 'HTTP_PURPOSE', 'HTTP_X_MOZ' ) as $entete ) {
		if ( isset( $_SERVER[ $entete ] ) && false !== stripos( sanitize_text_field( wp_unslash( $_SERVER[ $entete ] ) ), 'prefetch' ) ) {
			return false;
		}
	}

	$agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
	if ( '' === $agent || preg_match( '/bot|crawl|spider|slurp|scrap|fetch|preview|facebookexternalhit|embedly|lighthouse|headless|curl|wget|python|java\/|httpclient|go-http/i', $agent ) ) {
		return false;
	}

	/**
	 * Permet d'ajuster la règle ailleurs (extension, thème enfant).
	 *
	 * @param bool $compte Vrai si la visite est comptée.
	 */
	return (bool) apply_filters( 'les_renardiere_visite_compte', true );
}

/**
 * Compteur de visites des projets (méta « _vues »).
 */
function les_renardiere_compter_vues() {
	if ( ! is_singular( 'projet' ) || ! les_renardiere_visite_compte() ) {
		return;
	}

	$post_id = get_queried_object_id();
	$vues    = intval( get_post_meta( $post_id, '_vues', true ) );
	update_post_meta( $post_id, '_vues', $vues + 1 );
}
add_action( 'template_redirect', 'les_renardiere_compter_vues' );

/**
 * Nombre de vues d'un projet, ex. « 3 vues ».
 *
 * @param int $post_id Projet (par défaut : l'article courant).
 * @return string
 */
function les_renardiere_vues_texte( $post_id = 0 ) {
	$vues = intval( get_post_meta( $post_id ? $post_id : get_the_ID(), '_vues', true ) );
	return $vues . ( $vues > 1 ? ' vues' : ' vue' );
}

/**
 * Valeur de la source de liaison de blocs « Nombre de vues ».
 *
 * @param array    $source_args    Arguments de la liaison (inutilisés).
 * @param WP_Block $block_instance Bloc lié.
 * @return string
 */
function les_renardiere_vues_valeur( $source_args, $block_instance ) {
	$post_id = isset( $block_instance->context['postId'] ) ? (int) $block_instance->context['postId'] : get_the_ID();
	return les_renardiere_vues_texte( $post_id );
}

/**
 * Source de liaison de blocs « Nombre de vues ».
 * Le nom « mon-theme-fse/vues » vient de l'ancien thème : il est gardé pour ne pas casser
 * les blocs déjà enregistrés qui l'utilisent.
 */
function les_renardiere_bindings() {
	register_block_bindings_source(
		'mon-theme-fse/vues',
		array(
			'label'              => 'Nombre de vues',
			'get_value_callback' => 'les_renardiere_vues_valeur',
			'uses_context'       => array( 'postId' ),
		)
	);
}
add_action( 'init', 'les_renardiere_bindings' );

/**
 * Colonne « Visites » dans la liste des projets.
 *
 * @param array $columns Colonnes.
 * @return array
 */
function les_renardiere_colonne_vues( $columns ) {
	$columns['vues'] = 'Visites';
	return $columns;
}
add_filter( 'manage_projet_posts_columns', 'les_renardiere_colonne_vues' );

/**
 * Contenu de la colonne « Visites ».
 *
 * @param string $column  Colonne.
 * @param int    $post_id Projet.
 */
function les_renardiere_colonne_vues_contenu( $column, $post_id ) {
	if ( 'vues' === $column ) {
		echo intval( get_post_meta( $post_id, '_vues', true ) );
	}
}
add_action( 'manage_projet_posts_custom_column', 'les_renardiere_colonne_vues_contenu', 10, 2 );

/**
 * 9 projets par page sur les pages « technologie » (comme l'ancien template).
 *
 * @param WP_Query $query Requête.
 */
function les_renardiere_technologie_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_tax( 'technologie' ) ) {
		$query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'les_renardiere_technologie_query' );
