=== Collège Les Renardières ===

Contributors: Sasha Passas Youssi et l'équipe BAP
Tags: custom-logo, featured-images, translation-ready

Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 2.0.1
License: GNU General Public License v2 or later
License URI: LICENSE

Thème sur mesure pour le Collège Les Renardières, basé sur Underscores (_s).

== Description ==

Thème classique Underscores « hybride » : les pages sont des modèles PHP, l'en-tête et le pied de page
sont des parties de modèle FSE (parts/header.html, parts/footer.html), modifiables dans
Apparence → Compositions → Parties de modèle (un thème classique n'a pas de menu « Éditeur »).

Le thème déclare aussi le type de contenu « Projet », la taxonomie « Technologies » et un compteur de vues.
Voir README.md pour l'organisation des fichiers et des styles.

== Installation ==

1. Dans l'administration, aller dans Apparence → Thèmes → Ajouter.
2. Cliquer sur Téléverser un thème, choisir le .zip du thème, puis Installer.
3. Activer le thème.
4. Créer les pages vers lesquelles pointent l'accueil et le pied de page (slugs) :
   infos-pratiques, contact, mentions-legales, accessibilite, plan-du-site.

== Changelog ==

= 2.0.1 =
* Compteur de vues : n'enregistre plus les visites des rédacteurs, des robots ni des aperçus.
* Boutons de l'accueil : lien vers la vraie page, masqués si la page n'existe pas.
* Pied de page : reprend le menu de l'en-tête (sous-menus en colonnes), liens légaux construits avec l'adresse du site, année du © automatique.
* Cartes de projets : technologies et nombre de vues.
* Page de recherche alignée sur les autres pages.
* Nettoyage : menu classique, zone de widgets, Customizer, défilement infini Jetpack et polices inutilisés retirés.

= 2.0.0 =
* Conversion du thème à blocs en thème Underscores.

== Credits ==

* Based on Underscores https://underscores.me/, (C) 2012-2020 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* normalize.css https://necolas.github.io/normalize.css/, (C) 2012-2018 Nicolas Gallagher and Jonathan Neal, [MIT](https://opensource.org/licenses/MIT)
