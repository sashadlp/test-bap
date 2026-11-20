<?php
/**
 * Page « Section internationale arabe » (identifiant / slug « section-international-arabe »).
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Mise en page : template-parts/section-internationale.php (bandeau, présentation, repères,
 * programme, actualités, contact, lien vers la section américaine).
 * Image : l'image mise en avant de la page si elle existe, sinon le bandeau
 * assets/img/si-arabe-banniere.svg (motif arabesque).
 * Texte : le contenu de la page dans l'éditeur WordPress s'il y en a un, sinon le texte ci-dessous.
 * Actualités : articles de la catégorie « si-arabe ».
 *
 * @package Les_Renardiere
 */

get_header();

get_template_part(
	'template-parts/section-internationale',
	null,
	array(
		'titre'   => 'Section internationale arabe',
		'texte'   => 'La Section Internationale Arabe du Collège Les Renardières propose aux élèves un parcours d’excellence bilingue et biculturel, alliant l’enseignement approfondi de la langue et de la littérature arabes à l’histoire-géographie. Encadré par une équipe pédagogique dédiée et favorisé par des effectifs réduits, ce cursus permet de développer une maîtrise solide de l’arabe littéraire tout en s’ouvrant à la richesse des cultures du monde arabe, préparant ainsi les collégiens au Diplôme National du Brevet option internationale (DNBi).',
		'dessin'  => 'si-arabe-banniere.svg',
		'couleur' => 'arabe',
		'reperes' => array(
			array( '6e → 3e', 'Un parcours sur les quatre années du collège' ),
			array( 'Arabe littéraire', 'Langue et littérature approfondies' ),
			array( 'Effectifs réduits', 'Un suivi attentif de chaque élève' ),
			array( 'DNBi', 'Le brevet option internationale en fin de 3e' ),
		),
		'blocs'   => array(
			array(
				'titre' => 'Langue et littérature arabes',
				'texte' => 'Un enseignement spécifique, assuré par l’équipe de la section, pour gagner en aisance en arabe littéraire, à l’écrit comme à l’oral.',
				'liste' => array(
					'Lecture et étude de textes de la littérature arabe, classique et contemporaine',
					'Expression écrite et orale : rédactions, exposés, échanges en classe',
					'Grammaire et enrichissement du vocabulaire',
				),
			),
			array(
				'titre' => 'Histoire-géographie',
				'texte' => 'Une partie du programme est abordée en arabe : les élèves apprennent à analyser des documents et à expliquer le monde dans les deux langues.',
				'liste' => array(
					'Étude de documents en arabe et en français',
					'Regards croisés sur le monde arabe et la Méditerranée',
					'Préparation aux épreuves spécifiques du DNBi',
				),
			),
			array(
				'titre' => 'Ouverture culturelle',
				'texte' => 'La section fait découvrir la diversité des cultures et des sociétés du monde arabe, et développe un véritable regard biculturel.',
				'liste' => array(
					'Découverte des arts, des traditions et de l’actualité du monde arabe',
					'Projets menés au fil de l’année avec l’équipe de la section',
					'Un bagage bilingue précieux pour la suite des études',
				),
			),
		),
		'actus'   => array( 'si-arabe' ),
		'autre'   => array(
			'titre'  => 'Section internationale américaine',
			'resume' => 'L’anglais à l’écrit comme à l’oral, de Shakespeare à Chimamanda Ngozi Adichie, avec des débats et des projets comme le Yearbook.',
			'page'   => 'si-anglais',
			'dessin' => 'si-americaine-banniere.svg',
		),
	)
);

get_footer();
