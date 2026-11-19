# Collège Les Renardières — thème Underscores

Conversion du thème à blocs `les-renardiere` en thème classique basé sur [Underscores (_s)](https://underscores.me/).

## Où est quoi

| Ancien (blocs)                      | Nouveau (Underscores)                     |
|-------------------------------------|-------------------------------------------|
| `parts/header.html`                 | `parts/header.html` (gardé en FSE, chargé par `header.php`) |
| `parts/footer.html`                 | `parts/footer.html` (gardé en FSE, chargé par `footer.php`) |
| `templates/front-page.html`         | `front-page.php`                          |
| `templates/home.html`               | `home.php` + `template-parts/card.php`    |
| `templates/page.html`               | `page.php`                                |
| `templates/single.html`             | `single.php`                              |
| `templates/archive.html`            | `archive.php`                             |
| `templates/taxonomy-technologie.html` | `taxonomy-technologie.php`              |
| `templates/404.html`                | `404.php`                                 |
| `templates/contact.html`            | `page-templates/template-contact.php` (modèle « Contact ») |
| `templates/index.html`              | `index.php` (boucle Underscores standard) |
| CPT Projet, Technologies, vues      | `inc/projets.php`                         |

## Styles

- `style.css` (racine) : base Underscores (normalize + styles WordPress). Ne pas y mettre nos styles.
- `assets/sass/` → compilé en `assets/css/style.css` (Live Sass Compiler), chargé après la base.
- `assets/sass/_underscores.scss` : styles du balisage PHP (menu classique, conteneurs, hero, grilles, pagination).
- `theme.json` : palette, tailles de texte, espacements et police Inter pour l'éditeur.

## En-tête et pied de page (FSE)

Le thème est un thème classique Underscores **hybride** : les pages sont en PHP, mais l'en-tête et le pied de page
sont des **parties de modèle FSE** (`add_theme_support( 'block-template-parts' )`).

- Les modifier : **Apparence → Design → Compositions → En-tête / Pied de page**.
- Une fois modifiées dans l'éditeur, elles sont enregistrées dans la base de données (et ne lisent plus `parts/*.html`).
  Pour revenir au fichier : dans l'éditeur, menu ⋮ → « Réinitialiser ».
- Le menu est le bloc Navigation : il se modifie directement dans l'en-tête (Apparence → Design → Navigation).
- Styles : `_header.scss` et `_footer.scss` (classes des blocs, comme dans l'ancien thème).
