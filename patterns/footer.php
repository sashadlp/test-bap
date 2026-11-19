<?php
/**
 * Title: Pied de page
 * Slug: les-renardiere/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Contenu du pied de page (menu, mentions légales, copyright), chargé par parts/footer.html.
 *
 * Menu : bloc Navigation sans menu choisi, donc WordPress affiche le même menu que l'en-tête
 * (le dernier menu de navigation publié). Modifier le menu de l'en-tête met aussi le pied de page à jour.
 * Les sous-menus y sont toujours déployés, en colonnes (voir assets/sass/_footer.scss).
 *
 * Mentions légales : liens construits avec home_url() (marchent aussi dans un sous-dossier) ;
 * les pages mentions-legales, accessibilite et plan-du-site doivent exister.
 *
 * @package Les_Renardiere
 */

?>
<!-- wp:group {"tagName":"footer","className":"site-footer","layout":{"type":"constrained"}} -->
<footer class="wp-block-group site-footer">

	<!-- wp:navigation {"className":"footer-nav","overlayMenu":"never","showSubmenuIcon":false,"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} /-->

	<!-- wp:paragraph {"align":"center","className":"footer-legal"} -->
	<p class="has-text-align-center footer-legal"><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Mentions légales', 'les-renardiere' ); ?></a> · <a href="<?php echo esc_url( home_url( '/accessibilite/' ) ); ?>"><?php esc_html_e( 'Accessibilité', 'les-renardiere' ); ?></a> · <a href="<?php echo esc_url( home_url( '/plan-du-site/' ) ); ?>"><?php esc_html_e( 'Plan du site', 'les-renardiere' ); ?></a></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"align":"center","className":"footer-bottom"} -->
	<p class="has-text-align-center footer-bottom"><?php /* translators: %s: année en cours. */ printf( esc_html__( '© %s Collège Les Renardières — Tous droits réservés', 'les-renardiere' ), esc_html( wp_date( 'Y' ) ) ); ?></p>
	<!-- /wp:paragraph -->

</footer>
<!-- /wp:group -->
