<?php
/**
 * Page « Section internationale américaine » (identifiant / slug « si-anglais »).
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Mise en page : template-parts/section-internationale.php (bandeau, présentation, repères,
 * programme, actualités, contact, lien vers la section arabe).
 * Image : l'image mise en avant de la page si elle existe, sinon le bandeau
 * assets/img/si-americaine-banniere.svg (semis d’étoiles).
 * Texte : le contenu de la page dans l'éditeur WordPress s'il y en a un, sinon le texte ci-dessous
 * (rédigé sur le modèle de la section arabe, d’après les projets publiés sur le site).
 * Actualités : articles de la catégorie « si-americaine ».
 *
 * @package Les_Renardiere
 */

get_header();

get_template_part(
	'template-parts/section-internationale',
	null,
	array(
		'titre'   => 'Section internationale américaine',
		'texte'   => 'La Section Internationale Américaine du Collège Les Renardières propose aux élèves un parcours d’excellence bilingue et biculturel, alliant l’enseignement approfondi de la langue et de la littérature anglo-américaines à l’histoire-géographie. Encadré par une équipe pédagogique dédiée, ce cursus permet de développer une maîtrise solide de l’anglais, à l’écrit comme à l’oral, à travers la lecture d’œuvres de Shakespeare à Chimamanda Ngozi Adichie, des débats et des projets comme le Yearbook ou un voyage sur l’île de Wight, tout en s’ouvrant à la richesse des cultures du monde anglophone, préparant ainsi les collégiens au Diplôme National du Brevet option internationale (DNBi).',
		'dessin'  => 'si-americaine-banniere.svg',
		'couleur' => 'americaine',
		'reperes' => array(
			array( '6e → 3e', 'Un parcours sur les quatre années du collège' ),
			array( 'Anglais', 'Langue et littérature anglo-américaines' ),
			array( 'Débats', 'Prendre la parole avec aisance' ),
			array( 'DNBi', 'Le brevet option internationale en fin de 3e' ),
		),
		'blocs'   => array(
			array(
				'titre' => 'Langue et littérature',
				'texte' => 'Un enseignement renforcé de l’anglais, fondé sur la lecture d’œuvres complètes et la pratique régulière de l’écrit et de l’oral.',
				'liste' => array(
					'Des œuvres du monde anglophone, de Shakespeare à Chimamanda Ngozi Adichie',
					'Débats, exposés et prises de parole en anglais',
					'Analyse de textes et écriture personnelle',
				),
			),
			array(
				'titre' => 'Histoire-géographie',
				'texte' => 'Une partie du programme est abordée en anglais : les élèves apprennent à analyser des documents et à expliquer le monde dans les deux langues.',
				'liste' => array(
					'Étude de documents en anglais et en français',
					'Regards croisés sur les États-Unis et le monde anglophone',
					'Préparation aux épreuves spécifiques du DNBi',
				),
			),
			array(
				'titre' => 'Projets et voyages',
				'texte' => 'La section fait vivre l’anglais au-delà de la classe, à travers des projets concrets et des découvertes sur le terrain.',
				'liste' => array(
					'Le Yearbook, réalisé par les élèves',
					'Un voyage sur l’île de Wight',
					'Une ouverture sur les cultures du monde anglophone',
				),
			),
		),
		'actus'   => array( 'si-americaine' ),
		'autre'   => array(
			'titre'  => 'Section internationale arabe',
			'resume' => 'Une maîtrise solide de l’arabe littéraire et une ouverture sur la richesse des cultures du monde arabe, avec des effectifs réduits.',
			'page'   => 'section-international-arabe',
			'dessin' => 'si-arabe-banniere.svg',
		),
	)
);

get_footer();
