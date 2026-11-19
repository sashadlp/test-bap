<?php
/**
 * Article seul (repris de templates/single.html de l'ancien thème à blocs) :
 * image à la une 16/9, date + catégories, titre, contenu.
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main single-article-container">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="single-article-cover">
						<?php the_post_thumbnail( 'large' ); ?>
					</figure>
				<?php endif; ?>

				<div class="single-article-meta">
					<?php les_renardiere_posted_on(); ?>
					<?php if ( 'post' === get_post_type() && has_category() ) : ?>
						<span class="cat-links"><?php the_category( ', ' ); ?></span>
					<?php elseif ( 'projet' === get_post_type() ) : ?>
						<?php the_terms( get_the_ID(), 'technologie', '<span class="cat-links">', ', ', '</span>' ); ?>
						<span class="projet-vues"><?php echo esc_html( les_renardiere_vues_texte() ); ?></span>
					<?php endif; ?>
				</div>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages :', 'les-renardiere' ),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Précédent :', 'les-renardiere' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Suivant :', 'les-renardiere' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>

	</main><!-- #primary -->

<?php
get_footer();
