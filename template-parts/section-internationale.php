<?php
/**
 * Mise en page commune des sections internationales : image pleine largeur sous le menu,
 * fil d'Ariane, titre et présentation, repères, programme, actualités de la section,
 * contact, puis lien vers l'autre section.
 * Utilisée par page-section-international-arabe.php et page-si-anglais.php.
 *
 * Arguments ($args) :
 * - titre   : titre affiché ;
 * - texte   : texte de présentation (utilisé si la page n'a pas de contenu dans l'éditeur) ;
 * - dessin  : bandeau de assets/img/ affiché quand la page n'a pas d'image mise en avant ;
 * - couleur : couleur d'accent de la section (« arabe » : doré, « americaine » : rouge) ;
 * - reperes : infos clés [ valeur, libellé ] ;
 * - blocs   : programme [ titre, texte, liste ] ;
 * - actus   : identifiants (slugs) des catégories d'articles de la section ;
 * - autre   : l'autre section [ titre, resume, page (slug), dessin ].
 *
 * Styles : assets/sass/_si.scss (cartes d'actualités : _matieres.scss).
 *
 * @package Les_Renardiere
 */

$les_renardiere_args = wp_parse_args(
	$args,
	array(
		'couleur' => 'arabe',
		'reperes' => array(),
		'blocs'   => array(),
		'actus'   => array(),
		'autre'   => array(),
	)
);

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
$les_renardiere_contact = les_renardiere_page_url( 'contact' );
$les_renardiere_slug    = get_post_field( 'post_name', get_queried_object_id() );

// Actualités de la section (en cache : inc/rubriques.php).
$les_renardiere_actus = $les_renardiere_args['actus']
	? les_renardiere_rubrique_actus( 'si-' . $les_renardiere_slug, array( 'Actualités' => $les_renardiere_args['actus'] ) )
	: array();
$les_renardiere_actus = $les_renardiere_actus ? reset( $les_renardiere_actus ) : null;

// Lien vers l'autre section, si sa page est publiée.
$les_renardiere_autre     = $les_renardiere_args['autre'];
$les_renardiere_autre_url = ! empty( $les_renardiere_autre['page'] ) ? les_renardiere_page_url( $les_renardiere_autre['page'] ) : '';
?>

	<div class="si-banniere">
		<?php if ( $les_renardiere_image ) : ?>
			<?php echo $les_renardiere_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML généré par get_the_post_thumbnail(). ?>
		<?php else : ?>
			<img class="si-banniere__dessin" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $les_renardiere_args['dessin'] ); ?>" alt="" width="1600" height="480" fetchpriority="high">
		<?php endif; ?>
	</div>

	<main id="primary" class="site-main page-container page-si page-si--<?php echo esc_attr( $les_renardiere_args['couleur'] ); ?>">

		<?php
		while ( have_posts() ) :
			the_post();
			$les_renardiere_contenu = get_the_content();
			// Texte de remplissage (« Lorem ipsum ») laissé dans l'éditeur : on ne l'affiche pas.
			$les_renardiere_contenu = $les_renardiere_contenu && false === stripos( $les_renardiere_contenu, 'lorem ipsum' );
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<?php if ( $les_renardiere_section ) : ?>
						<li><a href="<?php echo esc_url( $les_renardiere_section ); ?>"><?php esc_html_e( 'Section internationale', 'les-renardiere' ); ?></a></li>
					<?php endif; ?>
					<li aria-current="page"><?php echo esc_html( $les_renardiere_args['titre'] ); ?></li>
				</ol>
			</nav>

			<p class="si-surtitre"><?php esc_html_e( 'Parcours bilingue et biculturel · De la 6e à la 3e', 'les-renardiere' ); ?></p>
			<h1 class="si-titre"><?php echo esc_html( $les_renardiere_args['titre'] ); ?></h1>

			<div class="si-texte">
				<?php if ( $les_renardiere_contenu ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<p><?php echo esc_html( $les_renardiere_args['texte'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $les_renardiere_args['reperes'] ) : ?>
				<ul class="si-reperes">
					<?php foreach ( $les_renardiere_args['reperes'] as $les_renardiere_repere ) : ?>
						<li>
							<strong><?php echo esc_html( $les_renardiere_repere[0] ); ?></strong>
							<span><?php echo esc_html( $les_renardiere_repere[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( $les_renardiere_args['blocs'] ) : ?>
				<section class="si-programme" aria-labelledby="si-programme-titre">
					<h2 id="si-programme-titre" class="si-intertitre"><?php esc_html_e( 'Au programme', 'les-renardiere' ); ?></h2>
					<div class="si-blocs">
						<?php foreach ( $les_renardiere_args['blocs'] as $les_renardiere_i => $les_renardiere_bloc ) : ?>
							<article class="si-bloc">
								<span class="si-bloc__numero" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $les_renardiere_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
								<h3><?php echo esc_html( $les_renardiere_bloc['titre'] ); ?></h3>
								<?php if ( ! empty( $les_renardiere_bloc['texte'] ) ) : ?>
									<p><?php echo esc_html( $les_renardiere_bloc['texte'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $les_renardiere_bloc['liste'] ) ) : ?>
									<ul class="si-liste">
										<?php foreach ( $les_renardiere_bloc['liste'] as $les_renardiere_item ) : ?>
											<li><?php echo esc_html( $les_renardiere_item ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( $les_renardiere_actus ) : ?>
				<section class="si-actus" aria-labelledby="si-actus-titre">
					<div class="section-matiere__entete">
						<h2 id="si-actus-titre"><?php esc_html_e( 'Actualités de la section', 'les-renardiere' ); ?></h2>
						<?php if ( $les_renardiere_actus['total'] > count( $les_renardiere_actus['articles'] ) ) : ?>
							<a class="section-matiere__tout" href="<?php echo esc_url( $les_renardiere_actus['lien'] ); ?>">
								<?php
								/* translators: %s: nombre d'articles. */
								echo esc_html( sprintf( __( 'Voir les %s articles', 'les-renardiere' ), number_format_i18n( $les_renardiere_actus['total'] ) ) );
								?>
								<span aria-hidden="true">→</span>
							</a>
						<?php endif; ?>
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
					<p><?php esc_html_e( 'Contactez le collège pour connaître les modalités d’admission dans la section.', 'les-renardiere' ); ?></p>
				</div>
				<?php if ( $les_renardiere_contact ) : ?>
					<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
				<?php endif; ?>
			</aside>

			<?php if ( $les_renardiere_autre_url ) : ?>
				<aside class="si-autre" aria-labelledby="si-autre-titre">
					<p class="si-autre__surtitre" id="si-autre-titre"><?php esc_html_e( 'Découvrir aussi', 'les-renardiere' ); ?></p>
					<div class="si-carte si-carte--horizontale">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $les_renardiere_autre['dessin'] ); ?>" alt="" width="1600" height="480" loading="lazy">
						<div class="si-carte__corps">
							<h2><a href="<?php echo esc_url( $les_renardiere_autre_url ); ?>"><?php echo esc_html( $les_renardiere_autre['titre'] ); ?></a></h2>
							<p><?php echo esc_html( $les_renardiere_autre['resume'] ); ?></p>
							<span class="bouton" aria-hidden="true"><?php esc_html_e( 'Découvrir la section', 'les-renardiere' ); ?> <span>→</span></span>
						</div>
					</div>
				</aside>
			<?php endif; ?>

		<?php endwhile; ?>

	</main><!-- #primary -->
