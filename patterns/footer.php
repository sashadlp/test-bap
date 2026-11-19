<?php
/**
 * Title: Pied de page
 * Slug: les-renardiere/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Contenu du pied de page (liens et copyright), chargé par parts/footer.html.
 *
 * Les liens sont construits avec home_url() : ils restent bons si WordPress est installé
 * dans un sous-dossier. Les pages doivent exister avec ces slugs.
 *
 * @package Les_Renardiere
 */

?>
<!-- wp:group {"tagName":"footer","className":"site-footer","layout":{"type":"constrained"}} -->
<footer class="wp-block-group site-footer">

	<!-- wp:columns {"className":"footer-columns"} -->
	<div class="wp-block-columns footer-columns">

		<!-- wp:column {"className":"footer-col"} -->
		<div class="wp-block-column footer-col">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Mentions légales', 'les-renardiere' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:list -->
			<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Mentions légales', 'les-renardiere' ); ?></a> - <a href="<?php echo esc_url( home_url( '/accessibilite/' ) ); ?>"><?php esc_html_e( 'Accessibilité', 'les-renardiere' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/plan-du-site/' ) ); ?>"><?php esc_html_e( 'Plan du site', 'les-renardiere' ); ?></a></li>
<!-- /wp:list-item --></ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"footer-col"} -->
		<div class="wp-block-column footer-col">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Contact', 'les-renardiere' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:list -->
			<ul class="wp-block-list"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/secretariat/' ) ); ?>"><?php esc_html_e( 'Secrétariat', 'les-renardiere' ); ?></a> - <a href="<?php echo esc_url( home_url( '/vie-scolaire/' ) ); ?>"><?php esc_html_e( 'Vie scolaire', 'les-renardiere' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/horaires/' ) ); ?>"><?php esc_html_e( 'Horaires d’ouverture', 'les-renardiere' ); ?></a></li>
<!-- /wp:list-item --></ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- wp:paragraph {"align":"center","className":"footer-bottom"} -->
	<p class="has-text-align-center footer-bottom"><?php /* translators: %s: année en cours. */ printf( esc_html__( '© %s Collège Les Renardières — Tous droits réservés', 'les-renardiere' ), esc_html( wp_date( 'Y' ) ) ); ?></p>
	<!-- /wp:paragraph -->

</footer>
<!-- /wp:group -->
