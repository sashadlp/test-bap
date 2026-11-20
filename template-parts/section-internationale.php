<?php
/**
 * Mise en page commune des sections internationales : image pleine largeur sous le menu,
 * fil d'Ariane, titre, puis texte de présentation.
 * Utilisée par page-section-international-arabe.php et page-si-anglais.php.
 *
 * Arguments ($args) :
 * - titre  : titre affiché ;
 * - texte  : texte de présentation (utilisé si la page n'a pas de contenu dans l'éditeur) ;
 * - dessin : bandeau de assets/img/ affiché quand la page n'a pas d'image mise en avant.
 *
 * Styles : assets/sass/_si.scss.
 *
 * @package Les_Renardiere
 */

$les_renardiere_image   = get_the_post_thumbnail(
	get_queried_object_id(),
	'full',
	array(
		'alt'           => '',
		'fetchpriority' => 'high',
		'loading'       => 'eager',
		'sizes'         => '100vw',
	)
);
$les_renardiere_section = les_renardiere_page_url( 'section-internationale' );
?>

	<div class="si-banniere">
		<?php if ( $les_renardiere_image ) : ?>
			<?php echo $les_renardiere_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML généré par get_the_post_thumbnail(). ?>
		<?php else : ?>
			<img class="si-banniere__dessin" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $args['dessin'] ); ?>" alt="" width="1600" height="480" fetchpriority="high">
		<?php endif; ?>
	</div>

	<main id="primary" class="site-main page-container page-si">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<?php if ( $les_renardiere_section ) : ?>
						<li><a href="<?php echo esc_url( $les_renardiere_section ); ?>"><?php esc_html_e( 'Section internationale', 'les-renardiere' ); ?></a></li>
					<?php endif; ?>
					<li aria-current="page"><?php echo esc_html( $args['titre'] ); ?></li>
				</ol>
			</nav>

			<h1 class="si-titre"><?php echo esc_html( $args['titre'] ); ?></h1>

			<div class="si-texte">
				<?php if ( get_the_content() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<p><?php echo esc_html( $args['texte'] ); ?></p>
				<?php endif; ?>
			</div>

		<?php endwhile; ?>

	</main><!-- #primary -->
