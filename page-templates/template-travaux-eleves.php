<?php
/**
 * Template Name: Travaux des élèves
 *
 * Travaux des élèves rangés par matière (catégories d'articles), avec un filtre par matière.
 *
 * Une matière s'affiche si sa catégorie existe avec l'identifiant (slug) exact de la liste
 * ci-dessous ET contient au moins un article publié (directement ou dans une sous-catégorie).
 * Sinon, un encadré visible seulement par les admins explique pourquoi elle n'apparaît pas.
 *
 * @package Les_Renardiere
 */

get_header();

// Identifiants (slugs) exacts des catégories, en minuscules sans accents.
// Les vérifier dans Articles → Catégories → colonne « Identifiant ».
$les_renardiere_matieres = array(
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

// Toutes les catégories de la liste, même vides : on décide nous-mêmes quoi afficher.
$les_renardiere_categories = get_categories(
	array(
		'slug'       => $les_renardiere_matieres,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

// Une requête par matière ; on ne garde que les matières qui ont des articles publiés.
$les_renardiere_sections = array();
$les_renardiere_vides    = array();
foreach ( $les_renardiere_categories as $les_renardiere_cat ) {
	$les_renardiere_q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'cat'                 => $les_renardiere_cat->term_id, // Inclut les sous-catégories.
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $les_renardiere_q->have_posts() ) {
		$les_renardiere_sections[] = array(
			'cat'   => $les_renardiere_cat,
			'query' => $les_renardiere_q,
		);
	} else {
		$les_renardiere_vides[] = $les_renardiere_cat;
	}
}
?>

	<main id="primary" class="site-main page-container page-travaux">

		<header class="page-header">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>

		<?php
		// Diagnostic pour les admins : pourquoi une matière de la liste n'apparaît pas.
		if ( current_user_can( 'manage_categories' ) ) :
			$les_renardiere_trouves   = wp_list_pluck( $les_renardiere_categories, 'slug' );
			$les_renardiere_manquants = array_diff( $les_renardiere_matieres, $les_renardiere_trouves );
			$les_renardiere_toutes    = get_categories( array( 'hide_empty' => false ) );
			$les_renardiere_messages  = array();

			foreach ( $les_renardiere_manquants as $les_renardiere_slug ) {
				// Catégories au slug proche (ex. « espagnole », « espagnol-2 » pour « espagnol »).
				$les_renardiere_proches = array();
				foreach ( $les_renardiere_toutes as $les_renardiere_autre ) {
					if ( 0 === strpos( $les_renardiere_autre->slug, $les_renardiere_slug ) || 0 === strpos( $les_renardiere_slug, $les_renardiere_autre->slug ) || 0 === strcasecmp( sanitize_title( $les_renardiere_autre->name ), $les_renardiere_slug ) ) {
						$les_renardiere_proches[] = sprintf( '« %s » (identifiant : %s)', $les_renardiere_autre->name, $les_renardiere_autre->slug );
					}
				}

				$les_renardiere_message = sprintf( 'Aucune catégorie avec l’identifiant « %s ».', $les_renardiere_slug );
				if ( $les_renardiere_proches ) {
					$les_renardiere_message .= ' Catégorie proche : ' . implode( ', ', $les_renardiere_proches ) . ' → changer son identifiant en « ' . $les_renardiere_slug . ' ».';
				} elseif ( get_term_by( 'slug', $les_renardiere_slug, 'post_tag' ) ) {
					$les_renardiere_message .= ' C’est une étiquette, pas une catégorie : créer une catégorie avec cet identifiant.';
				} else {
					$les_renardiere_message .= ' La créer dans Articles → Catégories.';
				}
				$les_renardiere_messages[] = $les_renardiere_message;
			}

			foreach ( $les_renardiere_vides as $les_renardiere_cat ) {
				$les_renardiere_messages[] = sprintf( 'La catégorie « %s » existe mais n’a aucun article publié (brouillons, articles privés ou programmés ne comptent pas).', $les_renardiere_cat->name );
			}

			if ( $les_renardiere_messages ) :
				?>
				<div class="travaux-diagnostic">
					<p><strong>Visible seulement par les admins : matières non affichées</strong></p>
					<ul>
						<?php foreach ( $les_renardiere_messages as $les_renardiere_message ) : ?>
							<li><?php echo esc_html( $les_renardiere_message ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php
			endif;
		endif;
		?>

		<?php if ( $les_renardiere_sections ) : ?>

			<div class="filtre-matieres">
				<label for="select-matiere"><?php esc_html_e( 'Matière :', 'les-renardiere' ); ?></label>
				<select id="select-matiere">
					<option value="all"><?php esc_html_e( 'Toutes les matières', 'les-renardiere' ); ?></option>
					<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
						<option value="<?php echo esc_attr( $les_renardiere_section['cat']->slug ); ?>"><?php echo esc_html( $les_renardiere_section['cat']->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
				<section class="section-matiere actualites" data-matiere="<?php echo esc_attr( $les_renardiere_section['cat']->slug ); ?>">
					<h2 class="actualites__titre"><?php echo esc_html( $les_renardiere_section['cat']->name ); ?></h2>

					<ul class="wp-block-post-template posts-grid">
						<?php
						while ( $les_renardiere_section['query']->have_posts() ) :
							$les_renardiere_section['query']->the_post();
							get_template_part( 'template-parts/card' );
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</section>
			<?php endforeach; ?>

			<script>
			( function () {
				var select = document.getElementById( 'select-matiere' );
				if ( ! select ) {
					return;
				}
				select.addEventListener( 'change', function () {
					var choix = this.value;
					document.querySelectorAll( '.section-matiere' ).forEach( function ( section ) {
						section.hidden = ! ( 'all' === choix || section.dataset.matiere === choix );
					} );
				} );
			}() );
			</script>

		<?php else : ?>
			<p><?php esc_html_e( 'Aucun travail publié pour le moment.', 'les-renardiere' ); ?></p>
		<?php endif; ?>

	</main><!-- #primary -->

<?php
get_footer();
