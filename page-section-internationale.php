<?php
/**
 * Page « Section internationale » (identifiant / slug « section-internationale ») : page d'entrée
 * des deux sections, avec les bandeaux, une présentation, une carte par section et les actualités.
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Présentation : le contenu de la page dans l'éditeur s'il y en a un (le « Lorem ipsum » est ignoré),
 * sinon le texte ci-dessous. Pages des sections : page-section-international-arabe.php et page-si-anglais.php.
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_intro = 'Le Collège Les Renardières propose deux Sections Internationales, arabe et américaine : des parcours d’excellence bilingues et biculturels, de la 6e à la 3e, qui allient l’enseignement approfondi de la langue et de la littérature à l’histoire-géographie et préparent au Diplôme National du Brevet option internationale (DNBi).';

// Une carte par section : titre, résumé, slug de la page, bandeau (assets/img/).
$les_renardiere_sections = array(
	array(
		'titre'  => 'Section internationale arabe',
		'resume' => 'Une maîtrise solide de l’arabe littéraire et une ouverture sur la richesse des cultures du monde arabe, avec des effectifs réduits.',
		'page'   => 'section-international-arabe',
		'dessin' => 'si-arabe-banniere.svg',
	),
	array(
		'titre'  => 'Section internationale américaine',
		'resume' => 'L’anglais à l’écrit comme à l’oral, de Shakespeare à Chimamanda Ngozi Adichie, avec des débats et des projets comme le Yearbook.',
		'page'   => 'si-anglais',
		'dessin' => 'si-americaine-banniere.svg',
	),
);

$les_renardiere_actus   = les_renardiere_rubrique_actus( 'section-internationale', array( 'Actualités' => array( 'si-arabe', 'si-americaine' ) ) );
$les_renardiere_actus   = $les_renardiere_actus ? reset( $les_renardiere_actus ) : null;
$les_renardiere_contact = les_renardiere_page_url( 'contact' );
?>

	<div class="si-banniere si-banniere--double" aria-hidden="true">
		<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
			<img class="si-banniere__dessin" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $les_renardiere_section['dessin'] ); ?>" alt="" width="1600" height="480" fetchpriority="high">
		<?php endforeach; ?>
	</div>

	<main id="primary" class="site-main page-container page-si page-si-hub">

		<?php
		while ( have_posts() ) :
			the_post();
			$les_renardiere_contenu = get_the_content();
			$les_renardiere_contenu = $les_renardiere_contenu && false === stripos( $les_renardiere_contenu, 'lorem ipsum' );
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>

			<?php the_title( '<h1 class="si-titre">', '</h1>' ); ?>

			<div class="si-texte">
				<?php if ( $les_renardiere_contenu ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<p><?php echo esc_html( $les_renardiere_intro ); ?></p>
				<?php endif; ?>
			</div>

			<ul class="si-cartes">
				<?php foreach ( $les_renardiere_sections as $les_renardiere_section ) : ?>
					<?php $les_renardiere_url = les_renardiere_page_url( $les_renardiere_section['page'] ); ?>
					<?php if ( $les_renardiere_url ) : ?>
						<li class="si-carte">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $les_renardiere_section['dessin'] ); ?>" alt="" width="1600" height="480" loading="lazy">
							<div class="si-carte__corps">
								<h2><a href="<?php echo esc_url( $les_renardiere_url ); ?>"><?php echo esc_html( $les_renardiere_section['titre'] ); ?></a></h2>
								<p><?php echo esc_html( $les_renardiere_section['resume'] ); ?></p>
								<span class="bouton bouton--plein" aria-hidden="true"><?php esc_html_e( 'Découvrir la section', 'les-renardiere' ); ?> <span>→</span></span>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>

			<?php if ( $les_renardiere_actus ) : ?>
				<section class="si-actus" aria-labelledby="si-actus-titre">
					<div class="section-matiere__entete">
						<h2 id="si-actus-titre"><?php esc_html_e( 'Actualités des sections', 'les-renardiere' ); ?></h2>
					</div>
					<ul class="matiere-grille">
						<?php foreach ( $les_renardiere_actus['articles'] as $les_renardiere_article ) : ?>
							<?php get_template_part( 'template-parts/carte-article', null, array( 'article' => $les_renardiere_article ) ); ?>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<aside class="si-cta">
				<div>
					<p class="si-cta__titre"><?php esc_html_e( 'Votre enfant est intéressé ?', 'les-renardiere' ); ?></p>
					<p><?php esc_html_e( 'Contactez le collège pour connaître les modalités de candidature aux sections internationales.', 'les-renardiere' ); ?></p>
				</div>
				<?php if ( $les_renardiere_contact ) : ?>
					<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
				<?php endif; ?>
			</aside>

		<?php endwhile; ?>

	</main><!-- #primary -->

<?php
get_footer();
