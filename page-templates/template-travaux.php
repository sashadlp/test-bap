<?php
/**
 * Template Name: Travaux des élèves
 *
 * @package Les_Renardiere
 */

get_header();

// Liste exacte des slugs (identifiants) en minuscules sans accents
$matieres_autorisees = array(
	'allemand',
	'arts-plastiques',
	'anglais',
	'espagnol',
	'francais',
	'physique-chimie',
	'mathematiques',
	'svt',
	'histoire-geo-emc',
	'technologie',
	'lce-anglais',
	'eps',
);

$categories = get_categories(
	array(
		'slug'       => $matieres_autorisees,
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<main id="primary" class="site-main page-container">

	<header class="page-header">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</header>

	<!-- Menu déroulant de filtre -->
	<div class="filtre-matieres" style="margin-bottom: 2rem;">
		<label for="select-matiere"><?php esc_html_e( 'Matière :', 'les-renardiere' ); ?></label>
		<select id="select-matiere">
			<option value="all"><?php esc_html_e( 'Toutes les matières', 'les-renardiere' ); ?></option>
			<?php foreach ( $categories as $cat ) : ?>
				<option value="<?php echo esc_attr( $cat->slug ); ?>">
					<?php echo esc_html( $cat->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

	<!-- Boucle automatique par matière -->
	<?php if ( ! empty( $categories ) ) : ?>
		<?php foreach ( $categories as $cat ) : ?>
			<section class="section-matiere actualites" data-matiere="<?php echo esc_attr( $cat->slug ); ?>">
				<h2 class="actualites__titre"><?php echo esc_html( $cat->name ); ?></h2>

				<?php
				$q = new WP_Query(
					array(
						'post_type'           => 'post',
						'cat'                 => $cat->term_id,
						'posts_per_page'      => 6,
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);
				?>

				<?php if ( $q->have_posts() ) : ?>
					<ul class="wp-block-post-template posts-grid">
						<?php
						while ( $q->have_posts() ) :
							$q->the_post();
							get_template_part( 'template-parts/card' );
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				<?php endif; ?>
			</section>
		<?php endforeach; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Aucun travail publié pour le moment.', 'les-renardiere' ); ?></p>
	<?php endif; ?>

</main><!-- #primary -->

<script>
document.getElementById('select-matiere').addEventListener('change', function() {
	const choix = this.value;
	document.querySelectorAll('.section-matiere').forEach(section => {
		section.style.display = (choix === 'all' || section.dataset.matiere === choix) ? 'block' : 'none';
	});
});
</script>

<?php
get_footer();