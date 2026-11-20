<?php
/**
 * Les_Renardiere functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Les_Renardiere
 */

if ( ! defined( 'LES_RENARDIERE_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'LES_RENARDIERE_VERSION', '2.0.2' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function les_renardiere_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Les_Renardiere, use a find and replace
		* to change 'les-renardiere' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'les-renardiere', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// Pas d'emplacement de menu classique : le menu est le bloc Navigation de parts/header.html.

	// En-tête et pied de page en parties de modèle FSE (parts/header.html, parts/footer.html),
	// modifiables dans Apparence → Compositions → Parties de modèle.
	add_theme_support( 'block-template-parts' );

	// Styles du thème (Sass compilé) aussi dans l'éditeur.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/style.css' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'les_renardiere_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function les_renardiere_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'les_renardiere_content_width', 640 );
}
add_action( 'after_setup_theme', 'les_renardiere_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function les_renardiere_scripts() {
	// Base Underscores (reset + styles WordPress).
	wp_enqueue_style( 'les-renardiere-style', get_stylesheet_uri(), array(), LES_RENARDIERE_VERSION );

	// Styles de l'équipe : assets/sass compilé en assets/css/style.css (Live Sass Compiler).
	$les_renardiere_css = get_template_directory() . '/assets/css/style.css';
	wp_enqueue_style(
		'les-renardiere-main',
		get_template_directory_uri() . '/assets/css/style.css',
		array( 'les-renardiere-style' ),
		file_exists( $les_renardiere_css ) ? filemtime( $les_renardiere_css ) : LES_RENARDIERE_VERSION
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'les_renardiere_scripts' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Types de contenu, taxonomies et champs repris de l'ancien thème à blocs.
 */
require get_template_directory() . '/inc/projets.php';

/**
 * Formulaire de la page Contact (envoi par e-mail).
 */
require get_template_directory() . '/inc/contact.php';

/**
 * Articles rangés par matière, en cache (travaux des élèves, matière enseignée).
 */
require get_template_directory() . '/inc/matieres.php';
