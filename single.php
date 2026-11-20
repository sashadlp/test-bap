<?php
/**
 * Article seul (actualités, travaux des élèves) et projet :
 * fil d'Ariane, rubrique, titre, date et temps de lecture, texte (sans image en tête),
 * pied d'article (rubrique, copier le lien), puis articles précédent / suivant,
 * articles de la même rubrique et retour aux travaux.
 *
 * Texte : paragraphes vides retirés et signature finale mise en forme
 * (les_renardiere_contenu_article() dans inc/template-functions.php).
 * Styles : assets/sass/_article.scss (cartes : _matieres.scss).
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_travaux = les_renardiere_page_url( 'travaux-des-eleves' );
?>

	<main id="primary" class="site-main article-page">

		<?php
		while ( have_posts() ) :
			the_post();

			$les_renardiere_est_article = 'post' === get_post_type();
			$les_renardiere_rubrique    = $les_renardiere_est_article ? les_renardiere_rubrique_principale() : null;
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>

				<header class="article-entete">
					<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
						<ol>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
							<?php if ( $les_renardiere_est_article && $les_renardiere_travaux ) : ?>
								<li><a href="<?php echo esc_url( $les_renardiere_travaux ); ?>"><?php esc_html_e( 'Travaux des élèves', 'les-renardiere' ); ?></a></li>
							<?php endif; ?>
							<?php if ( $les_renardiere_rubrique ) : ?>
								<li><a href="<?php echo esc_url( get_category_link( $les_renardiere_rubrique ) ); ?>"><?php echo esc_html( $les_renardiere_rubrique->name ); ?></a></li>
							<?php endif; ?>
						</ol>
					</nav>

					<?php if ( $les_renardiere_rubrique ) : ?>
						<a class="article-rubrique" href="<?php echo esc_url( get_category_link( $les_renardiere_rubrique ) ); ?>"><?php echo esc_html( $les_renardiere_rubrique->name ); ?></a>
					<?php elseif ( 'projet' === get_post_type() ) : ?>
						<?php the_terms( get_the_ID(), 'technologie', '<span class="article-rubrique">', ', ', '</span>' ); ?>
					<?php endif; ?>

					<?php the_title( '<h1 class="article-titre">', '</h1>' ); ?>

					<p class="article-meta">
						<span>
							<?php
							printf(
								/* translators: %s: date de publication. */
								esc_html__( 'Publié le %s', 'les-renardiere' ),
								'<time datetime="' . esc_attr( get_the_date( DATE_W3C ) ) . '">' . esc_html( get_the_date() ) . '</time>'
							);
							?>
						</span>
						<span><?php echo esc_html( les_renardiere_temps_lecture() ); ?></span>
						<?php if ( 'projet' === get_post_type() ) : ?>
							<span class="projet-vues"><?php echo esc_html( les_renardiere_vues_texte() ); ?></span>
						<?php endif; ?>
					</p>
				</header>

				<div class="entry-content article-contenu">
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

				<footer class="article-pied">
					<?php if ( $les_renardiere_rubrique ) : ?>
						<p class="article-pied__rubrique">
							<?php esc_html_e( 'Publié dans', 'les-renardiere' ); ?>
							<a href="<?php echo esc_url( get_category_link( $les_renardiere_rubrique ) ); ?>"><?php echo esc_html( $les_renardiere_rubrique->name ); ?></a>
						</p>
					<?php endif; ?>
					<button type="button" class="bouton article-copier" data-lien="<?php echo esc_url( get_permalink() ); ?>">
						<span class="article-copier__texte"><?php esc_html_e( 'Copier le lien', 'les-renardiere' ); ?></span>
					</button>
					<span class="screen-reader-text" role="status" aria-live="polite"></span>
				</footer>

			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			// Précédent / suivant : dans la même rubrique pour les articles.
			$les_renardiere_meme = $les_renardiere_est_article && $les_renardiere_rubrique;
			the_post_navigation(
				array(
					'prev_text'          => '<span class="nav-subtitle">' . esc_html__( '← Article précédent', 'les-renardiere' ) . '</span> <span class="nav-title">%title</span>',
					'next_text'          => '<span class="nav-subtitle">' . esc_html__( 'Article suivant →', 'les-renardiere' ) . '</span> <span class="nav-title">%title</span>',
					'in_same_term'       => $les_renardiere_meme,
					'taxonomy'           => 'category',
					'screen_reader_text' => __( 'Navigation entre les articles', 'les-renardiere' ),
				)
			);

			// Autres articles de la même rubrique.
			if ( $les_renardiere_rubrique ) :
				$les_renardiere_autres = get_posts(
					array(
						'cat'                    => $les_renardiere_rubrique->term_id,
						'post__not_in'           => array( get_the_ID() ),
						'posts_per_page'         => 3,
						'ignore_sticky_posts'    => true,
						'no_found_rows'          => true,
						'update_post_term_cache' => false,
					)
				);

				if ( $les_renardiere_autres ) :
					?>
					<section class="article-autres" aria-labelledby="article-autres-titre">
						<div class="section-matiere__entete">
							<h2 id="article-autres-titre">
								<?php
								/* translators: %s: nom de la rubrique. */
								echo esc_html( sprintf( __( 'Aussi en %s', 'les-renardiere' ), $les_renardiere_rubrique->name ) );
								?>
							</h2>
							<a class="section-matiere__tout" href="<?php echo esc_url( get_category_link( $les_renardiere_rubrique ) ); ?>"><?php esc_html_e( 'Tout voir', 'les-renardiere' ); ?> <span aria-hidden="true">→</span></a>
						</div>
						<ul class="matiere-grille">
							<?php foreach ( $les_renardiere_autres as $les_renardiere_autre ) : ?>
								<?php get_template_part( 'template-parts/carte-article', null, array( 'article' => les_renardiere_article_resume( $les_renardiere_autre ) ) ); ?>
							<?php endforeach; ?>
						</ul>
					</section>
					<?php
				endif;
			endif;
			?>

			<?php if ( $les_renardiere_est_article && $les_renardiere_travaux ) : ?>
				<p class="article-retour">
					<a class="bouton" href="<?php echo esc_url( $les_renardiere_travaux ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'Tous les travaux des élèves', 'les-renardiere' ); ?></a>
				</p>
			<?php endif; ?>

			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>

		<script>
		( function () {
			// Bouton « Copier le lien » : copie l'adresse de l'article et le confirme.
			var bouton = document.querySelector( '.article-copier' );
			if ( ! bouton || ! navigator.clipboard ) {
				if ( bouton ) {
					bouton.hidden = true;
				}
				return;
			}
			var texte = bouton.querySelector( '.article-copier__texte' );
			var annonce = bouton.nextElementSibling;
			var initial = texte.textContent;
			bouton.addEventListener( 'click', function () {
				navigator.clipboard.writeText( bouton.getAttribute( 'data-lien' ) ).then( function () {
					texte.textContent = <?php echo wp_json_encode( __( 'Lien copié', 'les-renardiere' ) ); ?>;
					annonce.textContent = texte.textContent;
					setTimeout( function () {
						texte.textContent = initial;
						annonce.textContent = '';
					}, 2000 );
				} );
			} );
		}() );
		</script>

	</main><!-- #primary -->

<?php
get_footer();
