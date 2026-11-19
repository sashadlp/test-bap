<?php
/**
 * Page des articles / actualités (repris de templates/home.html de l'ancien thème à blocs) :
 * titre, image à la une, extrait, pagination.
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main page-container">

		<?php if ( ! is_front_page() ) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>
		<?php endif; ?>

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

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</main><!-- #primary -->

<?php
get_footer();
