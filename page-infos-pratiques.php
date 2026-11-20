<?php
/**
 * Page « Infos pratiques » (Vie scolaire) : calendrier scolaire, fournitures par niveau, réunions de rentrée.
 *
 * S'applique automatiquement à la page dont l'identifiant (slug) est « infos-pratiques »
 * (hiérarchie des modèles WordPress : page-{slug}.php), sans rien choisir dans l'éditeur.
 *
 * Le contenu se met à jour dans les tableaux ci-dessous.
 * PDF des fournitures : téléverser dans Médias un fichier nommé fournitures-6e.pdf, fournitures-5e.pdf, etc.
 * Le bouton « Télécharger la liste (PDF) » apparaît dès que le fichier existe.
 * Un encadré visible seulement par les admins signale les PDF manquants.
 *
 * @package Les_Renardiere
 */

get_header();

// Calendrier scolaire 2026-2027, zone C (académie de Versailles). « fin » = jour de reprise (AAAA-MM-JJ).
$les_renardiere_calendrier = array(
	array(
		'periode' => 'Rentrée des élèves',
		'dates'   => 'Mardi 1er septembre 2026',
		'fin'     => '2026-09-01',
	),
	array(
		'periode' => 'Vacances de la Toussaint',
		'dates'   => 'Du samedi 17 octobre au lundi 2 novembre 2026',
		'fin'     => '2026-11-02',
	),
	array(
		'periode' => 'Vacances de Noël',
		'dates'   => 'Du samedi 19 décembre 2026 au lundi 4 janvier 2027',
		'fin'     => '2027-01-04',
	),
	array(
		'periode' => 'Vacances d’hiver',
		'dates'   => 'Du samedi 6 février au lundi 22 février 2027',
		'fin'     => '2027-02-22',
	),
	array(
		'periode' => 'Vacances de printemps',
		'dates'   => 'Du samedi 3 avril au lundi 19 avril 2027',
		'fin'     => '2027-04-19',
	),
	array(
		'periode' => 'Début des vacances d’été',
		'dates'   => 'Samedi 3 juillet 2027',
		'fin'     => '2027-07-03',
	),
);

// Fournitures communes à tous les niveaux. « obligatoire » => true affiche le badge « Obligatoire ».
// LISTE INDICATIVE : à remplacer par la liste officielle du collège.
$les_renardiere_fournitures_communes = array(
	array( 'Agenda (pas de cahier de textes)', true ),
	array( 'Trousse : stylos bleu, noir, rouge et vert, crayon à papier, gomme, taille-crayon, colle, ciseaux à bouts ronds', true ),
	array( 'Règle de 30 cm, équerre, rapporteur et compas', true ),
	array( 'Calculatrice collège (type fx-92)', true ),
	array( 'Copies simples et doubles, grand format, grands carreaux', true ),
	array( 'Crayons de couleur et surligneurs', false ),
	array( 'Écouteurs filaires (cours de langues)', false ),
	array( 'Tenue de sport : baskets, survêtement ou short, gourde', true ),
);

// Fournitures propres à chaque niveau (clé = niveau, utilisée aussi pour le nom du PDF).
$les_renardiere_fournitures_niveaux = array(
	'6e' => array(
		array( '6 cahiers 24 × 32 grands carreaux, 96 pages (français, maths, histoire-géo, SVT, anglais, technologie)', true ),
		array( '1 pochette de papier dessin blanc 24 × 32 (arts plastiques)', true ),
		array( '1 classeur souple A4 avec pochettes plastiques et intercalaires', true ),
		array( '1 cahier de brouillon', false ),
	),
	'5e' => array(
		array( '6 cahiers 24 × 32 grands carreaux, 96 pages', true ),
		array( '1 cahier 24 × 32 pour la deuxième langue vivante (LV2)', true ),
		array( '1 pochette de papier dessin blanc 24 × 32 (arts plastiques)', true ),
		array( '1 cahier de brouillon', false ),
	),
	'4e' => array(
		array( '6 cahiers 24 × 32 grands carreaux, 96 pages', true ),
		array( '1 classeur A4 avec pochettes plastiques (physique-chimie)', true ),
		array( '1 pochette de papier dessin blanc 24 × 32 (arts plastiques)', true ),
		array( '1 cahier de brouillon', false ),
	),
	'3e' => array(
		array( '6 cahiers 24 × 32 grands carreaux, 96 pages', true ),
		array( '1 classeur A4 avec pochettes plastiques (physique-chimie)', true ),
		array( '1 porte-vues pour les fiches de révision du brevet', false ),
		array( '1 cahier de brouillon', false ),
	),
);

// Réunions de rentrée parents / professeurs. Laisser une valeur vide affiche « à venir ».
$les_renardiere_reunions = array(
	'6e' => array(
		'date'  => '',
		'heure' => '',
		'lieu'  => '',
	),
	'5e' => array(
		'date'  => '',
		'heure' => '',
		'lieu'  => '',
	),
	'4e' => array(
		'date'  => '',
		'heure' => '',
		'lieu'  => '',
	),
	'3e' => array(
		'date'  => '',
		'heure' => '',
		'lieu'  => '',
	),
);

// PDF des fournitures : fichier fournitures-6e.pdf (etc.) dans la médiathèque.
$les_renardiere_pdfs           = array();
$les_renardiere_pdfs_manquants = array();
foreach ( array_keys( $les_renardiere_fournitures_niveaux ) as $les_renardiere_niveau ) {
	$les_renardiere_fichier = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'application/pdf',
			'name'           => 'fournitures-' . $les_renardiere_niveau,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( $les_renardiere_fichier ) {
		$les_renardiere_pdfs[ $les_renardiere_niveau ] = wp_get_attachment_url( $les_renardiere_fichier[0] );
	} else {
		$les_renardiere_pdfs_manquants[] = 'fournitures-' . $les_renardiere_niveau . '.pdf';
	}
}

// Lien du bouton « Contacter le collège ».
$les_renardiere_page_contact = get_page_by_path( 'contact' );
$les_renardiere_url_contact  = $les_renardiere_page_contact ? get_permalink( $les_renardiere_page_contact ) : home_url( '/contact/' );

$les_renardiere_aujourdhui = current_time( 'Y-m-d' );
?>

	<main id="primary" class="site-main page-container page-infos">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<nav class="fil-ariane" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'les-renardiere' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'les-renardiere' ); ?></a></li>
					<?php foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $les_renardiere_parent ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $les_renardiere_parent ) ); ?>"><?php echo esc_html( get_the_title( $les_renardiere_parent ) ); ?></a></li>
					<?php endforeach; ?>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<?php if ( current_user_can( 'upload_files' ) && $les_renardiere_pdfs_manquants ) : ?>
				<div class="infos-diagnostic" role="note">
					<p>
						<strong><?php esc_html_e( 'Visible seulement par les admins :', 'les-renardiere' ); ?></strong>
						<?php
						printf(
							/* translators: %s: noms des fichiers PDF manquants. */
							esc_html__( 'le bouton « Télécharger la liste (PDF) » est caché tant que ces fichiers ne sont pas dans Médias : %s.', 'les-renardiere' ),
							esc_html( implode( ', ', $les_renardiere_pdfs_manquants ) )
						);
						?>
					</p>
				</div>
			<?php endif; ?>

			<div class="infos-layout">

				<nav class="infos-sousnav" aria-label="<?php esc_attr_e( 'Sur cette page', 'les-renardiere' ); ?>">
					<ul>
						<li><a href="#calendrier"><?php esc_html_e( 'Calendrier scolaire', 'les-renardiere' ); ?></a></li>
						<li><a href="#fournitures"><?php esc_html_e( 'Fournitures par niveau', 'les-renardiere' ); ?></a></li>
						<li><a href="#reunions"><?php esc_html_e( 'Réunions parents', 'les-renardiere' ); ?></a></li>
					</ul>
				</nav>

				<div class="infos-contenu">

					<?php if ( get_the_content() ) : ?>
						<div class="entry-content infos-intro">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>

					<section id="calendrier" class="infos-section" aria-labelledby="calendrier-titre">
						<h2 id="calendrier-titre"><?php esc_html_e( 'Calendrier scolaire 2026-2027', 'les-renardiere' ); ?></h2>
						<p class="infos-section__intro"><?php esc_html_e( 'Zone C (académie de Versailles). Les vacances commencent après la classe le samedi et la reprise se fait le matin du jour indiqué.', 'les-renardiere' ); ?></p>

						<ul class="infos-liste">
							<?php foreach ( $les_renardiere_calendrier as $les_renardiere_ligne ) : ?>
								<?php $les_renardiere_passe = $les_renardiere_ligne['fin'] < $les_renardiere_aujourdhui; ?>
								<li class="infos-liste__ligne<?php echo $les_renardiere_passe ? ' is-passe' : ''; ?>">
									<strong><?php echo esc_html( $les_renardiere_ligne['periode'] ); ?></strong>
									<span><?php echo esc_html( $les_renardiere_ligne['dates'] ); ?></span>
									<?php if ( $les_renardiere_passe ) : ?>
										<span class="infos-badge infos-badge--passe"><?php esc_html_e( 'Passé', 'les-renardiere' ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>

						<a class="bouton" href="https://www.education.gouv.fr/calendrier-scolaire-100148" target="_blank" rel="noopener">
							<?php esc_html_e( 'Voir le calendrier officiel', 'les-renardiere' ); ?>
							<span class="screen-reader-text"><?php esc_html_e( '(nouvel onglet)', 'les-renardiere' ); ?></span>
						</a>
					</section>

					<section id="fournitures" class="infos-section" aria-labelledby="fournitures-titre">
						<h2 id="fournitures-titre"><?php esc_html_e( 'Fournitures par niveau', 'les-renardiere' ); ?></h2>

						<div class="infos-onglets" role="tablist" aria-label="<?php esc_attr_e( 'Niveau', 'les-renardiere' ); ?>">
							<?php
							$les_renardiere_premier = true;
							foreach ( array_keys( $les_renardiere_fournitures_niveaux ) as $les_renardiere_niveau ) :
								?>
								<button type="button" role="tab" class="infos-onglet" id="onglet-<?php echo esc_attr( $les_renardiere_niveau ); ?>" aria-controls="panneau-<?php echo esc_attr( $les_renardiere_niveau ); ?>" aria-selected="<?php echo $les_renardiere_premier ? 'true' : 'false'; ?>" tabindex="<?php echo $les_renardiere_premier ? '0' : '-1'; ?>"><?php echo esc_html( $les_renardiere_niveau ); ?></button>
								<?php
								$les_renardiere_premier = false;
							endforeach;
							?>
						</div>

						<?php
						$les_renardiere_premier = true;
						foreach ( $les_renardiere_fournitures_niveaux as $les_renardiere_niveau => $les_renardiere_articles ) :
							?>
							<div class="infos-panneau" role="tabpanel" id="panneau-<?php echo esc_attr( $les_renardiere_niveau ); ?>" aria-labelledby="onglet-<?php echo esc_attr( $les_renardiere_niveau ); ?>" tabindex="0"<?php echo $les_renardiere_premier ? '' : ' hidden'; ?>>
								<div class="infos-panneau__colonnes">
									<div>
										<h3><?php esc_html_e( 'Fournitures communes', 'les-renardiere' ); ?></h3>
										<ul class="infos-fournitures">
											<?php foreach ( $les_renardiere_fournitures_communes as $les_renardiere_article ) : ?>
												<li>
													<?php echo esc_html( $les_renardiere_article[0] ); ?>
													<?php if ( $les_renardiere_article[1] ) : ?>
														<span class="infos-badge"><?php esc_html_e( 'Obligatoire', 'les-renardiere' ); ?></span>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
									<div>
										<h3>
											<?php
											/* translators: %s: niveau (6e, 5e…). */
											printf( esc_html__( 'Spécifique à la %s', 'les-renardiere' ), esc_html( $les_renardiere_niveau ) );
											?>
										</h3>
										<ul class="infos-fournitures">
											<?php foreach ( $les_renardiere_articles as $les_renardiere_article ) : ?>
												<li>
													<?php echo esc_html( $les_renardiere_article[0] ); ?>
													<?php if ( $les_renardiere_article[1] ) : ?>
														<span class="infos-badge"><?php esc_html_e( 'Obligatoire', 'les-renardiere' ); ?></span>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>

								<?php if ( isset( $les_renardiere_pdfs[ $les_renardiere_niveau ] ) ) : ?>
									<a class="bouton" href="<?php echo esc_url( $les_renardiere_pdfs[ $les_renardiere_niveau ] ); ?>" download>
										<?php esc_html_e( 'Télécharger la liste (PDF)', 'les-renardiere' ); ?>
										<span class="screen-reader-text">
											<?php
											/* translators: %s: niveau (6e, 5e…). */
											printf( esc_html__( 'des fournitures de %s', 'les-renardiere' ), esc_html( $les_renardiere_niveau ) );
											?>
										</span>
									</a>
								<?php endif; ?>
							</div>
							<?php
							$les_renardiere_premier = false;
						endforeach;
						?>
					</section>

					<section id="reunions" class="infos-section" aria-labelledby="reunions-titre">
						<h2 id="reunions-titre"><?php esc_html_e( 'Réunions de rentrée', 'les-renardiere' ); ?></h2>
						<p class="infos-section__intro"><?php esc_html_e( 'Une réunion par niveau pour rencontrer l’équipe pédagogique. Les convocations sont aussi envoyées par l’ENT.', 'les-renardiere' ); ?></p>

						<ul class="infos-liste">
							<?php foreach ( $les_renardiere_reunions as $les_renardiere_niveau => $les_renardiere_reunion ) : ?>
								<li class="infos-liste__ligne">
									<strong><?php echo esc_html( $les_renardiere_niveau ); ?></strong>
									<?php if ( $les_renardiere_reunion['date'] ) : ?>
										<span><?php echo esc_html( implode( ' · ', array_filter( array( $les_renardiere_reunion['date'], $les_renardiere_reunion['heure'], $les_renardiere_reunion['lieu'] ) ) ) ); ?></span>
									<?php else : ?>
										<span class="infos-liste__vide"><?php esc_html_e( 'Date à venir', 'les-renardiere' ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</section>

					<aside class="infos-contact">
						<div>
							<p class="infos-contact__titre"><?php esc_html_e( 'Vous ne trouvez pas l’information ?', 'les-renardiere' ); ?></p>
							<p><?php esc_html_e( 'Écrivez au secrétariat ou à la vie scolaire.', 'les-renardiere' ); ?></p>
						</div>
						<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_url_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
					</aside>

				</div>
			</div>

		<?php endwhile; ?>

		<script>
		( function () {
			// Onglets des niveaux (clic + flèches gauche/droite du clavier).
			var onglets = Array.prototype.slice.call( document.querySelectorAll( '.infos-onglet' ) );
			function choisir( onglet ) {
				onglets.forEach( function ( autre ) {
					var actif = autre === onglet;
					autre.setAttribute( 'aria-selected', actif ? 'true' : 'false' );
					autre.tabIndex = actif ? 0 : -1;
					document.getElementById( autre.getAttribute( 'aria-controls' ) ).hidden = ! actif;
				} );
			}
			onglets.forEach( function ( onglet, i ) {
				onglet.addEventListener( 'click', function () {
					choisir( onglet );
				} );
				onglet.addEventListener( 'keydown', function ( e ) {
					var pas = 'ArrowRight' === e.key ? 1 : ( 'ArrowLeft' === e.key ? -1 : 0 );
					if ( pas ) {
						var suivant = onglets[ ( i + pas + onglets.length ) % onglets.length ];
						choisir( suivant );
						suivant.focus();
					}
				} );
			} );

			// Sous-navigation : surligne la rubrique visible à l'écran.
			var liens = document.querySelectorAll( '.infos-sousnav a' );
			if ( ! ( 'IntersectionObserver' in window ) ) {
				return;
			}
			var observer = new IntersectionObserver( function ( entrees ) {
				entrees.forEach( function ( entree ) {
					if ( entree.isIntersecting ) {
						liens.forEach( function ( lien ) {
							if ( lien.getAttribute( 'href' ) === '#' + entree.target.id ) {
								lien.setAttribute( 'aria-current', 'true' );
							} else {
								lien.removeAttribute( 'aria-current' );
							}
						} );
					}
				} );
			}, { rootMargin: '-30% 0px -60% 0px' } );
			document.querySelectorAll( '.infos-section' ).forEach( function ( section ) {
				observer.observe( section );
			} );
		}() );
		</script>

	</main><!-- #primary -->

<?php
get_footer();
