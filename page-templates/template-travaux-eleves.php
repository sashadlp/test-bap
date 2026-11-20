<?php
/**
 * Template Name: Articles par matière
 *
 * Articles rangés par matière (catégories d'articles), avec des boutons de filtre.
 * Utilisé par la page « Matière enseignée » (page-actualite-matiere.php).
 * La page « Les travaux des élèves » a son propre modèle : page-travaux-des-eleves.php.
 *
 * Liste des matières, nombre d'articles par matière et cache : inc/matieres.php.
 * Lien direct vers une matière : ?matiere=anglais (le filtre met l'adresse à jour).
 *
 * Une matière s'affiche si au moins une de ses catégories existe ET contient un article publié.
 * Sinon, un encadré visible seulement par les admins explique pourquoi elle n'apparaît pas.
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_donnees  = les_renardiere_matieres_donnees();
$les_renardiere_sections = $les_renardiere_donnees['sections'];

// Matière choisie dans l'adresse (?matiere=anglais), sinon toutes.
$les_renardiere_choix = isset( $_GET['matiere'] ) ? sanitize_key( $_GET['matiere'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! in_array( $les_renardiere_choix, wp_list_pluck( $les_renardiere_sections, 'slug' ), true ) ) {
	$les_renardiere_choix = '';
}
?>

	<main id="primary" class="site-main page-container page-travaux">

		<header class="page-header travaux-entete">
			<h1 class="page-title"><?php the_title(); ?></h1>
			<p class="travaux-entete__intro"><?php esc_html_e( 'Projets, sorties et travaux des élèves, rangés par matière.', 'les-renardiere' ); ?></p>
		</header>

		<?php
		// Diagnostic pour les admins : pourquoi une matière de la liste n'apparaît pas.
		if ( current_user_can( 'manage_categories' ) ) :
			$les_renardiere_matieres = les_renardiere_matieres_liste();
			$les_renardiere_toutes   = get_categories( array( 'hide_empty' => false ) );
			$les_renardiere_messages = array();

			foreach ( $les_renardiere_donnees['manquants'] as $les_renardiere_cle ) {
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
					$les_renardiere_message .= ' Catégorie proche : ' . implode( ', ', $les_renardiere_proches ) . ' → ajouter son identifiant dans la liste de inc/matieres.php.';
				} elseif ( get_term_by( 'slug', $les_renardiere_cle, 'post_tag' ) ) {
					$les_renardiere_message .= ' C’est une étiquette, pas une catégorie : créer une catégorie avec cet identifiant.';
				} else {
					$les_renardiere_message .= ' La créer dans Articles → Catégories.';
				}
				$les_renardiere_messages[] = $les_renardiere_message;
			}

			foreach ( $les_renardiere_donnees['vides'] as $les_renardiere_nom ) {
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

			<nav class="matiere-filtres" aria-label="<?php esc_attr_e( 'Filtrer par matière', 'les-renardiere' ); ?>">
				<div class="matiere-filtres__liste">
					<a class="matiere-filtre" href="<?php echo esc_url( get_permalink() ); ?>" data-matiere="all"<?php echo $les_renardiere_choix ? '' : ' aria-current="true"'; ?>><?php esc_html_e( 'Toutes les matières', 'les-renardiere' ); ?></a>
					<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
						<a class="matiere-filtre" href="<?php echo esc_url( add_query_arg( 'matiere', $les_renardiere_section['slug'], get_permalink() ) ); ?>" data-matiere="<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>"<?php echo $les_renardiere_choix === $les_renardiere_section['slug'] ? ' aria-current="true"' : ''; ?>>
							<?php echo esc_html( $les_renardiere_section['nom'] ); ?>
							<span class="matiere-filtre__nombre"><?php echo esc_html( number_format_i18n( $les_renardiere_section['total'] ) ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</nav>

			<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
				<section class="section-matiere" id="matiere-<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>" data-matiere="<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>" aria-labelledby="titre-<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>"<?php echo ( $les_renardiere_choix && $les_renardiere_choix !== $les_renardiere_section['slug'] ) ? ' hidden' : ''; ?>>
					<div class="section-matiere__entete">
						<h2 id="titre-<?php echo esc_attr( $les_renardiere_section['slug'] ); ?>"><?php echo esc_html( $les_renardiere_section['nom'] ); ?></h2>
						<span class="section-matiere__nombre">
							<?php
							/* translators: %s: nombre d'articles. */
							echo esc_html( sprintf( _n( '%s article', '%s articles', $les_renardiere_section['total'], 'les-renardiere' ), number_format_i18n( $les_renardiere_section['total'] ) ) );
							?>
						</span>
						<?php if ( $les_renardiere_section['total'] > count( $les_renardiere_section['articles'] ) ) : ?>
							<a class="section-matiere__tout" href="<?php echo esc_url( $les_renardiere_section['lien'] ); ?>">
								<?php esc_html_e( 'Voir tout', 'les-renardiere' ); ?>
								<span class="screen-reader-text"><?php echo esc_html( $les_renardiere_section['nom'] ); ?></span>
								<span aria-hidden="true">→</span>
							</a>
						<?php endif; ?>
					</div>

					<ul class="matiere-grille">
						<?php foreach ( $les_renardiere_section['articles'] as $les_renardiere_article ) : ?>
							<?php get_template_part( 'template-parts/carte-article', null, array( 'article' => $les_renardiere_article ) ); ?>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endforeach; ?>

			<script>
			( function () {
				// Filtre sans recharger la page ; l'adresse (?matiere=…) reste partageable.
				var filtres = document.querySelectorAll( '.matiere-filtre' );
				var sections = document.querySelectorAll( '.section-matiere' );

				function afficher( choix ) {
					filtres.forEach( function ( filtre ) {
						if ( filtre.dataset.matiere === choix ) {
							filtre.setAttribute( 'aria-current', 'true' );
						} else {
							filtre.removeAttribute( 'aria-current' );
						}
					} );
					sections.forEach( function ( section ) {
						section.hidden = ! ( 'all' === choix || section.dataset.matiere === choix );
					} );
				}

				// Téléphone : la ligne de filtres défile jusqu'à la matière choisie.
				var actuel = document.querySelector( '.matiere-filtre[aria-current]' );
				if ( actuel ) {
					var liste = actuel.parentNode;
					liste.scrollLeft = actuel.offsetLeft - liste.offsetLeft - 16;
				}

				filtres.forEach( function ( filtre ) {
					filtre.addEventListener( 'click', function ( e ) {
						e.preventDefault();
						afficher( filtre.dataset.matiere );
						if ( window.history.replaceState ) {
							window.history.replaceState( null, '', filtre.href );
						}
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
