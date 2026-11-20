<?php
/**
 * Carte d'article compacte (pages matières et rubriques) : image, date, titre, extrait court.
 * Toute la carte est cliquable. Données : les_renardiere_article_resume() (inc/matieres.php).
 * Styles : .carte-matiere dans assets/sass/_matieres.scss.
 *
 * @package Les_Renardiere
 */

$les_renardiere_article = $args['article'];
?>
<li class="carte-matiere<?php echo $les_renardiere_article['image'] ? ' has-image' : ''; ?>">
	<?php if ( $les_renardiere_article['image'] ) : ?>
		<div class="carte-matiere__image"><?php echo $les_renardiere_article['image']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML généré par get_the_post_thumbnail(). ?></div>
	<?php endif; ?>
	<div class="carte-matiere__corps">
		<time class="carte-matiere__date" datetime="<?php echo esc_attr( $les_renardiere_article['date_iso'] ); ?>"><?php echo esc_html( $les_renardiere_article['date'] ); ?></time>
		<h3 class="carte-matiere__titre"><a href="<?php echo esc_url( $les_renardiere_article['lien'] ); ?>"><?php echo esc_html( $les_renardiere_article['titre'] ); ?></a></h3>
		<?php if ( $les_renardiere_article['extrait'] ) : ?>
			<p class="carte-matiere__extrait"><?php echo esc_html( $les_renardiere_article['extrait'] ); ?></p>
		<?php endif; ?>
	</div>
</li>
