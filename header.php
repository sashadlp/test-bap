<?php
/**
 * En-tête : <head> + partie de modèle FSE « header » (parts/header.html).
 * Le contenu de l'en-tête se modifie dans Apparence → Éditeur → Compositions → Parties de modèle → En-tête.
 *
 * @package Les_Renardiere
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Aller au contenu', 'les-renardiere' ); ?></a>

	<?php block_template_part( 'header' ); ?>
