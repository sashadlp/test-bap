<?php
/**
 * Page « Contact » : bandeau, espace parents (formulaire + questions fréquentes),
 * adresse postale et coordonnées de l'établissement.
 *
 * S'applique automatiquement à la page dont l'identifiant (slug) est « contact »
 * (hiérarchie des modèles WordPress : page-{slug}.php).
 *
 * Bandeau : image mise en avant de la page Contact, sinon celle de la page d'accueil.
 * Formulaire : envoyé par e-mail, voir inc/contact.php (destinataire, liste des objets).
 * Coordonnées : tableau $les_renardiere_services ci-dessous.
 *
 * @package Les_Renardiere
 */

get_header();

$les_renardiere_adresse = '15 Av. d’Alsace, 92400 Courbevoie';
$les_renardiere_carte   = 'https://www.google.com/maps/search/?api=1&query=15+Avenue+d%27Alsace+92400+Courbevoie';

// Services à contacter. « tel » = numéro affiché, « tel_lien » = même numéro au format international.
$les_renardiere_services = array(
	array(
		'nom'      => __( 'La vie scolaire', 'les-renardiere' ),
		'tel'      => '01 47 78 99 55',
		'tel_lien' => '+33147789955',
		'horaires' => __( 'Du lundi au vendredi, de 8h à 17h30', 'les-renardiere' ),
		'ecrire'   => true, // Bouton « Écrire à la vie scolaire » vers le formulaire.
	),
	array(
		'nom'      => __( 'Secrétariat de direction', 'les-renardiere' ),
		'tel'      => '01 47 78 99 55',
		'tel_lien' => '+33147789955',
		'horaires' => __( 'Du lundi au vendredi, de 8h à 17h30', 'les-renardiere' ),
		'ecrire'   => false,
	),
);

// Questions fréquentes (question => réponse, HTML simple autorisé).
$les_renardiere_faq = array(
	__( 'Que faire en cas d’absence de mon enfant ?', 'les-renardiere' ) => sprintf(
		/* translators: 1: numéro de téléphone, 2: lien vers le formulaire. */
		__( 'Prévenez la vie scolaire le jour même, avant 9h, par téléphone au %1$s ou %2$s. Au retour de votre enfant, justifiez l’absence par écrit dans le carnet de correspondance.', 'les-renardiere' ),
		'<a href="tel:+33147789955">01 47 78 99 55</a>',
		'<a href="' . esc_url( add_query_arg( 'objet', 'absence', get_permalink( get_queried_object_id() ) ) . '#formulaire-contact' ) . '" data-objet="absence">' . esc_html__( 'avec le formulaire ci-contre', 'les-renardiere' ) . '</a>'
	),
	__( 'Comment joindre le secrétariat ?', 'les-renardiere' )               => __( 'Par téléphone au <a href="tel:+33147789955">01 47 78 99 55</a>, du lundi au vendredi de 8h à 17h30, ou sur place à l’accueil du collège.', 'les-renardiere' ),
);

// Image du bandeau.
$les_renardiere_banniere = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
if ( ! $les_renardiere_banniere && get_option( 'page_on_front' ) ) {
	$les_renardiere_banniere = get_the_post_thumbnail_url( (int) get_option( 'page_on_front' ), 'full' );
}

// Formulaire : objet présélectionné (?objet=absence) et retour après envoi (?contact=…).
$les_renardiere_objets    = les_renardiere_contact_objets();
$les_renardiere_objet_get = isset( $_GET['objet'] ) ? sanitize_key( $_GET['objet'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$les_renardiere_etat      = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$les_renardiere_messages  = array(
	'envoye'   => __( 'Merci, votre message a bien été envoyé. La vie scolaire vous répondra par e-mail.', 'les-renardiere' ),
	'invalide' => __( 'Le message n’a pas été envoyé : vérifiez votre nom, votre e-mail, l’objet et le message.', 'les-renardiere' ),
	'erreur'   => __( 'Le message n’a pas pu être envoyé. Réessayez ou appelez la vie scolaire.', 'les-renardiere' ),
);
?>

	<div class="contact-banniere<?php echo $les_renardiere_banniere ? ' has-image' : ''; ?>"<?php echo $les_renardiere_banniere ? ' style="background-image:url(' . esc_url( $les_renardiere_banniere ) . ')"' : ''; ?> aria-hidden="true"></div>

	<main id="primary" class="site-main page-container page-contact">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<?php the_title( '<h1 class="entry-title contact-titre">', '</h1>' ); ?>

			<section class="contact-parents" aria-labelledby="espace-parents-titre">
				<h2 id="espace-parents-titre"><?php esc_html_e( 'Espace parents', 'les-renardiere' ); ?></h2>
				<p class="contact-parents__intro"><?php esc_html_e( 'Les services du quotidien, regroupés au même endroit.', 'les-renardiere' ); ?></p>

				<?php if ( get_the_content() ) : ?>
					<div class="entry-content"><?php the_content(); ?></div>
				<?php endif; ?>

				<div class="contact-grille">

					<section id="formulaire-contact" class="contact-carte" aria-labelledby="formulaire-titre">
						<h3 id="formulaire-titre"><?php esc_html_e( 'Contacter la vie scolaire', 'les-renardiere' ); ?></h3>

						<?php if ( isset( $les_renardiere_messages[ $les_renardiere_etat ] ) ) : ?>
							<p class="contact-etat contact-etat--<?php echo esc_attr( $les_renardiere_etat ); ?>" role="<?php echo 'envoye' === $les_renardiere_etat ? 'status' : 'alert'; ?>">
								<?php echo esc_html( $les_renardiere_messages[ $les_renardiere_etat ] ); ?>
							</p>
						<?php endif; ?>

						<form class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
							<input type="hidden" name="action" value="les_renardiere_contact">
							<?php wp_nonce_field( 'les_renardiere_contact', 'les_renardiere_contact_nonce' ); ?>

							<p class="contact-form__champ">
								<label for="contact-nom"><?php esc_html_e( 'Nom et prénom', 'les-renardiere' ); ?></label>
								<input type="text" id="contact-nom" name="nom" autocomplete="name" required>
							</p>

							<p class="contact-form__champ">
								<label for="contact-email"><?php esc_html_e( 'E-mail', 'les-renardiere' ); ?></label>
								<input type="email" id="contact-email" name="email" autocomplete="email" required>
							</p>

							<p class="contact-form__champ">
								<label for="contact-objet"><?php esc_html_e( 'Objet', 'les-renardiere' ); ?></label>
								<select id="contact-objet" name="objet" required>
									<option value=""><?php esc_html_e( 'Choisir un objet', 'les-renardiere' ); ?></option>
									<?php foreach ( $les_renardiere_objets as $les_renardiere_cle => $les_renardiere_libelle ) : ?>
										<option value="<?php echo esc_attr( $les_renardiere_cle ); ?>"<?php selected( $les_renardiere_objet_get, $les_renardiere_cle ); ?>><?php echo esc_html( $les_renardiere_libelle ); ?></option>
									<?php endforeach; ?>
								</select>
							</p>

							<p class="contact-form__champ">
								<label for="contact-message"><?php esc_html_e( 'Votre message', 'les-renardiere' ); ?></label>
								<textarea id="contact-message" name="message" rows="6" required></textarea>
							</p>

							<?php // Champ piège pour les robots, caché aux visiteurs. ?>
							<p class="contact-form__piege" aria-hidden="true">
								<label for="contact-site"><?php esc_html_e( 'Ne pas remplir ce champ', 'les-renardiere' ); ?></label>
								<input type="text" id="contact-site" name="site_web" tabindex="-1" autocomplete="off">
							</p>

							<p class="contact-form__note"><?php esc_html_e( 'Vos informations servent uniquement à vous répondre.', 'les-renardiere' ); ?></p>

							<button type="submit" class="bouton bouton--plein"><?php esc_html_e( 'Envoyer le message', 'les-renardiere' ); ?></button>
						</form>
					</section>

					<section class="contact-faq" aria-labelledby="faq-titre">
						<h3 id="faq-titre"><?php esc_html_e( 'Questions fréquentes', 'les-renardiere' ); ?></h3>

						<?php foreach ( $les_renardiere_faq as $les_renardiere_question => $les_renardiere_reponse ) : ?>
							<details class="contact-faq__item">
								<summary><?php echo esc_html( $les_renardiere_question ); ?></summary>
								<p><?php echo wp_kses( $les_renardiere_reponse, array( 'a' => array( 'href' => array(), 'data-objet' => array() ) ) ); ?></p>
							</details>
						<?php endforeach; ?>
					</section>

				</div>
			</section>

			<section class="contact-adresse" aria-labelledby="adresse-titre">
				<h2 id="adresse-titre"><?php esc_html_e( 'Adresse postale', 'les-renardiere' ); ?></h2>
				<address><?php echo esc_html( $les_renardiere_adresse ); ?></address>
				<a class="bouton" href="<?php echo esc_url( $les_renardiere_carte ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'Voir sur la carte', 'les-renardiere' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( '(nouvel onglet)', 'les-renardiere' ); ?></span>
				</a>
			</section>

			<section class="contact-etablissement" aria-labelledby="etablissement-titre">
				<h2 id="etablissement-titre"><?php esc_html_e( 'Contacter l’établissement', 'les-renardiere' ); ?></h2>

				<div class="contact-services">
					<?php foreach ( $les_renardiere_services as $les_renardiere_service ) : ?>
						<div class="contact-service">
							<h3><?php echo esc_html( $les_renardiere_service['nom'] ); ?></h3>
							<p><a href="tel:<?php echo esc_attr( $les_renardiere_service['tel_lien'] ); ?>"><?php echo esc_html( $les_renardiere_service['tel'] ); ?></a></p>
							<p class="contact-service__horaires"><?php echo esc_html( $les_renardiere_service['horaires'] ); ?></p>
							<div class="contact-service__boutons">
								<a class="bouton bouton--plein" href="tel:<?php echo esc_attr( $les_renardiere_service['tel_lien'] ); ?>"><?php esc_html_e( 'Appeler', 'les-renardiere' ); ?></a>
								<?php if ( $les_renardiere_service['ecrire'] ) : ?>
									<a class="bouton" href="#formulaire-contact"><?php esc_html_e( 'Écrire un message', 'les-renardiere' ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>

		<?php endwhile; ?>

		<script>
		( function () {
			// Liens « avec le formulaire » / « Écrire un message » : préremplit l'objet et place le curseur.
			var objet = document.getElementById( 'contact-objet' );
			document.querySelectorAll( '.page-contact a[href$="#formulaire-contact"]' ).forEach( function ( lien ) {
				lien.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					if ( lien.dataset.objet ) {
						objet.value = lien.dataset.objet;
					}
					document.getElementById( 'formulaire-contact' ).scrollIntoView( { behavior: 'smooth' } );
					( objet.value ? document.getElementById( 'contact-nom' ) : objet ).focus( { preventScroll: true } );
				} );
			} );
		}() );
		</script>

	</main><!-- #primary -->

<?php
get_footer();
