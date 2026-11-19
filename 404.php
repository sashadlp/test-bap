<?php
/**
 * Page 404 (repris de templates/404.html de l'ancien thème à blocs).
 *
 * @package Les_Renardiere
 */

get_header();
?>

	<main id="primary" class="site-main page-container">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( '404 - Page non trouvée', 'les-renardiere' ); ?></h1>
			</header>

			<div class="page-content">
				<p><?php esc_html_e( 'La page que vous recherchez n’existe pas.', 'les-renardiere' ); ?></p>
				<?php get_search_form(); ?>
				<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l’accueil', 'les-renardiere' ); ?></a></p>
			</div>
		</section>

	</main><!-- #primary -->

<?php
get_footer();
