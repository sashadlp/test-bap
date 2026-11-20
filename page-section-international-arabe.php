<?php
/**
 * Page « Section internationale arabe » (identifiant / slug « section-international-arabe ») :
 * image en haut sous le menu, puis le titre, puis le texte de présentation.
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Image : l'image mise en avant de la page si elle existe, sinon le bandeau
 * assets/img/si-arabe-banniere.svg (motif arabesque et « العربية »).
 * Texte : le contenu de la page dans l'éditeur WordPress s'il y en a un, sinon le texte ci-dessous.
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_titre = 'Section internationale arabe';
$les_renardiere_texte = 'La Section Internationale Arabe du Collège Les Renardières propose aux élèves un parcours d’excellence bilingue et biculturel, alliant l’enseignement approfondi de la langue et de la littérature arabes à l’histoire-géographie. Encadré par une équipe pédagogique dédiée et favorisé par des effectifs réduits, ce cursus permet de développer une maîtrise solide de l’arabe littéraire tout en s’ouvrant à la richesse des cultures du monde arabe, préparant ainsi les collégiens au Diplôme National du Brevet option internationale (DNBi).';

$les_renardiere_image = get_the_post_thumbnail(
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
			<img class="si-banniere__dessin" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/si-arabe-banniere.svg' ); ?>" alt="" width="1600" height="480" fetchpriority="high">
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
					<li aria-current="page"><?php echo esc_html( $les_renardiere_titre ); ?></li>
				</ol>
			</nav>

			<h1 class="si-titre"><?php echo esc_html( $les_renardiere_titre ); ?></h1>

			<div class="si-texte">
				<?php if ( get_the_content() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<p><?php echo esc_html( $les_renardiere_texte ); ?></p>
				<?php endif; ?>
			</div>

		<?php endwhile; ?>

	</main><!-- #primary -->

<?php
get_footer();
