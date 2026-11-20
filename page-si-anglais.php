<?php
/**
 * Page « Section internationale américaine » (identifiant / slug « si-anglais »).
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Mise en page : template-parts/section-internationale.php (image, titre, texte).
 * Image : l'image mise en avant de la page si elle existe, sinon le bandeau
 * assets/img/si-americaine-banniere.svg (semis d’étoiles).
 * Texte : le contenu de la page dans l'éditeur WordPress s'il y en a un, sinon le texte ci-dessous
 * (rédigé sur le modèle de la section arabe, d’après les projets publiés sur le site).
 *
 * @package Les_Renardiere
 */

get_header();

get_template_part(
	'template-parts/section-internationale',
	null,
	array(
		'titre'  => 'Section internationale américaine',
		'texte'  => 'La Section Internationale Américaine du Collège Les Renardières propose aux élèves un parcours d’excellence bilingue et biculturel, alliant l’enseignement approfondi de la langue et de la littérature anglo-américaines à l’histoire-géographie. Encadré par une équipe pédagogique dédiée, ce cursus permet de développer une maîtrise solide de l’anglais, à l’écrit comme à l’oral, à travers la lecture d’œuvres de Shakespeare à Chimamanda Ngozi Adichie, des débats et des projets comme le Yearbook ou un voyage sur l’île de Wight, tout en s’ouvrant à la richesse des cultures du monde anglophone, préparant ainsi les collégiens au Diplôme National du Brevet option internationale (DNBi).',
		'dessin' => 'si-americaine-banniere.svg',
	)
);

get_footer();
