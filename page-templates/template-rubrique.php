<?php
/**
 * Page rubrique : bandeau, repères, sections de texte, liens utiles, actualités et contact.
 *
 * Utilisé automatiquement par les pages listées dans inc/rubriques.php (Orientation, Découverte
 * des métiers). Le contenu se modifie dans ce fichier-là.
 *
 * @package Les_Renardiere
 */

$les_renardiere_rubrique = les_renardiere_rubrique_actuelle();

get_header();

if ( ! $les_renardiere_rubrique ) :
	// Page qui n'est pas (ou plus) dans la liste : contenu simple.
	?>
	<main id="primary" class="site-main page-container">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'page' );
		endwhile;
		?>
	</main>
	<?php
	get_footer();
	return;
endif;

$les_renardiere_actus   = les_renardiere_rubrique_actus( $les_renardiere_rubrique['slug'], $les_renardiere_rubrique['actus'] );
$les_renardiere_contact = les_renardiere_page_url( 'contact' );
$les_renardiere_contact = $les_renardiere_contact ? $les_renardiere_contact : home_url( '/contact/' );
$les_renardiere_liens   = array_filter(
	isset( $les_renardiere_rubrique['liens'] ) ? $les_renardiere_rubrique['liens'] : array(),
	function ( $lien ) {
		return ! empty( $lien[1] );
	}
);
?>

	<main id="primary" class="site-main page-container page-rubrique">

		<?php
		while ( have_posts() ) :
			the_post();
			$les_renardiere_titre   = isset( $les_renardiere_rubrique['titre'] ) ? $les_renardiere_rubrique['titre'] : get_the_title();
			$les_renardiere_contenu = get_the_content();
			// Texte de remplissage (« Lorem ipsum ») laissé dans l'éditeur : on ne l'affiche pas.
			$les_renardiere_contenu = ( $les_renardiere_contenu && false === stripos( $les_renardiere_contenu, 'lorem ipsum' ) );
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<?php if ( ! empty( $les_renardiere_rubrique['parent']['nom'] ) ) : ?>
						<li>
							<?php if ( $les_renardiere_rubrique['parent']['url'] ) : ?>
								<a href="<?php echo esc_url( $les_renardiere_rubrique['parent']['url'] ); ?>"><?php echo esc_html( $les_renardiere_rubrique['parent']['nom'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $les_renardiere_rubrique['parent']['nom'] ); ?>
							<?php endif; ?>
						</li>
					<?php endif; ?>
					<li aria-current="page"><?php echo esc_html( $les_renardiere_titre ); ?></li>
				</ol>
			</nav>

			<header class="rubrique-bandeau">
				<?php if ( ! empty( $les_renardiere_rubrique['parent']['nom'] ) ) : ?>
					<p class="rubrique-bandeau__surtitre"><?php echo esc_html( $les_renardiere_rubrique['parent']['nom'] ); ?></p>
				<?php endif; ?>
				<h1 class="rubrique-bandeau__titre"><?php echo esc_html( $les_renardiere_titre ); ?></h1>
				<p class="rubrique-bandeau__chapo"><?php echo esc_html( $les_renardiere_rubrique['chapo'] ); ?></p>

				<div class="rubrique-bandeau__boutons">
					<?php if ( ! empty( $les_renardiere_rubrique['bouton'] ) ) : ?>
						<a class="bouton rubrique-bouton--clair" href="<?php echo esc_url( $les_renardiere_rubrique['bouton'][1] ); ?>" target="_blank" rel="noopener">
							<?php echo esc_html( $les_renardiere_rubrique['bouton'][0] ); ?>
							<span class="screen-reader-text"><?php esc_html_e( '(nouvel onglet)', 'les-renardiere' ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $les_renardiere_actus ) : ?>
						<a class="bouton rubrique-bouton--contour" href="#actualites"><?php esc_html_e( 'Voir les actualités', 'les-renardiere' ); ?></a>
					<?php endif; ?>
				</div>
			</header>

			<?php if ( ! empty( $les_renardiere_rubrique['reperes'] ) ) : ?>
				<ul class="rubrique-reperes">
					<?php foreach ( $les_renardiere_rubrique['reperes'] as $les_renardiere_repere ) : ?>
						<li>
							<strong><?php echo esc_html( $les_renardiere_repere[0] ); ?></strong>
							<span><?php echo esc_html( $les_renardiere_repere[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<div class="rubrique-layout">

				<div class="rubrique-contenu">
					<?php if ( $les_renardiere_contenu ) : ?>
						<div class="entry-content rubrique-editeur"><?php the_content(); ?></div>
					<?php endif; ?>

					<?php foreach ( $les_renardiere_rubrique['sections'] as $les_renardiere_i => $les_renardiere_section ) : ?>
						<section class="rubrique-section" id="<?php echo esc_attr( 'section-' . ( $les_renardiere_i + 1 ) ); ?>">
							<h2><?php echo esc_html( $les_renardiere_section['titre'] ); ?></h2>
							<?php foreach ( isset( $les_renardiere_section['texte'] ) ? $les_renardiere_section['texte'] : array() as $les_renardiere_paragraphe ) : ?>
								<p><?php echo esc_html( $les_renardiere_paragraphe ); ?></p>
							<?php endforeach; ?>
							<?php if ( ! empty( $les_renardiere_section['liste'] ) ) : ?>
								<ul class="rubrique-liste">
									<?php foreach ( $les_renardiere_section['liste'] as $les_renardiere_item ) : ?>
										<li><?php echo esc_html( $les_renardiere_item ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</section>
					<?php endforeach; ?>
				</div>

				<aside class="rubrique-aside">
					<nav class="rubrique-carte" aria-label="<?php esc_attr_e( 'Sur cette page', 'les-renardiere' ); ?>">
						<p class="rubrique-carte__titre"><?php esc_html_e( 'Sur cette page', 'les-renardiere' ); ?></p>
						<ul class="rubrique-sommaire">
							<?php foreach ( $les_renardiere_rubrique['sections'] as $les_renardiere_i => $les_renardiere_section ) : ?>
								<li><a href="<?php echo esc_attr( '#section-' . ( $les_renardiere_i + 1 ) ); ?>"><?php echo esc_html( $les_renardiere_section['titre'] ); ?></a></li>
							<?php endforeach; ?>
							<?php if ( $les_renardiere_actus ) : ?>
								<li><a href="#actualites"><?php esc_html_e( 'Actualités', 'les-renardiere' ); ?></a></li>
							<?php endif; ?>
						</ul>
					</nav>

					<?php if ( $les_renardiere_liens ) : ?>
						<div class="rubrique-carte">
							<p class="rubrique-carte__titre"><?php esc_html_e( 'Liens utiles', 'les-renardiere' ); ?></p>
							<ul class="rubrique-liens">
								<?php foreach ( $les_renardiere_liens as $les_renardiere_lien ) : ?>
									<?php $les_renardiere_externe = 0 !== strpos( $les_renardiere_lien[1], home_url() ); ?>
									<li>
										<a href="<?php echo esc_url( $les_renardiere_lien[1] ); ?>"<?php echo $les_renardiere_externe ? ' target="_blank" rel="noopener"' : ''; ?>>
											<?php echo esc_html( $les_renardiere_lien[0] ); ?>
											<?php if ( $les_renardiere_externe ) : ?>
												<span aria-hidden="true">↗</span>
												<span class="screen-reader-text"><?php esc_html_e( '(nouvel onglet)', 'les-renardiere' ); ?></span>
											<?php endif; ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<div class="rubrique-carte rubrique-carte--contact">
						<p class="rubrique-carte__titre"><?php esc_html_e( 'Une question ?', 'les-renardiere' ); ?></p>
						<p><?php esc_html_e( 'La vie scolaire vous répond par téléphone ou par message.', 'les-renardiere' ); ?></p>
						<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
					</div>
				</aside>

			</div>

			<?php if ( $les_renardiere_actus ) : ?>
				<section class="rubrique-actus" id="actualites" aria-labelledby="actualites-titre">
					<div class="section-matiere__entete">
						<h2 id="actualites-titre"><?php esc_html_e( 'Actualités', 'les-renardiere' ); ?></h2>
						<?php if ( count( $les_renardiere_actus ) > 1 ) : ?>
							<div class="rubrique-onglets" role="tablist" aria-label="<?php esc_attr_e( 'Choisir les actualités', 'les-renardiere' ); ?>">
								<?php
								$les_renardiere_premier = true;
								foreach ( array_keys( $les_renardiere_actus ) as $les_renardiere_j => $les_renardiere_groupe ) :
									?>
									<button type="button" role="tab" class="matiere-filtre" id="onglet-actus-<?php echo esc_attr( $les_renardiere_j ); ?>" aria-controls="actus-<?php echo esc_attr( $les_renardiere_j ); ?>" aria-selected="<?php echo $les_renardiere_premier ? 'true' : 'false'; ?>" tabindex="<?php echo $les_renardiere_premier ? '0' : '-1'; ?>"><?php echo esc_html( $les_renardiere_groupe ); ?></button>
									<?php
									$les_renardiere_premier = false;
								endforeach;
								?>
							</div>
						<?php endif; ?>
					</div>

					<?php
					$les_renardiere_premier = true;
					foreach ( array_values( $les_renardiere_actus ) as $les_renardiere_j => $les_renardiere_groupe ) :
						?>
						<div class="rubrique-actus__groupe" id="actus-<?php echo esc_attr( $les_renardiere_j ); ?>"<?php echo count( $les_renardiere_actus ) > 1 ? ' role="tabpanel" aria-labelledby="onglet-actus-' . esc_attr( $les_renardiere_j ) . '"' : ''; ?><?php echo $les_renardiere_premier ? '' : ' hidden'; ?>>
							<ul class="matiere-grille">
								<?php foreach ( $les_renardiere_groupe['articles'] as $les_renardiere_article ) : ?>
									<?php get_template_part( 'template-parts/carte-article', null, array( 'article' => $les_renardiere_article ) ); ?>
								<?php endforeach; ?>
							</ul>
							<?php if ( $les_renardiere_groupe['total'] > count( $les_renardiere_groupe['articles'] ) ) : ?>
								<p class="rubrique-actus__tout">
									<a class="bouton" href="<?php echo esc_url( $les_renardiere_groupe['lien'] ); ?>">
										<?php
										/* translators: %s: nombre d'articles. */
										echo esc_html( sprintf( __( 'Voir les %s articles', 'les-renardiere' ), number_format_i18n( $les_renardiere_groupe['total'] ) ) );
										?>
									</a>
								</p>
							<?php endif; ?>
						</div>
						<?php
						$les_renardiere_premier = false;
					endforeach;
					?>
				</section>
			<?php endif; ?>

			<aside class="rubrique-cta">
				<div>
					<p class="rubrique-cta__titre"><?php echo esc_html( $les_renardiere_rubrique['cta'][0] ); ?></p>
					<p><?php echo esc_html( $les_renardiere_rubrique['cta'][1] ); ?></p>
				</div>
				<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
			</aside>

		<?php endwhile; ?>

		<script>
		( function () {
			// Onglets des actualités (ex. Section américaine / Section arabe).
			var onglets = Array.prototype.slice.call( document.querySelectorAll( '.rubrique-onglets [role="tab"]' ) );
			function choisir( onglet ) {
				onglets.forEach( function ( autre ) {
					var actif = autre === onglet;
					autre.setAttribute( 'aria-selected', actif ? 'true' : 'false' );
					autre.tabIndex = actif ? 0 : -1;
					document.getElementById( autre.getAttribute( 'aria-controls' ) ).hidden = ! actif;
				} );
			}
			onglets.forEach( function ( onglet, i ) {
				onglet.addEventListener( 'click', function () {
					choisir( onglet );
				} );
				onglet.addEventListener( 'keydown', function ( e ) {
					var pas = 'ArrowRight' === e.key ? 1 : ( 'ArrowLeft' === e.key ? -1 : 0 );
					if ( pas ) {
						var suivant = onglets[ ( i + pas + onglets.length ) % onglets.length ];
						choisir( suivant );
						suivant.focus();
					}
				} );
			} );
		}() );
		</script>

	</main><!-- #primary -->

<?php
get_footer();
