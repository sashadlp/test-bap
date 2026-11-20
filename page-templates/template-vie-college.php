<?php
/**
 * Template Name: Vie au collège
 *
 * Pages des clubs, ateliers et instances (Atelier Journalisme, éco-délégués, CVC…).
 * S'applique tout seul aux pages listées dans inc/vie-college.php, où se trouvent aussi les textes.
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_vie = les_renardiere_vie_college_donnees( get_queried_object() );

// Catégorie « Vie au collège » pour le fil d'Ariane.
$les_renardiere_vie_cat  = get_category_by_slug( 'vie-collegienne' );
$les_renardiere_vie_lien = $les_renardiere_vie_cat ? get_category_link( $les_renardiere_vie_cat ) : '';

// Derniers articles de la catégorie de la page (sous-catégories comprises).
$les_renardiere_cat         = get_category_by_slug( $les_renardiere_vie['categorie'] );
$les_renardiere_actualites  = $les_renardiere_cat
	? get_posts(
		array(
			'cat'                 => $les_renardiere_cat->term_id,
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	)
	: array();
$les_renardiere_lien_actus  = $les_renardiere_cat ? get_category_link( $les_renardiere_cat ) : '';

// Sommaire « Sur cette page » : une entrée par section, plus les actualités.
$les_renardiere_sommaire = array();
foreach ( $les_renardiere_vie['sections'] as $les_renardiere_index => $les_renardiere_section ) {
	$les_renardiere_vie['sections'][ $les_renardiere_index ]['id'] = sanitize_title( $les_renardiere_section['titre'] );
	$les_renardiere_sommaire[ $les_renardiere_vie['sections'][ $les_renardiere_index ]['id'] ] = $les_renardiere_section['titre'];
}
if ( $les_renardiere_actualites ) {
	$les_renardiere_sommaire['actualites'] = __( 'Actualités', 'les-renardiere' );
}

// Lien du bouton « Contacter le collège ».
$les_renardiere_page_contact = get_page_by_path( 'contact' );
$les_renardiere_url_contact  = $les_renardiere_page_contact ? get_permalink( $les_renardiere_page_contact ) : home_url( '/contact/' );
?>

	<main id="primary" class="site-main page-container page-vie<?php echo $les_renardiere_vie['variante'] ? ' page-vie--' . esc_attr( $les_renardiere_vie['variante'] ) : ''; ?>">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<li>
						<?php if ( $les_renardiere_vie_lien ) : ?>
							<a href="<?php echo esc_url( $les_renardiere_vie_lien ); ?>"><?php esc_html_e( 'Vie au collège', 'les-renardiere' ); ?></a>
						<?php else : ?>
							<?php esc_html_e( 'Vie au collège', 'les-renardiere' ); ?>
						<?php endif; ?>
					</li>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>

			<div class="vie-tete">

			<header class="vie-hero">
				<p class="vie-hero__surtitre"><?php esc_html_e( 'Vie au collège', 'les-renardiere' ); ?></p>
				<?php the_title( '<h1 class="vie-hero__titre">', '</h1>' ); ?>
				<?php if ( $les_renardiere_vie['intro'] ) : ?>
					<p class="vie-hero__intro"><?php echo esc_html( $les_renardiere_vie['intro'] ); ?></p>
				<?php endif; ?>
				<?php if ( $les_renardiere_actualites ) : ?>
					<a class="vie-hero__bouton" href="#actualites"><?php esc_html_e( 'Voir les actualités', 'les-renardiere' ); ?></a>
				<?php endif; ?>
			</header>

			<?php if ( $les_renardiere_vie['reperes'] ) : ?>
				<ul class="vie-reperes">
					<?php foreach ( $les_renardiere_vie['reperes'] as $les_renardiere_num => $les_renardiere_repere ) : ?>
						<li class="vie-repere">
							<span class="vie-repere__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $les_renardiere_num + 1 ) ); ?></span>
							<strong><?php echo esc_html( $les_renardiere_repere[0] ); ?></strong>
							<span><?php echo esc_html( $les_renardiere_repere[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			</div><!-- .vie-tete -->

			<div class="vie-layout">

				<div class="vie-contenu">

					<?php if ( get_the_content() ) : ?>
						<div class="entry-content vie-editeur">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>

					<?php foreach ( $les_renardiere_vie['sections'] as $les_renardiere_section ) : ?>
						<section id="<?php echo esc_attr( $les_renardiere_section['id'] ); ?>" class="vie-section" aria-labelledby="<?php echo esc_attr( $les_renardiere_section['id'] ); ?>-titre">
							<h2 id="<?php echo esc_attr( $les_renardiere_section['id'] ); ?>-titre"><?php echo esc_html( $les_renardiere_section['titre'] ); ?></h2>

							<?php if ( ! empty( $les_renardiere_section['liste'] ) ) : ?>
								<ul class="vie-liste">
									<?php foreach ( $les_renardiere_section['liste'] as $les_renardiere_element ) : ?>
										<li>
											<svg class="vie-liste__icone" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12" /><path d="M7 12.5l3.2 3.2L17 8.8" /></svg>
											<span><?php echo esc_html( $les_renardiere_element ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $les_renardiere_section['texte'] ) ) : ?>
								<p><?php echo esc_html( $les_renardiere_section['texte'] ); ?></p>
							<?php endif; ?>
						</section>
					<?php endforeach; ?>

				</div>

				<aside class="vie-cote">
					<?php if ( $les_renardiere_sommaire ) : ?>
						<nav class="vie-encadre vie-sommaire" aria-labelledby="vie-sommaire-titre">
							<p id="vie-sommaire-titre" class="vie-encadre__titre"><?php esc_html_e( 'Sur cette page', 'les-renardiere' ); ?></p>
							<ul>
								<?php foreach ( $les_renardiere_sommaire as $les_renardiere_ancre => $les_renardiere_libelle ) : ?>
									<li><a href="#<?php echo esc_attr( $les_renardiere_ancre ); ?>"><?php echo esc_html( $les_renardiere_libelle ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endif; ?>

					<div class="vie-encadre vie-question">
						<p class="vie-encadre__titre"><?php esc_html_e( 'Une question ?', 'les-renardiere' ); ?></p>
						<p><?php esc_html_e( 'La vie scolaire vous répond par téléphone ou par message.', 'les-renardiere' ); ?></p>
						<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_url_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
					</div>
				</aside>

			</div>

			<?php if ( $les_renardiere_actualites ) : ?>
				<section id="actualites" class="vie-actualites" aria-labelledby="actualites-titre">
					<div class="vie-actualites__entete">
						<h2 id="actualites-titre"><?php esc_html_e( 'Actualités', 'les-renardiere' ); ?></h2>
						<?php if ( $les_renardiere_lien_actus ) : ?>
							<a class="vie-actualites__tout" href="<?php echo esc_url( $les_renardiere_lien_actus ); ?>"><?php esc_html_e( 'Toutes les actualités', 'les-renardiere' ); ?> <span aria-hidden="true">→</span></a>
						<?php endif; ?>
					</div>

					<ul class="vie-grille">
						<?php foreach ( $les_renardiere_actualites as $les_renardiere_article ) : ?>
							<li class="vie-carte">
								<div class="vie-carte__corps">
									<time class="vie-carte__date" datetime="<?php echo esc_attr( get_the_date( 'c', $les_renardiere_article ) ); ?>"><?php echo esc_html( ucfirst( get_the_date( 'j M Y', $les_renardiere_article ) ) ); ?></time>
									<h3 class="vie-carte__titre"><a href="<?php echo esc_url( get_permalink( $les_renardiere_article ) ); ?>"><?php echo esc_html( get_the_title( $les_renardiere_article ) ); ?></a></h3>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

		<?php endwhile; ?>

	</main><!-- #primary -->

<?php
get_footer();
