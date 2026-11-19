<?php
/**
 * Archives (repris de templates/archive.html de l'ancien thème à blocs) : titre de l'archive, titre + extrait.
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main page-container">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header>

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-item' ); ?>>
					<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
					<div class="entry-summary"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;

			the_posts_pagination(
				array(
					'prev_text' => esc_html__( 'Précédent', 'les-renardiere' ),
					'next_text' => esc_html__( 'Suivant', 'les-renardiere' ),
				)
			);

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #primary -->

<?php
get_footer();
