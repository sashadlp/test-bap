<?php
/**
 * Template Name: Contact
 *
 * Repris de templates/contact.html de l'ancien thème à blocs (qui ne contenait que l'en-tête et le pied de page).
 * Pour l'instant : titre + contenu de la page.
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main page-container page-contact">

		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'page' );
		endwhile;
		?>

	</main><!-- #primary -->

<?php
get_footer();
