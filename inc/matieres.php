<?php
/**
 * Articles rangés par matière (pages « Travaux des élèves » et « Matière enseignée »).
 *
 * Les données sont calculées une fois puis gardées en cache (transient) : la page n'a plus
 * à refaire 12 requêtes ni à générer les extraits à chaque visite. Le cache est vidé dès qu'un
 * article ou une catégorie est ajouté, modifié ou supprimé.
 *
 * @package Les_Renardiere
 */

/**
 * Matière => identifiants (slugs) des catégories, en minuscules sans accents.
 * Les vérifier dans Articles → Catégories → colonne « Identifiant ».
 * Une matière peut avoir plusieurs catégories : WordPress ajoute parfois un suffixe
 * (« espagnol-pedagogie ») quand un identifiant est déjà pris.
 *
 * @return array<string, string[]>
 */
function les_renardiere_matieres_liste() {
	return array(
		'allemand'         => array( 'allemand' ),
		'arts-plastiques'  => array( 'arts-plastiques' ),
		'anglais'          => array( 'anglais' ),
		'espagnol'         => array( 'espagnol', 'espagnol-pedagogie' ),
		'francais'         => array( 'francais' ),
		'physique-chimie'  => array( 'physique-chimie' ),
		'mathematiques'    => array( 'mathematiques', 'mathematiques-pedagogie' ),
		'svt'              => array( 'svt' ),
		'histoire-geo-emc' => array( 'histoire-geo-emc', 'histoire-geographie-emc' ),
		'technologie'      => array( 'technologie' ),
		'lce-anglais'      => array( 'lce-anglais' ),
		'eps'              => array( 'eps' ),
	);
}

/**
 * Nombre d'articles affichés par matière (les autres sont sur la page de la catégorie).
 */
const LES_RENARDIERE_MATIERES_PAR_SECTION = 4;

/**
 * Extrait court d'un article, sans passer le contenu dans les filtres de the_content (lent).
 *
 * @param WP_Post $post Article.
 * @param int     $mots Nombre de mots maximum.
 * @return string Texte brut (à échapper à l'affichage).
 */
function les_renardiere_extrait_court( $post, $mots = 16 ) {
	$texte = has_excerpt( $post ) ? $post->post_excerpt : strip_shortcodes( $post->post_content );
	$texte = html_entity_decode( wp_strip_all_tags( $texte, true ), ENT_QUOTES, 'UTF-8' );

	return wp_trim_words( $texte, $mots, '…' );
}

/**
 * Sections par matière, prêtes à afficher (en cache).
 *
 * @return array{sections: array, vides: string[], manquants: string[]}
 */
function les_renardiere_matieres_donnees() {
	$matieres = les_renardiere_matieres_liste();
	$empreinte = md5( wp_json_encode( $matieres ) . LES_RENARDIERE_MATIERES_PAR_SECTION . LES_RENARDIERE_VERSION );

	$cache = get_transient( 'les_renardiere_matieres' );
	if ( is_array( $cache ) && isset( $cache['empreinte'] ) && $empreinte === $cache['empreinte'] ) {
		return $cache;
	}

	// Toutes les catégories de la liste, même vides, indexées par identifiant.
	$par_slug = array();
	foreach ( get_categories(
		array(
			'slug'       => array_merge( ...array_values( $matieres ) ),
			'hide_empty' => false,
		)
	) as $cat ) {
		$par_slug[ $cat->slug ] = $cat;
	}

	$donnees = array(
		'empreinte' => $empreinte,
		'sections'  => array(),
		'vides'     => array(),
		'manquants' => array(),
	);

	foreach ( $matieres as $cle => $slugs ) {
		$cats = array_values( array_intersect_key( $par_slug, array_flip( $slugs ) ) );

		if ( ! $cats ) {
			$donnees['manquants'][] = $cle;
			continue;
		}

		$q = new WP_Query(
			array(
				'post_type'              => 'post',
				'post_status'            => 'publish',
				'cat'                    => implode( ',', wp_list_pluck( $cats, 'term_id' ) ), // Inclut les sous-catégories.
				'posts_per_page'         => LES_RENARDIERE_MATIERES_PAR_SECTION,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
			)
		);

		// Nom affiché : la première catégorie de la liste.
		$nom = $cats[0]->name;

		if ( ! $q->have_posts() ) {
			$donnees['vides'][] = $nom;
			continue;
		}

		// Lien « Voir tout » : la catégorie de la matière qui a le plus d'articles.
		usort(
			$cats,
			function ( $a, $b ) {
				return $b->count - $a->count;
			}
		);

		$articles = array();
		foreach ( $q->posts as $post ) {
			$articles[] = array(
				'titre'    => get_the_title( $post ),
				'lien'     => get_permalink( $post ),
				'date_iso' => get_the_date( 'c', $post ),
				'date'     => get_the_date( 'j M Y', $post ),
				'extrait'  => les_renardiere_extrait_court( $post ),
				'image'    => get_the_post_thumbnail(
					$post,
					'medium',
					array(
						'alt'     => '',
						'loading' => 'lazy',
						'sizes'   => '(max-width: 600px) 100vw, 300px',
					)
				),
			);
		}

		$donnees['sections'][] = array(
			'slug'     => $cle,
			'nom'      => $nom,
			'total'    => (int) $q->found_posts,
			'lien'     => get_category_link( $cats[0] ),
			'articles' => $articles,
		);
	}

	// Ordre alphabétique des matières (sans tenir compte des accents).
	usort(
		$donnees['sections'],
		function ( $a, $b ) {
			return strcasecmp( remove_accents( $a['nom'] ), remove_accents( $b['nom'] ) );
		}
	);

	set_transient( 'les_renardiere_matieres', $donnees, 12 * HOUR_IN_SECONDS );

	return $donnees;
}

/**
 * Vide le cache dès qu'un article ou une catégorie change.
 */
function les_renardiere_matieres_vider_cache() {
	delete_transient( 'les_renardiere_matieres' );
}
add_action( 'save_post_post', 'les_renardiere_matieres_vider_cache' );
add_action( 'deleted_post', 'les_renardiere_matieres_vider_cache' );
add_action( 'trashed_post', 'les_renardiere_matieres_vider_cache' );
add_action( 'transition_post_status', 'les_renardiere_matieres_vider_cache' ); // Articles programmés qui se publient.
add_action( 'set_object_terms', 'les_renardiere_matieres_vider_cache' );
add_action( 'created_category', 'les_renardiere_matieres_vider_cache' );
add_action( 'edited_category', 'les_renardiere_matieres_vider_cache' );
add_action( 'delete_category', 'les_renardiere_matieres_vider_cache' );
