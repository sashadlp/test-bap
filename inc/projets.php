<?php
/**
 * Projets, technologies et compteur de vues.
 * Repris tel quel de l'ancien thème à blocs « les-renardiere » (préfixe mon_theme_fse_ renommé).
 *
 * @package Les_Renardiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Recalcule les permaliens quand on active ou désactive le thème (pour /projets/ et /technologie/).
function les_renardiere_flush_rewrite() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'les_renardiere_flush_rewrite' );
add_action( 'switch_theme', 'les_renardiere_flush_rewrite' );

// 4. Déclaration du Custom Post Type "Projet"
function les_renardiere_projets_cpt()
{
    register_post_type('projet', [
        'labels' => [
            'name'          => 'Projets',
            'singular_name' => 'Projet',
            'add_new_item'  => 'Ajouter un projet',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'projets'],
    ]);
}
add_action('init', 'les_renardiere_projets_cpt');

// 5. Déclaration de la taxonomie "Technologies"
function les_renardiere_technologies_taxonomy()
{
    // Création de la taxonomie "Technologies" pour le type de contenu "projet"
    register_taxonomy('technologie', 'projet', [
        'labels' => [
            'name' => 'Technologies',
            'singular_name' => 'Technologie',
            'search_items' => 'Rechercher une technologie',
            'all_items' => 'Toutes les technologies',
            'edit_item' => 'Modifier une technologie',
            'update_item' => 'Mettre à jour une technologie',
            'add_new_item' => 'Ajouter une technologie',
            'new_item_name' => 'Nouvelle technologie',
            'not_found' => 'Aucune technologie trouvée',
            'menu_name' => 'Technologies',
        ],
        'public' => true,
        'hierarchical' => true, // Les termes sont affichés comme des étiquettes et non des cases à cocher
        'show_admin_column' => true, // Affiche la colonne dans la liste des projets
        'show_in_rest' => true, // Obligatoire pour l'éditeur de blocs
        'rewrite' => ['slug' => 'technologie'],
    ]);
}
add_action('init', 'les_renardiere_technologies_taxonomy');

// 6. Déclaration des métadonnées personnalisées pour les projets
function les_renardiere_projets_meta()
{
    register_post_meta('projet', 'client', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('projet', 'annee', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('projet', 'lien_projet', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'esc_url_raw',
    ]);
}
add_action('init', 'les_renardiere_projets_meta');

// 7. Compteur de visites pour les projets
function les_renardiere_compter_vues()
{
    if (!is_singular('projet')) {
        return;
    }

    $post_id = get_queried_object_id();
    $vues    = intval(get_post_meta($post_id, '_vues', true));
    update_post_meta($post_id, '_vues', $vues + 1);
}
add_action('template_redirect', 'les_renardiere_compter_vues');

function les_renardiere_vues_valeur($source_args, $block_instance)
{
    $post_id = $block_instance->context['postId'] ?? get_the_ID();
    $vues    = intval(get_post_meta($post_id, '_vues', true));

    return $vues . ($vues > 1 ? ' vues' : ' vue');
}

function les_renardiere_bindings()
{
    register_block_bindings_source('mon-theme-fse/vues', [
        'label'              => 'Nombre de vues',
        'get_value_callback' => 'les_renardiere_vues_valeur',
        'uses_context'       => ['postId'],
    ]);
}
add_action('init', 'les_renardiere_bindings');

function les_renardiere_colonne_vues($columns)
{
    $columns['vues'] = 'Visites';
    return $columns;
}
add_filter('manage_projet_posts_columns', 'les_renardiere_colonne_vues');

function les_renardiere_colonne_vues_contenu($column, $post_id)
{
    if ($column === 'vues') {
        echo intval(get_post_meta($post_id, '_vues', true));
    }
}
add_action('manage_projet_posts_custom_column', 'les_renardiere_colonne_vues_contenu', 10, 2);


// 8. 9 projets par page sur les pages « technologie » (comme l'ancien template).
function les_renardiere_technologie_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_tax( 'technologie' ) ) {
		$query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'les_renardiere_technologie_query' );

/**
 * Nombre de vues d'un projet, ex. « 3 vues ».
 *
 * @param int $post_id Projet.
 * @return string
 */
function les_renardiere_vues_texte( $post_id = 0 ) {
	$vues = intval( get_post_meta( $post_id ? $post_id : get_the_ID(), '_vues', true ) );
	return $vues . ( $vues > 1 ? ' vues' : ' vue' );
}
