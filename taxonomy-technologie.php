<?php
/**
 * Projets d'une technologie (repris de templates/taxonomy-technologie.html de l'ancien thème à blocs) :
 * nom sans préfixe, description, grille de 9 projets, pagination.
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main page-container">

		<header class="page-header">
			<h1 class="page-title"><?php single_term_title(); ?></h1>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>

		<?php if ( have_posts() ) : ?>

			<ul class="wp-block-post-template posts-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				?>
			</ul>

			<?php
			the_posts_pagination(
				array(
					'prev_text' => esc_html__( 'Précédent', 'les-renardiere' ),
					'next_text' => esc_html__( 'Suivant', 'les-renardiere' ),
				)
			);
			?>

		<?php else : ?>

			<p><?php esc_html_e( 'Aucun projet pour cette technologie.', 'les-renardiere' ); ?></p>

		<?php endif; ?>

	</main><!-- #primary -->

<?php
get_footer();
