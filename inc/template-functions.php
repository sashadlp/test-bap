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
