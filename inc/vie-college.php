<?php
/**
 * Pages « Vie au collège » (clubs, ateliers, instances) : contenu et choix du modèle.
 *
 * Chaque page listée ci-dessous (clé = identifiant/slug de la page) utilise automatiquement
 * le modèle page-templates/template-vie-college.php : bandeau, 3 repères, sections,
 * encadrés « Sur cette page » et « Une question ? », puis les derniers articles de la catégorie.
 *
 * Pour modifier un texte : changer la valeur ici. Pour ajouter une page : ajouter une entrée
 * avec le slug de la page, ou choisir le modèle « Vie au collège » dans l'éditeur.
 * Le contenu tapé dans l'éditeur de la page s'affiche en plus, sous le bandeau.
 *
 * Textes à faire valider par le collège.
 *
 * @package Les_Renardiere
 */

/**
 * Contenu des pages « Vie au collège ».
 *
 * Champs : intro (texte du bandeau), reperes (3 × [titre, texte]),
 * sections (titre + texte, ou titre + liste), categorie (slug de la catégorie des actualités).
 *
 * @return array
 */
function les_renardiere_vie_college_pages() {
	return array(
		'atelier-journalisme'          => array(
			'intro'     => 'Le club journalisme et communication fait vivre l’actualité du collège : les élèves choisissent leurs sujets, enquêtent et rédigent les articles publiés sur ce site.',
			'reperes'   => array(
				array( 'D’octobre à juin', 'Une saison de rédaction' ),
				array( 'En ligne', 'Articles publiés sur le site' ),
				array( 'Nouveau', 'Un journal libre en 2026-2027' ),
			),
			'sections'  => array(
				array(
					'titre' => 'Ce que font les élèves',
					'liste' => array(
						'Choisir les sujets et se répartir les articles',
						'Couvrir les sorties, les évènements et la vie du collège',
						'Interviewer élèves, professeurs et intervenants',
						'Rédiger, relire et publier sur le site',
					),
				),
				array(
					'titre' => 'Nouveau cette année : le journal libre',
					'texte' => 'Depuis octobre 2026, le club crée aussi un journal libre, où les élèves écrivent sur les sujets de leur choix.',
				),
				array(
					'titre' => 'Pourquoi s’inscrire ?',
					'texte' => 'Écrire pour de vrais lecteurs, travailler en équipe et apprendre à vérifier ses informations : le club est aussi une école de l’esprit critique.',
				),
			),
			'categorie' => 'atelier-journalisme',
		),

		'les-eco-delegues'             => array(
			'intro'     => 'Dans chaque classe, des élèves volontaires s’engagent pour l’environnement : ils proposent des actions concrètes pour rendre le collège plus durable et sensibilisent leurs camarades.',
			'reperes'   => array(
				array( 'Dans chaque classe', 'Des élèves élus et volontaires' ),
				array( 'Toute l’année', 'Des projets au collège' ),
				array( 'Ensemble', 'Avec les adultes de l’établissement' ),
			),
			'sections'  => array(
				array(
					'titre' => 'Leurs missions',
					'liste' => array(
						'Repérer ce qui peut être amélioré au collège (déchets, énergie, eau…)',
						'Proposer et mener des actions concrètes',
						'Sensibiliser les élèves de leur classe',
						'Faire le lien avec l’équipe du collège',
					),
				),
				array(
					'titre' => 'Comment devenir éco-délégué ?',
					'texte' => 'Les éco-délégués sont élus en début d’année dans chaque classe. Tout élève motivé par l’environnement peut se présenter.',
				),
				array(
					'titre' => 'Pourquoi s’engager ?',
					'texte' => 'Agir concrètement pour la planète, mener un projet du début à la fin et donner l’exemple : être éco-délégué, c’est apprendre à devenir un citoyen responsable.',
				),
			),
			'categorie' => 'les-eco-delegues',
		),

		'conseil-de-la-vie-collegiene' => array(
			'intro'     => 'Le Conseil de la vie collégienne (CVC) donne la parole aux élèves : ses membres font des propositions pour améliorer la vie quotidienne au collège et les portent auprès de la direction.',
			'reperes'   => array(
				array( 'Élèves élus', 'Des représentants de tous les niveaux' ),
				array( 'Réunions', 'Plusieurs fois dans l’année' ),
				array( 'Projets', 'Proposés par et pour les élèves' ),
			),
			'sections'  => array(
				array(
					'titre' => 'Le rôle du CVC',
					'liste' => array(
						'Recueillir les idées et les besoins des élèves',
						'Proposer des projets pour la vie du collège',
						'Participer à l’organisation d’évènements',
						'Échanger avec la direction et les adultes du collège',
					),
				),
				array(
					'titre' => 'Qui en fait partie ?',
					'texte' => 'Des élèves élus, accompagnés d’adultes du collège : direction, enseignants et vie scolaire.',
				),
				array(
					'titre' => 'Pourquoi s’engager ?',
					'texte' => 'Représenter ses camarades, débattre, faire aboutir des idées : le CVC permet d’apprendre la vie démocratique en agissant pour son collège.',
				),
			),
			'categorie' => 'cvc',
		),

		'atelier-robotique'            => array(
			'intro'     => 'À l’atelier robotique, les élèves conçoivent, assemblent et programment des robots : une façon concrète de découvrir les sciences et le numérique en équipe.',
			'reperes'   => array(
				array( 'Concevoir', 'Imaginer et construire un robot' ),
				array( 'Programmer', 'Donner vie à sa création' ),
				array( 'En équipe', 'Un projet mené à plusieurs' ),
			),
			'sections'  => array(
				array(
					'titre' => 'Ce que font les élèves',
					'liste' => array(
						'Monter et câbler des robots',
						'Les programmer pour accomplir une mission',
						'Tester, se tromper et améliorer',
						'Présenter leurs réalisations',
					),
				),
				array(
					'titre' => 'Pourquoi s’inscrire ?',
					'texte' => 'Pas besoin d’être un expert : l’atelier est ouvert à tous les curieux. On y apprend la logique, la patience et le travail en équipe.',
				),
			),
			'categorie' => 'atelier-robotique',
		),

		'web-radio'                    => array(
			'intro'     => 'Avec la web radio, les élèves préparent et enregistrent des émissions : interviews, chroniques et reportages sur la vie du collège, à écouter en ligne.',
			'reperes'   => array(
				array( 'Au micro', 'Les élèves animent les émissions' ),
				array( 'En ligne', 'Des émissions à réécouter' ),
				array( 'De A à Z', 'Écriture, enregistrement et montage' ),
			),
			'sections'  => array(
				array(
					'titre' => 'Ce que font les élèves',
					'liste' => array(
						'Choisir les thèmes des émissions',
						'Préparer les interviews et écrire les chroniques',
						'Enregistrer au micro',
						'Monter le son et diffuser',
					),
				),
				array(
					'titre' => 'Pourquoi s’inscrire ?',
					'texte' => 'Parler en public, s’exprimer clairement, travailler en équipe : la radio aide à prendre confiance en soi.',
				),
			),
			'categorie' => 'webradio',
		),

		'cham'                         => array(
			'intro'     => 'Les classes à horaires aménagés musique (CHAM) permettent aux élèves de suivre un enseignement musical renforcé en plus de leur scolarité au collège.',
			'reperes'   => array(
				array( 'Musique', 'Un enseignement renforcé' ),
				array( 'En groupe', 'Chant et pratique collective' ),
				array( 'Sur scène', 'Des concerts et des projets' ),
			),
			'sections'  => array(
				array(
					'titre' => 'Ce que font les élèves',
					'liste' => array(
						'Pratiquer la musique en groupe',
						'Travailler leur voix et leur oreille',
						'Préparer des concerts et des spectacles',
						'Découvrir des œuvres et des artistes',
					),
				),
				array(
					'titre' => 'Comment intégrer la CHAM ?',
					'texte' => 'L’entrée en CHAM se fait sur dossier. Les modalités et le calendrier sont communiqués par le collège : n’hésitez pas à le contacter.',
				),
			),
			'categorie' => 'cham',
		),
	);
}

/**
 * Contenu de la page en cours (ou valeurs par défaut pour une page qui a juste choisi le modèle).
 *
 * @param WP_Post $page Page affichée.
 * @return array
 */
function les_renardiere_vie_college_donnees( $page ) {
	$pages = les_renardiere_vie_college_pages();

	$donnees = isset( $pages[ $page->post_name ] ) ? $pages[ $page->post_name ] : array();

	return wp_parse_args(
		$donnees,
		array(
			'intro'     => has_excerpt( $page ) ? get_the_excerpt( $page ) : '',
			'reperes'   => array(),
			'sections'  => array(),
			'categorie' => $page->post_name,
		)
	);
}

/**
 * Applique le modèle « Vie au collège » aux pages listées, sauf si un autre modèle a été choisi dans l'éditeur.
 *
 * @param string $template Chemin du modèle choisi par WordPress.
 * @return string
 */
function les_renardiere_vie_college_modele( $template ) {
	if ( ! is_page() || get_page_template_slug() ) {
		return $template;
	}

	if ( array_key_exists( get_post_field( 'post_name', get_queried_object_id() ), les_renardiere_vie_college_pages() ) ) {
		$modele = locate_template( 'page-templates/template-vie-college.php' );
		if ( $modele ) {
			return $modele;
		}
	}

	return $template;
}
add_filter( 'template_include', 'les_renardiere_vie_college_modele' );
