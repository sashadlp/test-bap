<?php
/**
 * Page (repris de templates/page.html de l'ancien thème à blocs).
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main page-container">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// Commentaires s'ils sont ouverts ou s'il y en a déjà.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>

	</main><!-- #primary -->

<?php
get_footer();
