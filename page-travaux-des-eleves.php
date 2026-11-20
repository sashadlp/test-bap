<?php
/**
 * Page « Les travaux des élèves » (identifiant / slug « travaux-des-eleves ») :
 * un travail à la une, puis les travaux par type (concours, arts, sciences…) avec des filtres.
 *
 * S'applique automatiquement (page-{slug}.php), et aussi quand la page est réglée comme
 * « page des articles » dans Réglages → Lecture (voir inc/travaux.php).
 * Types de travaux et catégories : les_renardiere_travaux_types() dans inc/travaux.php.
 *
 * @package Les_Renardiere
 */

get_header();

// Quand la page est la « page des articles », la boucle contient des articles : on lit la page elle-même.
$les_renardiere_page_id = get_queried_object_id();
$les_renardiere_donnees = les_renardiere_travaux_donnees();
$les_renardiere_une     = $les_renardiere_donnees['une'];
$les_renardiere_groupes = $les_renardiere_donnees['groupes'];
$les_renardiere_matiere = les_renardiere_page_url( 'actualite-matiere' );
$les_renardiere_contact = les_renardiere_page_url( 'contact' );
?>

	<main id="primary" class="site-main page-container page-travaux-eleves">

		<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
				<?php foreach ( array_reverse( get_post_ancestors( $les_renardiere_page_id ) ) as $les_renardiere_parent ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $les_renardiere_parent ) ); ?>"><?php echo esc_html( get_the_title( $les_renardiere_parent ) ); ?></a></li>
				<?php endforeach; ?>
				<li aria-current="page"><?php echo esc_html( get_the_title( $les_renardiere_page_id ) ); ?></li>
			</ol>
		</nav>

		<header class="travaux-tete">
			<h1 class="page-title"><?php echo esc_html( get_the_title( $les_renardiere_page_id ) ); ?></h1>
			<p><?php esc_html_e( 'Concours, créations, enquêtes, sorties : les réalisations des élèves du collège, de la 6e à la 3e.', 'les-renardiere' ); ?></p>
		</header>

		<?php if ( $les_renardiere_une ) : ?>
			<article class="travaux-une<?php echo $les_renardiere_une['image'] ? ' has-image' : ''; ?>">
				<?php if ( $les_renardiere_une['image'] ) : ?>
					<div class="travaux-une__image"><?php echo $les_renardiere_une['image']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML généré par get_the_post_thumbnail(). ?></div>
				<?php endif; ?>
				<div class="travaux-une__corps">
					<p class="travaux-une__badge"><?php esc_html_e( 'À la une', 'les-renardiere' ); ?></p>
					<time datetime="<?php echo esc_attr( $les_renardiere_une['date_iso'] ); ?>"><?php echo esc_html( $les_renardiere_une['date'] ); ?></time>
					<h2><a href="<?php echo esc_url( $les_renardiere_une['lien'] ); ?>"><?php echo esc_html( $les_renardiere_une['titre'] ); ?></a></h2>
					<?php if ( $les_renardiere_une['long'] ) : ?>
						<p><?php echo esc_html( $les_renardiere_une['long'] ); ?></p>
					<?php endif; ?>
					<span class="bouton bouton--plein" aria-hidden="true"><?php esc_html_e( 'Découvrir ce travail', 'les-renardiere' ); ?></span>
				</div>
			</article>
		<?php endif; ?>

		<?php if ( $les_renardiere_groupes ) : ?>

			<section class="travaux-liste" aria-labelledby="travaux-liste-titre">
				<div class="travaux-liste__entete">
					<h2 id="travaux-liste-titre"><?php esc_html_e( 'Explorer les travaux', 'les-renardiere' ); ?></h2>
					<?php if ( $les_renardiere_matiere ) : ?>
						<a class="travaux-liste__matiere" href="<?php echo esc_url( $les_renardiere_matiere ); ?>"><?php esc_html_e( 'Voir par matière', 'les-renardiere' ); ?> <span aria-hidden="true">→</span></a>
					<?php endif; ?>
				</div>

				<div class="travaux-filtres" role="tablist" aria-label="<?php esc_attr_e( 'Type de travaux', 'les-renardiere' ); ?>">
					<?php
					$les_renardiere_premier = true;
					foreach ( $les_renardiere_groupes as $les_renardiere_cle => $les_renardiere_groupe ) :
						?>
						<button type="button" role="tab" class="matiere-filtre" id="onglet-<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-controls="travaux-<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-selected="<?php echo $les_renardiere_premier ? 'true' : 'false'; ?>" tabindex="<?php echo $les_renardiere_premier ? '0' : '-1'; ?>">
							<?php echo esc_html( $les_renardiere_groupe['nom'] ); ?>
							<span class="matiere-filtre__nombre"><?php echo esc_html( number_format_i18n( $les_renardiere_groupe['total'] ) ); ?></span>
						</button>
						<?php
						$les_renardiere_premier = false;
					endforeach;
					?>
				</div>

				<?php
				$les_renardiere_premier = true;
				foreach ( $les_renardiere_groupes as $les_renardiere_cle => $les_renardiere_groupe ) :
					?>
					<div class="travaux-groupe" role="tabpanel" id="travaux-<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-labelledby="onglet-<?php echo esc_attr( $les_renardiere_cle ); ?>"<?php echo $les_renardiere_premier ? '' : ' hidden'; ?>>
						<ul class="matiere-grille">
							<?php foreach ( $les_renardiere_groupe['articles'] as $les_renardiere_article ) : ?>
								<?php get_template_part( 'template-parts/carte-article', null, array( 'article' => $les_renardiere_article ) ); ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php
					$les_renardiere_premier = false;
				endforeach;
				?>
			</section>

			<script>
			( function () {
				var onglets = Array.prototype.slice.call( document.querySelectorAll( '.travaux-filtres [role="tab"]' ) );
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

		<?php else : ?>
			<p><?php esc_html_e( 'Aucun travail publié pour le moment.', 'les-renardiere' ); ?></p>
		<?php endif; ?>

		<aside class="travaux-cta">
			<div>
				<p class="travaux-cta__titre"><?php esc_html_e( 'Un projet à mettre en avant ?', 'les-renardiere' ); ?></p>
				<p><?php esc_html_e( 'Professeurs et élèves : proposez vos réalisations pour qu’elles soient publiées sur le site.', 'les-renardiere' ); ?></p>
			</div>
			<?php if ( $les_renardiere_contact ) : ?>
				<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
			<?php endif; ?>
		</aside>

	</main><!-- #primary -->

<?php
get_footer();
