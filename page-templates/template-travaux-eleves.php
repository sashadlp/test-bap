<?php
/**
 * Template Name: Travaux des élèves
 *
 * Travaux des élèves rangés par matière (catégories d'articles), avec un filtre par matière.
 *
 * Chaque matière accepte une ou plusieurs catégories (identifiants / slugs exacts) : WordPress
 * ajoute parfois un suffixe (« espagnol-pedagogie ») quand un identifiant est déjà pris.
 * Les articles de toutes ces catégories (et de leurs sous-catégories) sont réunis dans une section.
 *
 * Une matière s'affiche si au moins une de ses catégories existe ET contient un article publié.
 * Sinon, un encadré visible seulement par les admins explique pourquoi elle n'apparaît pas.
 *
 * @package Les_Renardiere
 */

get_header();

// Matière => identifiants (slugs) des catégories, en minuscules sans accents.
// Les vérifier dans Articles → Catégories → colonne « Identifiant ».
$les_renardiere_matieres = array(
	'allemand'         => array( 'allemand' ),
	'arts-plastiques'  => array( 'arts-plastiques' ),
	'anglais'          => array( 'anglais' ),
	'espagnol'         => array( 'espagnol', 'espagnol-pedagogie' ),
	'francais'         => array( 'francais' ),
	'physique-chimie'  => array( 'physique-chimie' ),
	'mathematiques'    => array( 'mathematiques', 'mathematiques-pedagogie' ),
	'svt'              => array( 'svt' ),
	'histoire-geo-emc' => array( 'histoire-geo-emc', 'histoire-geographie-emc' ),
	'technologie'      => array( 'technologie' ),
	'lce-anglais'      => array( 'lce-anglais' ),
	'eps'              => array( 'eps' ),
);

// Toutes les catégories de la liste, même vides, indexées par identifiant.
$les_renardiere_par_slug = array();
foreach ( get_categories(
	array(
		'slug'       => array_merge( ...array_values( $les_renardiere_matieres ) ),
		'hide_empty' => false,
	)
) as $les_renardiere_cat ) {
	$les_renardiere_par_slug[ $les_renardiere_cat->slug ] = $les_renardiere_cat;
}

// Une requête par matière ; on ne garde que les matières qui ont des articles publiés.
$les_renardiere_sections  = array();
$les_renardiere_vides     = array();
$les_renardiere_manquants = array();
foreach ( $les_renardiere_matieres as $les_renardiere_cle => $les_renardiere_slugs ) {
	$les_renardiere_cats = array_values( array_intersect_key( $les_renardiere_par_slug, array_flip( $les_renardiere_slugs ) ) );

	if ( ! $les_renardiere_cats ) {
		$les_renardiere_manquants[] = $les_renardiere_cle;
		continue;
	}

	$les_renardiere_q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'cat'                 => implode( ',', wp_list_pluck( $les_renardiere_cats, 'term_id' ) ), // Inclut les sous-catégories.
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $les_renardiere_q->have_posts() ) {
		$les_renardiere_sections[] = array(
			'slug'  => $les_renardiere_cle,
			'name'  => $les_renardiere_cats[0]->name,
			'query' => $les_renardiere_q,
		);
	} else {
		$les_renardiere_vides[] = $les_renardiere_cats[0]->name;
	}
}

// Ordre alphabétique des matières (sans tenir compte des accents).
usort(
	$les_renardiere_sections,
	function ( $a, $b ) {
		return strcasecmp( remove_accents( $a['name'] ), remove_accents( $b['name'] ) );
	}
);
?>

	<main id="primary" class="site-main page-container page-travaux">

		<header class="page-header">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>

		<?php
		// Diagnostic pour les admins : pourquoi une matière de la liste n'apparaît pas.
		if ( current_user_can( 'manage_categories' ) ) :
			$les_renardiere_toutes   = get_categories( array( 'hide_empty' => false ) );
			$les_renardiere_messages = array();

			foreach ( $les_renardiere_manquants as $les_renardiere_cle ) {
				$les_renardiere_slugs = $les_renardiere_matieres[ $les_renardiere_cle ];

				// Catégories au slug ou au nom proche (ex. « espagnole », « espagnol-2 » pour « espagnol »).
				$les_renardiere_proches = array();
				foreach ( $les_renardiere_toutes as $les_renardiere_autre ) {
					if ( 0 === strpos( $les_renardiere_autre->slug, $les_renardiere_cle ) || 0 === strpos( $les_renardiere_cle, $les_renardiere_autre->slug ) || sanitize_title( $les_renardiere_autre->name ) === $les_renardiere_cle ) {
						$les_renardiere_proches[] = sprintf( '« %s » (identifiant : %s)', $les_renardiere_autre->name, $les_renardiere_autre->slug );
					}
				}

				$les_renardiere_message = sprintf( 'Matière « %s » : aucune catégorie avec l’identifiant %s.', $les_renardiere_cle, implode( ' ou ', $les_renardiere_slugs ) );
				if ( $les_renardiere_proches ) {
					$les_renardiere_message .= ' Catégorie proche : ' . implode( ', ', $les_renardiere_proches ) . ' → ajouter son identifiant dans la liste en haut de page-templates/template-travaux-eleves.php.';
				} elseif ( get_term_by( 'slug', $les_renardiere_cle, 'post_tag' ) ) {
					$les_renardiere_message .= ' C’est une étiquette, pas une catégorie : créer une catégorie avec cet identifiant.';
				} else {
					$les_renardiere_message .= ' La créer dans Articles → Catégories.';
				}
				$les_renardiere_messages[] = $les_renardiere_message;
			}

			foreach ( $les_renardiere_vides as $les_renardiere_nom ) {
				$les_renardiere_messages[] = sprintf( 'La matière « %s » existe mais n’a aucun article publié (brouillons, articles privés ou programmés ne comptent pas).', $les_renardiere_nom );
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
						<option value="<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>"><?php echo esc_html( $les_renardiere_section['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
				<section class="section-matiere actualites" data-matiere="<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>">
					<h2 class="actualites__titre"><?php echo esc_html( $les_renardiere_section['name'] ); ?></h2>

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
