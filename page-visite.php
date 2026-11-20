<?php
/**
 * Page « Visite guidée de l'établissement » (identifiant / slug « visite ») :
 * visite en étapes (précédent / suivant), chiffres clés, offre de formation, accès.
 *
 * S'applique automatiquement à la page « visite » (hiérarchie des modèles : page-{slug}.php).
 * Le contenu se modifie dans les tableaux ci-dessous.
 *
 * Photos : téléverser dans Médias une image nommée visite-accueil.jpg, visite-classes.jpg, etc.
 * (le nom du fichier = « visite- » + la clé de l'étape). Tant qu'il n'y a pas de photo,
 * l'étape affiche une illustration. Un encadré visible seulement par les admins liste les photos manquantes.
 *
 * @package Les_Renardiere
 */

get_header();

// Étapes de la visite : clé (nom de la photo) => contenu. « liens » = [ libellé, slug de page ].
$les_renardiere_etapes = array(
	'accueil'  => array(
		'titre' => 'L’accueil et la vie scolaire',
		'texte' => 'En arrivant, élèves et familles sont accueillis par la vie scolaire. La CPE et les assistants d’éducation suivent les absences, accompagnent les élèves au quotidien et sont le premier contact des familles.',
		'liens' => array( array( 'Infos pratiques', 'le-college/infos-pratiques' ), array( 'Contacter la vie scolaire', 'contact' ) ),
	),
	'classes'  => array(
		'titre' => 'Les salles de classe',
		'texte' => 'Environ 440 élèves répartis en 16 classes, de la 6e à la 3e : un collège à taille humaine, où chaque élève peut trouver sa place et être accompagné.',
		'liens' => array( array( 'Le mot de la principale', 'le-mot-du-principal' ) ),
	),
	'cdi'      => array(
		'titre' => 'Le CDI',
		'texte' => 'Le centre de documentation et d’information est ouvert aux élèves pour lire, faire des recherches, emprunter des livres et des magazines, et apprendre à s’informer.',
		'liens' => array(),
	),
	'musique'  => array(
		'titre' => 'La musique',
		'texte' => 'La CHAM et la chorale y répètent, en partenariat avec le conservatoire de Courbevoie. Depuis 2026, le collège accueille même un piano à queue.',
		'liens' => array( array( 'La CHAM', 'cham' ) ),
	),
	'sciences' => array(
		'titre' => 'Sciences et technologie',
		'texte' => 'SVT, physique-chimie et technologie : on y expérimente et on y construit. Le club robotique y programme des robots qui se déplacent seuls sur une piste.',
		'liens' => array( array( 'Atelier robotique', 'atelier-robotique' ) ),
	),
	'sport'    => array(
		'titre' => 'Le sport',
		'texte' => 'Cours d’EPS et association sportive : sports collectifs, step, et des sorties comme le hockey sur gazon ou un séjour surf en 5e.',
		'liens' => array(),
	),
	'jardin'   => array(
		'titre' => 'Le jardin et l’éco-collège',
		'texte' => 'Un atelier jardinage, du compostage et les projets des éco-délégués, récompensés par trois médailles du développement durable des Hauts-de-Seine.',
		'liens' => array( array( 'Les éco-délégués', 'les-eco-delegues' ), array( 'Classe ULIS', 'classe-ulis' ) ),
	),
);

$les_renardiere_chiffres = array(
	array( '≈ 440', 'élèves' ),
	array( '16', 'classes, de la 6e à la 3e' ),
	array( '2', 'sections internationales' ),
	array( '1', 'classe à horaires aménagés musique' ),
);

// Offre de formation (d'après « Le mot de la principale »). Dernier champ = slug de page (facultatif).
$les_renardiere_offre = array(
	array( 'Section internationale américaine', 'Langue et culture anglo-américaines, de la 6e à la 3e', 'section-internationale' ),
	array( 'Section internationale arabe', 'Langue et culture arabes, de la 6e à la 3e', 'section-internationale' ),
	array( 'Classe bilangue allemand', 'Deux langues vivantes dès la 6e', '' ),
	array( 'LCE anglais', 'Langues et cultures européennes', '' ),
	array( 'Langues et cultures de l’Antiquité', 'Découvrir les langues et la culture antiques', '' ),
	array( 'CHAM', 'Chant, avec le conservatoire de Courbevoie', 'cham' ),
	array( 'ULIS', 'Unité localisée pour l’inclusion scolaire', 'classe-ulis' ),
);

// Photos des étapes (visite-accueil.jpg…).
$les_renardiere_photos     = array();
$les_renardiere_manquantes = array();
foreach ( array_keys( $les_renardiere_etapes ) as $les_renardiere_cle ) {
	$les_renardiere_photo = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'name'           => 'visite-' . $les_renardiere_cle,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $les_renardiere_photo ) {
		$les_renardiere_photos[ $les_renardiere_cle ] = $les_renardiere_photo[0];
	} else {
		$les_renardiere_manquantes[] = 'visite-' . $les_renardiere_cle . '.jpg';
	}
}

$les_renardiere_contact = les_renardiere_page_url( 'contact' );
$les_renardiere_total   = count( $les_renardiere_etapes );
?>

	<main id="primary" class="site-main page-container page-visite">

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

			<header class="visite-tete">
				<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
				<p><?php esc_html_e( 'Au cœur de La Défense, à Courbevoie, découvrez les lieux du collège Les Renardières en quelques étapes.', 'les-renardiere' ); ?></p>
			</header>

			<?php if ( current_user_can( 'upload_files' ) && $les_renardiere_manquantes ) : ?>
				<div class="visite-diagnostic" role="note">
					<p>
						<strong><?php esc_html_e( 'Visible seulement par les admins :', 'les-renardiere' ); ?></strong>
						<?php
						printf(
							/* translators: %s: noms des fichiers photo manquants. */
							esc_html__( 'ajoutez ces photos dans Médias pour remplacer les illustrations : %s.', 'les-renardiere' ),
							esc_html( implode( ', ', $les_renardiere_manquantes ) )
						);
						?>
					</p>
				</div>
			<?php endif; ?>

			<ul class="visite-chiffres">
				<?php foreach ( $les_renardiere_chiffres as $les_renardiere_chiffre ) : ?>
					<li><strong><?php echo esc_html( $les_renardiere_chiffre[0] ); ?></strong> <span><?php echo esc_html( $les_renardiere_chiffre[1] ); ?></span></li>
				<?php endforeach; ?>
			</ul>

			<section class="visite-parcours" aria-labelledby="visite-parcours-titre">
				<h2 id="visite-parcours-titre"><?php esc_html_e( 'La visite, étape par étape', 'les-renardiere' ); ?></h2>

				<div class="visite-tour">
					<ol class="visite-etapes" role="tablist" aria-label="<?php esc_attr_e( 'Étapes de la visite', 'les-renardiere' ); ?>">
						<?php
						$les_renardiere_num = 0;
						foreach ( $les_renardiere_etapes as $les_renardiere_cle => $les_renardiere_etape ) :
							++$les_renardiere_num;
							?>
							<li role="presentation">
								<button type="button" role="tab" id="etape-onglet-<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-controls="etape-<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-selected="<?php echo 1 === $les_renardiere_num ? 'true' : 'false'; ?>" tabindex="<?php echo 1 === $les_renardiere_num ? '0' : '-1'; ?>">
									<span class="visite-etapes__num" aria-hidden="true"><?php echo esc_html( $les_renardiere_num ); ?></span>
									<?php echo esc_html( $les_renardiere_etape['titre'] ); ?>
								</button>
							</li>
						<?php endforeach; ?>
					</ol>

					<div class="visite-panneaux">
						<?php
						$les_renardiere_num = 0;
						foreach ( $les_renardiere_etapes as $les_renardiere_cle => $les_renardiere_etape ) :
							++$les_renardiere_num;
							?>
							<article class="visite-panneau" role="tabpanel" id="etape-<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-labelledby="etape-onglet-<?php echo esc_attr( $les_renardiere_cle ); ?>" tabindex="-1">
								<div class="visite-panneau__media">
									<?php if ( isset( $les_renardiere_photos[ $les_renardiere_cle ] ) ) : ?>
										<?php echo wp_get_attachment_image( $les_renardiere_photos[ $les_renardiere_cle ], 'large', false, array( 'loading' => 1 === $les_renardiere_num ? 'eager' : 'lazy' ) ); ?>
									<?php else : ?>
										<div class="visite-panneau__illustration visite-panneau__illustration--<?php echo esc_attr( $les_renardiere_cle ); ?>" aria-hidden="true">
											<span><?php echo esc_html( $les_renardiere_num ); ?></span>
										</div>
									<?php endif; ?>
								</div>
								<div class="visite-panneau__texte">
									<p class="visite-panneau__etape">
										<?php
										/* translators: 1: numéro de l'étape, 2: nombre d'étapes. */
										printf( esc_html__( 'Étape %1$s sur %2$s', 'les-renardiere' ), esc_html( $les_renardiere_num ), esc_html( $les_renardiere_total ) );
										?>
									</p>
									<h3><?php echo esc_html( $les_renardiere_etape['titre'] ); ?></h3>
									<p><?php echo esc_html( $les_renardiere_etape['texte'] ); ?></p>
									<?php
									$les_renardiere_liens = array_filter(
										array_map(
											function ( $lien ) {
												$url = les_renardiere_page_url( $lien[1] );
												return $url ? array( $lien[0], $url ) : null;
											},
											$les_renardiere_etape['liens']
										)
									);
									?>
									<?php if ( $les_renardiere_liens ) : ?>
										<ul class="visite-panneau__liens">
											<?php foreach ( $les_renardiere_liens as $les_renardiere_lien ) : ?>
												<li><a href="<?php echo esc_url( $les_renardiere_lien[1] ); ?>"><?php echo esc_html( $les_renardiere_lien[0] ); ?> <span aria-hidden="true">→</span></a></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>

						<div class="visite-nav" hidden>
							<button type="button" class="bouton" data-pas="-1"><span aria-hidden="true">←</span> <?php esc_html_e( 'Étape précédente', 'les-renardiere' ); ?></button>
							<div class="visite-progression" aria-hidden="true"><span></span></div>
							<button type="button" class="bouton bouton--plein" data-pas="1"><?php esc_html_e( 'Étape suivante', 'les-renardiere' ); ?> <span aria-hidden="true">→</span></button>
						</div>
					</div>
				</div>
			</section>

			<section class="visite-offre" aria-labelledby="visite-offre-titre">
				<h2 id="visite-offre-titre"><?php esc_html_e( 'Une offre de formation élargie', 'les-renardiere' ); ?></h2>
				<ul class="visite-offre__grille">
					<?php foreach ( $les_renardiere_offre as $les_renardiere_item ) : ?>
						<?php $les_renardiere_url = $les_renardiere_item[2] ? les_renardiere_page_url( $les_renardiere_item[2] ) : ''; ?>
						<li class="visite-offre__item<?php echo $les_renardiere_url ? ' has-lien' : ''; ?>">
							<h3>
								<?php if ( $les_renardiere_url ) : ?>
									<a href="<?php echo esc_url( $les_renardiere_url ); ?>"><?php echo esc_html( $les_renardiere_item[0] ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $les_renardiere_item[0] ); ?>
								<?php endif; ?>
							</h3>
							<p><?php echo esc_html( $les_renardiere_item[1] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="visite-acces" aria-labelledby="visite-acces-titre">
				<div class="visite-acces__texte">
					<h2 id="visite-acces-titre"><?php esc_html_e( 'Venir au collège', 'les-renardiere' ); ?></h2>
					<address>15 Avenue d’Alsace – La Défense 3<br>92400 Courbevoie</address>
					<ul class="visite-acces__transports">
						<li><span class="visite-acces__ligne visite-acces__ligne--metro">M 1</span> <?php esc_html_e( 'Esplanade de la Défense', 'les-renardiere' ); ?></li>
						<li><span class="visite-acces__ligne visite-acces__ligne--rer">RER A</span> <?php esc_html_e( 'Grande Arche', 'les-renardiere' ); ?></li>
					</ul>
					<p><a href="tel:+33147789955">01 47 78 99 55</a></p>
				</div>
				<div class="visite-acces__boutons">
					<a class="bouton bouton--plein" href="https://www.google.com/maps/dir/?api=1&amp;destination=15+Avenue+d%27Alsace+92400+Courbevoie" target="_blank" rel="noopener">
						<?php esc_html_e( 'Calculer mon itinéraire', 'les-renardiere' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(nouvel onglet)', 'les-renardiere' ); ?></span>
					</a>
					<?php if ( $les_renardiere_contact ) : ?>
						<a class="bouton" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Contacter le collège', 'les-renardiere' ); ?></a>
					<?php endif; ?>
				</div>
			</section>

			<aside class="visite-cta">
				<div>
					<p class="visite-cta__titre"><?php esc_html_e( 'Envie de visiter le collège en vrai ?', 'les-renardiere' ); ?></p>
					<p><?php esc_html_e( 'Chaque année, le collège ouvre ses portes aux futurs élèves et à leurs familles. Contactez-nous pour connaître la date de la prochaine journée portes ouvertes.', 'les-renardiere' ); ?></p>
				</div>
				<?php if ( $les_renardiere_contact ) : ?>
					<a class="bouton bouton--plein" href="<?php echo esc_url( $les_renardiere_contact ); ?>"><?php esc_html_e( 'Nous contacter', 'les-renardiere' ); ?></a>
				<?php endif; ?>
			</aside>

		<?php endwhile; ?>

		<script>
		( function () {
			// Visite en étapes : sans JavaScript, toutes les étapes s'affichent les unes sous les autres.
			var tour = document.querySelector( '.visite-tour' );
			if ( ! tour ) {
				return;
			}
			var onglets = Array.prototype.slice.call( tour.querySelectorAll( '[role="tab"]' ) );
			var nav = tour.querySelector( '.visite-nav' );
			var barre = tour.querySelector( '.visite-progression span' );
			var actuel = 0;

			tour.classList.add( 'is-actif' );
			nav.hidden = false;

			function aller( index, focus, defiler ) {
				actuel = ( index + onglets.length ) % onglets.length;
				onglets.forEach( function ( onglet, i ) {
					var actif = i === actuel;
					onglet.setAttribute( 'aria-selected', actif ? 'true' : 'false' );
					onglet.tabIndex = actif ? 0 : -1;
					document.getElementById( onglet.getAttribute( 'aria-controls' ) ).hidden = ! actif;
				} );
				barre.style.width = ( ( actuel + 1 ) / onglets.length * 100 ) + '%';
				nav.querySelector( '[data-pas="-1"]' ).disabled = 0 === actuel;
				nav.querySelector( '[data-pas="1"]' ).disabled = onglets.length - 1 === actuel;
				if ( focus ) {
					onglets[ actuel ].focus();
				} else if ( defiler ) {
					// Téléphone : la ligne des étapes suit l'étape affichée.
					var liste = onglets[ actuel ].closest( 'ol' );
					liste.scrollLeft = onglets[ actuel ].offsetLeft - liste.offsetLeft - 16;
				}
			}

			onglets.forEach( function ( onglet, i ) {
				onglet.addEventListener( 'click', function () {
					aller( i );
				} );
				onglet.addEventListener( 'keydown', function ( e ) {
					var pas = ( 'ArrowDown' === e.key || 'ArrowRight' === e.key ) ? 1 : ( ( 'ArrowUp' === e.key || 'ArrowLeft' === e.key ) ? -1 : 0 );
					if ( pas ) {
						e.preventDefault();
						aller( i + pas, true );
					}
				} );
			} );

			nav.querySelectorAll( '[data-pas]' ).forEach( function ( bouton ) {
				bouton.addEventListener( 'click', function () {
					aller( actuel + parseInt( bouton.dataset.pas, 10 ), false, true );
				} );
			} );

			aller( 0 );
		}() );
		</script>

	</main><!-- #primary -->

<?php
get_footer();
