<?php
/**
 * Page « Section internationale arabe » (identifiant / slug « section-international-arabe »).
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Mise en page : template-parts/section-internationale.php (image, titre, texte).
 * Image : l'image mise en avant de la page si elle existe, sinon le bandeau
 * assets/img/si-arabe-banniere.svg (motif arabesque).
 * Texte : le contenu de la page dans l'éditeur WordPress s'il y en a un, sinon le texte ci-dessous.
 *
 * @package Les_Renardiere
 */

get_header();

get_template_part(
	'template-parts/section-internationale',
	null,
	array(
		'titre'  => 'Section internationale arabe',
		'texte'  => 'La Section Internationale Arabe du Collège Les Renardières propose aux élèves un parcours d’excellence bilingue et biculturel, alliant l’enseignement approfondi de la langue et de la littérature arabes à l’histoire-géographie. Encadré par une équipe pédagogique dédiée et favorisé par des effectifs réduits, ce cursus permet de développer une maîtrise solide de l’arabe littéraire tout en s’ouvrant à la richesse des cultures du monde arabe, préparant ainsi les collégiens au Diplôme National du Brevet option internationale (DNBi).',
		'dessin' => 'si-arabe-banniere.svg',
	)
);

get_footer();
