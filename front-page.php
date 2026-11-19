<?php
/**
 * Page d'accueil (repris de templates/front-page.html de l'ancien thème à blocs).
 *
 * L'ancien template appelait le motif « iim-a2-cdi/hero », qui n'existait pas :
 * il est remplacé ici par un vrai bandeau, en utilisant les classes déjà prévues
 * dans assets/sass/_home.scss (.hero-college, .btn-rentree, .btn-contact-hero,
 * .qui-nous-sommes, grille d'actualités).
 *
 * Contenu : l'image à la une de la page d'accueil sert de fond au bandeau,
 * son extrait de sous-titre, et son contenu remplit « Qui nous sommes ».
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_news = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>

	<main id="primary" class="site-main front-page">

		<?php
		while ( have_posts() ) :
			the_post();
			$les_renardiere_bg = get_the_post_thumbnail_url( null, 'full' );
			?>

			<section class="hero-college<?php echo $les_renardiere_bg ? ' has-image' : ''; ?>"<?php echo $les_renardiere_bg ? ' style="background-image:url(' . esc_url( $les_renardiere_bg ) . ')"' : ''; ?>>
				<div class="hero-college__inner">
					<h1 class="hero-college__title"><?php esc_html_e( 'Bienvenue au collège Les Renardières', 'les-renardiere' ); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="hero-college__intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<?php
					// Chaque bouton n'apparaît que si sa page (slug « infos-pratiques », « contact ») est publiée.
					$les_renardiere_url_infos   = les_renardiere_page_url( 'infos-pratiques' );
					$les_renardiere_url_contact = les_renardiere_page_url( 'contact' );
					?>
					<?php if ( $les_renardiere_url_infos || $les_renardiere_url_contact ) : ?>
						<div class="hero-college__buttons">
							<?php if ( $les_renardiere_url_infos ) : ?>
								<a class="btn-rentree" href="<?php echo esc_url( $les_renardiere_url_infos ); ?>"><?php esc_html_e( 'Infos de rentrée', 'les-renardiere' ); ?></a>
							<?php endif; ?>
							<?php if ( $les_renardiere_url_contact ) : ?>
								<a class="btn-contact-hero" href="<?php echo esc_url( $les_renardiere_url_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>

			<section class="qui-nous-sommes">
				<h2><?php esc_html_e( 'Qui sommes nous', 'les-renardiere' ); ?></h2>
				<div class="entry-content">
					<?php
					if ( get_the_content() ) {
						the_content();
					} else {
						echo '<p>' . esc_html__( 'Écris la présentation du collège dans le contenu de la page d’accueil.', 'les-renardiere' ) . '</p>';
					}
					?>
				</div>
			</section>

		<?php endwhile; ?>

		<section class="actualites">
			<h2 class="actualites__titre"><?php esc_html_e( 'Actualités', 'les-renardiere' ); ?></h2>
			<?php if ( $les_renardiere_news->have_posts() ) : ?>
				<ul class="wp-block-post-template posts-grid">
					<?php
					while ( $les_renardiere_news->have_posts() ) :
						$les_renardiere_news->the_post();
						get_template_part( 'template-parts/card' );
					endwhile;
					wp_reset_postdata();
					?>
				</ul>
			<?php else : ?>
				<p><?php esc_html_e( 'Aucune actualité pour le moment.', 'les-renardiere' ); ?></p>
			<?php endif; ?>
		</section>

		<?php
		// Horaires, téléphone et carte : même fichier que la composition (patterns/carte-acces.php).
		get_template_part( 'patterns/carte-acces' );
		?>

	</main><!-- #primary -->

<?php
get_footer();
