<?php
/**
 * Pages « rubrique » : Orientation, Découverte des métiers.
 *
 * Chaque page est repérée par son identifiant (slug) et utilise automatiquement
 * page-templates/template-rubrique.php : pas de modèle à choisir dans l'éditeur.
 * Le texte se modifie ici, dans les_renardiere_rubriques(). Si la page a un contenu
 * dans l'éditeur WordPress, il s'affiche aussi (sous l'introduction).
 *
 * Champs d'une rubrique :
 * - parent     : rubrique du menu affichée dans le fil d'Ariane (« url » vide = pas de lien) ;
 * - titre      : titre affiché (sinon celui de la page) ;
 * - chapo      : phrase d'introduction ;
 * - reperes    : chiffres ou infos clés [ valeur, libellé ] ;
 * - sections   : blocs de texte [ titre, texte (paragraphes), liste ] ;
 * - liens      : liens utiles [ libellé, url ] ;
 * - bouton     : bouton principal du bandeau [ libellé, url ] (facultatif) ;
 * - actus      : actualités, groupe => identifiants de catégories d'articles ;
 * - cta        : encadré final [ titre, texte ].
 *
 * @package Les_Renardiere
 */

/**
 * Contenu des pages rubriques, indexé par identifiant (slug) de page.
 *
 * @return array<string, array>
 */
function les_renardiere_rubriques() {
	$vie_scolaire = array(
		'nom' => 'Vie scolaire',
		'url' => les_renardiere_page_url( 'le-college' ),
	);

	return array(

		'orientation'            => array(
			'parent'   => $vie_scolaire,
			'chapo'    => 'De la 6e à la 3e, le collège accompagne chaque élève dans la construction de son projet : découvrir des métiers, connaître les formations et préparer le passage au lycée.',
			'reperes'  => array(
				array( 'Parcours Avenir', 'De la 6e à la 3e' ),
				array( '4e', 'Bus de l’orientation et oral de présentation' ),
				array( '3e', 'Une semaine de stage en entreprise' ),
				array( '3e', 'Vœux d’orientation et choix du lycée' ),
			),
			'sections' => array(
				array(
					'titre' => 'Le parcours Avenir',
					'texte' => array( 'Tout au long du collège, le parcours Avenir aide les élèves à découvrir le monde professionnel, à connaître les formations et à réfléchir à leurs centres d’intérêt.' ),
					'liste' => array(
						'Rencontres avec des professionnels et visites d’entreprises',
						'Sorties et forums consacrés aux métiers',
						'Présentations des lycées du secteur',
					),
				),
				array(
					'titre' => 'En 4e : explorer les pistes',
					'texte' => array( 'Les élèves commencent à formuler leurs envies. Le collège accueille notamment le Bus de l’orientation Oriane de la Région Île-de-France : outils numériques, vidéos et échanges avec des professionnels, qui préparent aussi aux oraux de 4e.' ),
				),
				array(
					'titre' => 'En 3e : le stage et le choix du lycée',
					'texte' => array(
						'Chaque élève de 3e effectue une semaine de stage d’observation dans l’entreprise ou l’administration de son choix. Il envoie lui-même sa lettre de motivation et son CV. Le stage aide à affiner le projet et prépare l’oral du brevet.',
						'Les familles formulent ensuite les vœux d’orientation (voie générale et technologique ou voie professionnelle), puis les vœux d’affectation au lycée, accompagnées par le professeur principal.',
					),
				),
				array(
					'titre' => 'Les lycées de Courbevoie',
					'texte' => array( 'Pour préparer leurs choix, les élèves de 3e découvrent les lycées de la ville : Paul Lapie, Paul Painlevé et Lucie Aubrac.' ),
				),
			),
			'liens'    => array(
				array( 'ONISEP : métiers et formations', 'https://www.onisep.fr/' ),
				array( 'Oriane, l’orientation en Île-de-France', 'https://www.oriane.info/' ),
			),
			'actus'    => array( 'Actualités' => array( 'orientation' ) ),
			'cta'      => array( 'Une question sur l’orientation ?', 'Le professeur principal et la vie scolaire sont là pour vous conseiller.' ),
		),

		'decouverte-des-metiers' => array(
			'parent'   => $vie_scolaire,
			'chapo'    => 'Visites d’entreprises et de lycées professionnels, forums et rencontres : les élèves découvrent concrètement des métiers pour nourrir leur projet d’avenir.',
			'reperes'  => array(
				array( 'Parcours Avenir', 'Des découvertes à chaque niveau' ),
				array( 'Visites', 'Entreprises et lycées professionnels' ),
				array( 'Forum', 'Rencontres avec des professionnels' ),
			),
			'sections' => array(
				array(
					'titre' => 'Découvrir sur le terrain',
					'texte' => array( 'Des sorties permettent de voir des métiers en vrai et de poser des questions à celles et ceux qui les exercent.' ),
					'liste' => array(
						'Visite du lycée de l’automobile GARAC à Argenteuil',
						'Visite d’un laboratoire d’analyse de médicaments à Villeneuve-la-Garenne',
						'Après-midis d’immersion professionnelle pour les classes de 3e',
					),
				),
				array(
					'titre' => 'Rencontrer des professionnels',
					'texte' => array( 'Forums des métiers et interventions au collège : les élèves échangent avec des professionnels sur leur parcours, leur formation et leur quotidien.' ),
				),
				array(
					'titre' => 'Un lien direct avec l’orientation',
					'texte' => array( 'Ces découvertes s’inscrivent dans le parcours Avenir et aident chaque élève à préparer ses choix de fin de 3e.' ),
				),
			),
			'liens'    => array(
				array( 'ONISEP : fiches métiers', 'https://www.onisep.fr/' ),
				array( 'Page Orientation du collège', les_renardiere_page_url( 'orientation' ) ),
			),
			'actus'    => array( 'Actualités' => array( 'decouverte-des-metiers' ) ),
			'cta'      => array( 'Vous êtes un professionnel ?', 'Vous souhaitez présenter votre métier aux élèves ? Contactez le collège.' ),
		),

	);
}

/**
 * Rubrique de la page affichée, ou null.
 *
 * @return array|null
 */
function les_renardiere_rubrique_actuelle() {
	if ( ! is_page() ) {
		return null;
	}

	$rubriques = les_renardiere_rubriques();
	$slug      = get_post_field( 'post_name', get_queried_object_id() );

	return isset( $rubriques[ $slug ] ) ? $rubriques[ $slug ] + array( 'slug' => $slug ) : null;
}

/**
 * Utilise page-templates/template-rubrique.php pour les pages de la liste.
 *
 * @param string $template Modèle choisi par WordPress.
 * @return string
 */
function les_renardiere_rubrique_modele( $template ) {
	if ( les_renardiere_rubrique_actuelle() ) {
		$modele = locate_template( 'page-templates/template-rubrique.php' );
		if ( $modele ) {
			return $modele;
		}
	}

	return $template;
}
add_filter( 'template_include', 'les_renardiere_rubrique_modele' );

/**
 * Actualités d'une rubrique (en cache, vidé avec celui des matières : inc/matieres.php).
 *
 * @param string $slug   Identifiant de la page.
 * @param array  $groupes Groupe => identifiants de catégories.
 * @return array Groupe => [ total, lien, articles ].
 */
function les_renardiere_rubrique_actus( $slug, $groupes ) {
	$empreinte = md5( wp_json_encode( $groupes ) . LES_RENARDIERE_VERSION );
	$cache     = get_transient( 'les_renardiere_rubriques' );
	$cache     = is_array( $cache ) ? $cache : array();

	if ( isset( $cache[ $slug ]['empreinte'] ) && $empreinte === $cache[ $slug ]['empreinte'] ) {
		return $cache[ $slug ]['groupes'];
	}

	$resultat = array();
	foreach ( $groupes as $nom => $slugs ) {
		$cats = get_categories(
			array(
				'slug'       => $slugs,
				'hide_empty' => false,
			)
		);
		if ( ! $cats ) {
			continue;
		}

		$q = new WP_Query(
			array(
				'post_type'              => 'post',
				'post_status'            => 'publish',
				'cat'                    => implode( ',', wp_list_pluck( $cats, 'term_id' ) ),
				'posts_per_page'         => 6,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
			)
		);
		if ( ! $q->have_posts() ) {
			continue;
		}

		$resultat[ $nom ] = array(
			'total'    => (int) $q->found_posts,
			'lien'     => get_category_link( $cats[0] ),
			'articles' => array_map( 'les_renardiere_article_resume', $q->posts ),
		);
	}

	$cache[ $slug ] = array(
		'empreinte' => $empreinte,
		'groupes'   => $resultat,
	);
	set_transient( 'les_renardiere_rubriques', $cache, 12 * HOUR_IN_SECONDS );

	return $resultat;
}
