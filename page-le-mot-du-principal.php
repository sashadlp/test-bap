<?php
/**
 * Page « Le mot de la principale » (identifiant / slug « le-mot-du-principal ») : édito mis en page
 * comme une lettre, avec citation, le collège en bref, valeurs et liens pour continuer.
 *
 * S'applique automatiquement à cette page (hiérarchie des modèles : page-{slug}.php).
 * Le texte de la lettre se modifie dans l'éditeur WordPress, comme avant : le modèle le remet
 * en forme (paragraphes reconstruits, blocs vides retirés, dernière ligne courte = signature).
 * Photo : l'image mise en avant de la page s'affiche à côté de la signature.
 * Citation, chiffres et valeurs ci-dessous sont repris de la lettre : les mettre à jour avec elle.
 *
 * @package Les_Renardiere
 */

/**
 * Remet en paragraphes un texte collé depuis un document (divs, <br> au milieu des phrases, &nbsp;).
 *
 * @param string $html Contenu de la page.
 * @return string[] Paragraphes (HTML limité aux liens et à l'emphase).
 */
function les_renardiere_mot_paragraphes( $html ) {
	$inline = array(
		'a'      => array( 'href' => array() ),
		'strong' => array(),
		'em'     => array(),
		'b'      => array(),
		'i'      => array(),
	);

	$html = str_replace( array( '&nbsp;', "\xC2\xA0" ), ' ', $html );
	// Texte brut de l'éditeur : ligne vide = fin de paragraphe, simple retour à la ligne = <br>.
	$html = preg_replace( '/\R/u', "\n", $html );
	$html = preg_replace( "/\n[ \t]*\n/", '</p>', $html );
	$html = str_replace( "\n", '<br>', $html );
	// Fin de bloc = nouveau paragraphe.
	$html = preg_replace( '#</(p|div|h[1-6]|li)>#i', "\n\n", $html );
	// <br> après une fin de phrase = nouveau paragraphe ; sinon simple espace (retour à la ligne collé).
	$html = preg_replace( '#([.!?:»])\s*<br\s*/?>#iu', "$1\n\n", $html );
	$html = preg_replace( '#<br\s*/?>#i', ' ', $html );
	$html = wp_kses( $html, $inline );

	$paragraphes = array();
	foreach ( preg_split( "/\n\s*\n/", $html ) as $bloc ) {
		$bloc = trim( preg_replace( '/\s+/u', ' ', $bloc ) );
		if ( '' !== $bloc ) {
			$paragraphes[] = $bloc;
		}
	}

	return $paragraphes;
}

get_header();

$les_renardiere_citation = 'Un lieu où chaque élève a sa place, où chacun peut apprendre, grandir, réussir et s’épanouir pleinement.';

$les_renardiere_bref = array(
	array( '≈ 440', 'élèves' ),
	array( '16', 'classes' ),
	array( '6e → 3e', 'à La Défense' ),
);

$les_renardiere_valeurs = array( 'Confiance', 'Effort', 'Respect', 'Coopération' );

// Offre de formation citée dans la lettre : libellé => slug de page (vide = pas de lien).
$les_renardiere_offre = array(
	'Sections internationales arabe et américaine' => 'section-internationale',
	'Classe bilangue allemand'                     => '',
	'LCE anglais et langues et cultures antiques'  => '',
	'CHAM, avec le conservatoire de Courbevoie'    => 'cham',
	'ULIS'                                         => 'classe-ulis',
);

// Pour continuer : libellé, description, slug de page.
$les_renardiere_suite = array(
	array( 'Visiter le collège', 'Les lieux, étape par étape', 'le-college/visite' ),
	array( 'Infos pratiques', 'Calendrier, fournitures, réunions', 'le-college/infos-pratiques' ),
	array( 'Contacter le collège', 'Vie scolaire et secrétariat', 'contact' ),
);
?>

	<main id="primary" class="site-main page-container page-mot">

		<?php
		while ( have_posts() ) :
			the_post();
			$les_renardiere_paragraphes = les_renardiere_mot_paragraphes( get_the_content() );

			// Dernier paragraphe court = signature (ex. « La principale et l'équipe pédagogique »).
			$les_renardiere_signature = '';
			if ( count( $les_renardiere_paragraphes ) > 1 && mb_strlen( wp_strip_all_tags( end( $les_renardiere_paragraphes ) ) ) < 90 ) {
				$les_renardiere_signature = array_pop( $les_renardiere_paragraphes );
			}
			$les_renardiere_vie_scolaire = les_renardiere_page_url( 'le-college' );
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<?php if ( $les_renardiere_vie_scolaire ) : ?>
						<li><a href="<?php echo esc_url( $les_renardiere_vie_scolaire ); ?>"><?php esc_html_e( 'Vie scolaire', 'les-renardiere' ); ?></a></li>
					<?php endif; ?>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>

			<header class="mot-bandeau">
				<p class="mot-bandeau__surtitre"><?php esc_html_e( 'Édito', 'les-renardiere' ); ?></p>
				<?php the_title( '<h1 class="mot-bandeau__titre">', '</h1>' ); ?>
				<blockquote class="mot-bandeau__citation">
					<p><?php echo esc_html( $les_renardiere_citation ); ?></p>
				</blockquote>
			</header>

			<div class="mot-layout">

				<article class="mot-lettre">
					<?php foreach ( $les_renardiere_paragraphes as $les_renardiere_paragraphe ) : ?>
						<p><?php echo wp_kses( $les_renardiere_paragraphe, array( 'a' => array( 'href' => array() ), 'strong' => array(), 'em' => array(), 'b' => array(), 'i' => array() ) ); ?></p>
					<?php endforeach; ?>

					<?php if ( $les_renardiere_signature ) : ?>
						<footer class="mot-signature">
							<?php $les_renardiere_photo = get_the_post_thumbnail( null, 'thumbnail', array( 'class' => 'mot-signature__photo', 'alt' => '' ) ); ?>
							<?php if ( $les_renardiere_photo ) : ?>
								<?php echo $les_renardiere_photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML généré par get_the_post_thumbnail(). ?>
							<?php else : ?>
								<span class="mot-signature__monogramme" aria-hidden="true">LR</span>
							<?php endif; ?>
							<p><?php echo wp_kses( $les_renardiere_signature, array( 'strong' => array(), 'em' => array() ) ); ?></p>
						</footer>
					<?php endif; ?>
				</article>

				<aside class="mot-aside">
					<div class="mot-carte">
						<p class="mot-carte__titre"><?php esc_html_e( 'Le collège en bref', 'les-renardiere' ); ?></p>
						<ul class="mot-bref">
							<?php foreach ( $les_renardiere_bref as $les_renardiere_item ) : ?>
								<li><strong><?php echo esc_html( $les_renardiere_item[0] ); ?></strong> <span><?php echo esc_html( $les_renardiere_item[1] ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="mot-carte">
						<p class="mot-carte__titre"><?php esc_html_e( 'Nos valeurs', 'les-renardiere' ); ?></p>
						<ul class="mot-valeurs">
							<?php foreach ( $les_renardiere_valeurs as $les_renardiere_valeur ) : ?>
								<li><?php echo esc_html( $les_renardiere_valeur ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="mot-carte">
						<p class="mot-carte__titre"><?php esc_html_e( 'Une offre de formation élargie', 'les-renardiere' ); ?></p>
						<ul class="mot-offre">
							<?php foreach ( $les_renardiere_offre as $les_renardiere_libelle => $les_renardiere_slug ) : ?>
								<?php $les_renardiere_url = $les_renardiere_slug ? les_renardiere_page_url( $les_renardiere_slug ) : ''; ?>
								<li>
									<?php if ( $les_renardiere_url ) : ?>
										<a href="<?php echo esc_url( $les_renardiere_url ); ?>"><?php echo esc_html( $les_renardiere_libelle ); ?> <span aria-hidden="true">→</span></a>
									<?php else : ?>
										<?php echo esc_html( $les_renardiere_libelle ); ?>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</aside>

			</div>

			<section class="mot-suite" aria-labelledby="mot-suite-titre">
				<h2 id="mot-suite-titre"><?php esc_html_e( 'Pour continuer la visite', 'les-renardiere' ); ?></h2>
				<ul class="mot-suite__grille">
					<?php foreach ( $les_renardiere_suite as $les_renardiere_lien ) : ?>
						<?php $les_renardiere_url = les_renardiere_page_url( $les_renardiere_lien[2] ); ?>
						<?php if ( $les_renardiere_url ) : ?>
							<li>
								<a href="<?php echo esc_url( $les_renardiere_url ); ?>">
									<strong><?php echo esc_html( $les_renardiere_lien[0] ); ?></strong>
									<span><?php echo esc_html( $les_renardiere_lien[1] ); ?></span>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</section>

		<?php endwhile; ?>

	</main><!-- #primary -->

<?php
get_footer();
