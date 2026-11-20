<?php
/**
 * Page « Les travaux des élèves » (page-travaux-des-eleves.php) : travaux rangés par type
 * (concours, arts, sciences…), avec un travail « à la une ».
 *
 * Les données sont en cache (transient) et vidées avec celles des matières (inc/matieres.php).
 *
 * @package Les_Renardiere
 */

/**
 * Types de travaux : clé => [ libellé, identifiants de catégories d'articles ].
 * Les catégories se vérifient dans Articles → Catégories → colonne « Identifiant ».
 *
 * @return array<string, array>
 */
function les_renardiere_travaux_types() {
	return array(
		'concours' => array( 'Concours', array( 'concours' ) ),
		'arts'     => array( 'Arts et culture', array( 'arts-plastiques', 'culture', 'parcours-culturel', 'classe-creative', 'chorale', 'cham' ) ),
		'sciences' => array( 'Sciences et techno', array( 'technologie', 'atelier-robotique', 'svt', 'physique-chimie', 'mathematiques', 'mathematiques-pedagogie' ) ),
		'langues'  => array( 'Langues et international', array( 'si-americaine', 'si-arabe', 'lce-anglais', 'anglais', 'allemand', 'espagnol-pedagogie' ) ),
		'ecrits'   => array( 'Écrits et médias', array( 'atelier-journalisme', 'webradio', 'lettres', 'francais', 'cdi' ) ),
		'sorties'  => array( 'Sorties et voyages', array( 'voyages' ) ),
	);
}

/**
 * Applique le modèle de la page aussi quand elle est réglée comme « page des articles »
 * (Réglages → Lecture) : WordPress afficherait sinon la liste de tous les articles.
 *
 * @param string $template Modèle choisi par WordPress.
 * @return string
 */
function les_renardiere_travaux_modele( $template ) {
	if ( is_home() && ! is_front_page() && 'travaux-des-eleves' === get_post_field( 'post_name', (int) get_option( 'page_for_posts' ) ) ) {
		$modele = locate_template( 'page-travaux-des-eleves.php' );
		if ( $modele ) {
			return $modele;
		}
	}

	return $template;
}
add_filter( 'template_include', 'les_renardiere_travaux_modele' );

/**
 * Sur cette page réglée comme « page des articles », WordPress charge la liste du blog
 * alors que le modèle ne l'utilise pas : on réduit cette requête au minimum.
 *
 * @param WP_Query $query Requête.
 */
function les_renardiere_travaux_requete_legere( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_home() && ! $query->is_front_page()
		&& 'travaux-des-eleves' === get_post_field( 'post_name', (int) get_option( 'page_for_posts' ) ) ) {
		$query->set( 'posts_per_page', 1 );
		$query->set( 'no_found_rows', true );
		$query->set( 'fields', 'ids' );
	}
}
add_action( 'pre_get_posts', 'les_renardiere_travaux_requete_legere' );

/**
 * Travaux prêts à afficher (en cache) : à la une, « tous », puis un groupe par type.
 *
 * @return array{une: array|null, groupes: array}
 */
function les_renardiere_travaux_donnees() {
	$types     = les_renardiere_travaux_types();
	$empreinte = md5( wp_json_encode( $types ) . LES_RENARDIERE_VERSION );

	$cache = get_transient( 'les_renardiere_travaux' );
	if ( is_array( $cache ) && isset( $cache['empreinte'] ) && $empreinte === $cache['empreinte'] ) {
		return $cache;
	}

	// Identifiants des catégories de chaque type.
	$ids_type = array();
	foreach ( $types as $cle => $type ) {
		$ids_type[ $cle ] = wp_list_pluck(
			get_categories(
				array(
					'slug'       => $type[1],
					'hide_empty' => false,
				)
			),
			'term_id'
		);
	}
	$tous_ids = array_values( array_unique( array_merge( ...array_values( $ids_type ) ) ) );

	$base = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'ignore_sticky_posts'    => true,
		'update_post_term_cache' => false,
	);

	// À la une : le travail le plus récent avec une image, sinon le plus récent.
	$une = null;
	if ( $tous_ids ) {
		$q_une = new WP_Query(
			$base + array(
				'category__in'   => $tous_ids,
				'posts_per_page' => 1,
				'no_found_rows'  => true,
				'meta_key'       => '_thumbnail_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- une seule requête, mise en cache.
			)
		);
		if ( ! $q_une->have_posts() ) {
			$q_une = new WP_Query(
				$base + array(
					'category__in'   => $tous_ids,
					'posts_per_page' => 1,
					'no_found_rows'  => true,
				)
			);
		}
		if ( $q_une->have_posts() ) {
			$post         = $q_une->posts[0];
			$une          = les_renardiere_article_resume( $post );
			$une['id']    = $post->ID;
			$une['long']  = les_renardiere_extrait_court( $post, 34 );
			$une['image'] = get_the_post_thumbnail(
				$post,
				'medium_large',
				array(
					'alt'   => '',
					'sizes' => '(max-width: 800px) 100vw, 560px',
				)
			);
		}
	}

	$groupes = array();
	$requetes = array( 'tous' => array( 'Tous les travaux', $tous_ids, 9 ) );
	foreach ( $types as $cle => $type ) {
		$requetes[ $cle ] = array( $type[0], $ids_type[ $cle ], 6 );
	}

	foreach ( $requetes as $cle => $requete ) {
		if ( ! $requete[1] ) {
			continue;
		}
		$q = new WP_Query(
			$base + array(
				'category__in'   => $requete[1],
				'posts_per_page' => $requete[2],
				'post__not_in'   => $une ? array( $une['id'] ) : array(), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- un seul article, requête en cache.
			)
		);
		if ( ! $q->have_posts() ) {
			continue;
		}
		$groupes[ $cle ] = array(
			'nom'      => $requete[0],
			'total'    => (int) $q->found_posts + ( $une ? 1 : 0 ),
			'articles' => array_map( 'les_renardiere_article_resume', $q->posts ),
		);
	}

	// Le total de « tous » compte l'article à la une ; celui des types seulement s'il en fait partie.
	if ( $une ) {
		$cats_une = wp_get_post_categories( $une['id'] );
		foreach ( $groupes as $cle => $groupe ) {
			if ( 'tous' !== $cle && ! array_intersect( $cats_une, $ids_type[ $cle ] ) ) {
				--$groupes[ $cle ]['total'];
			}
		}
	}

	$donnees = array(
		'empreinte' => $empreinte,
		'une'       => $une,
		'groupes'   => $groupes,
	);
	set_transient( 'les_renardiere_travaux', $donnees, 12 * HOUR_IN_SECONDS );

	return $donnees;
}
