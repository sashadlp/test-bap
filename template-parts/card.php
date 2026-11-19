<?php
/**
 * Carte d'un article ou d'un projet dans une grille :
 * image, puis date + catégories (article) ou technologies + vues (projet), titre, extrait.
 * Les styles viennent de .wp-block-post-template li dans assets/sass/_home.scss.
 *
 * @package Les_Renardiere
 */

?>
<li id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'medium_large', array( 'alt' => '' ) ); ?>
		</a>
	<?php endif; ?>

	<?php if ( 'post' === get_post_type() ) : ?>
		<time class="wp-block-post-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<?php if ( has_category() ) : ?>
			<div class="taxonomy-category"><?php the_category( ', ' ); ?></div>
		<?php endif; ?>
	<?php elseif ( 'projet' === get_post_type() ) : ?>
		<?php the_terms( get_the_ID(), 'technologie', '<div class="taxonomy-category">', ', ', '</div>' ); ?>
		<span class="projet-vues"><?php echo esc_html( les_renardiere_vues_texte() ); ?></span>
	<?php endif; ?>

	<h2 class="wp-block-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

	<div class="wp-block-post-excerpt"><?php the_excerpt(); ?></div>
</li>
