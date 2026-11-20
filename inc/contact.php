<?php
/**
 * Formulaire « Contacter la vie scolaire » de la page Contact (page-contact.php).
 *
 * Le formulaire est envoyé à admin-post.php, ce fichier vérifie les champs puis envoie un e-mail
 * avec wp_mail(). Pas d'extension nécessaire.
 *
 * Destinataire : l'e-mail d'administration du site (Réglages → Général), sauf si une adresse
 * est indiquée dans LES_RENARDIERE_CONTACT_EMAIL ci-dessous.
 *
 * @package Les_Renardiere
 */

if ( ! defined( 'LES_RENARDIERE_CONTACT_EMAIL' ) ) {
	// Mettre ici l'adresse de la vie scolaire, ex. 'vie-scolaire@exemple.fr'. Vide = e-mail d'administration.
	define( 'LES_RENARDIERE_CONTACT_EMAIL', '' );
}

/**
 * Objets proposés dans la liste déroulante (clé => libellé).
 *
 * @return array<string, string>
 */
function les_renardiere_contact_objets() {
	return array(
		'absence'     => __( 'Absence de mon enfant', 'les-renardiere' ),
		'retard'      => __( 'Retard', 'les-renardiere' ),
		'rendez-vous' => __( 'Demande de rendez-vous', 'les-renardiere' ),
		'scolarite'   => __( 'Question sur la scolarité', 'les-renardiere' ),
		'autre'       => __( 'Autre demande', 'les-renardiere' ),
	);
}

/**
 * Traite l'envoi du formulaire puis revient sur la page avec ?contact=envoye|invalide|erreur.
 */
function les_renardiere_contact_envoyer() {
	$retour = wp_get_referer();
	$retour = $retour ? $retour : les_renardiere_page_url( 'contact' );
	$retour = remove_query_arg( 'contact', $retour ? $retour : home_url( '/' ) );

	if ( ! isset( $_POST['les_renardiere_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['les_renardiere_contact_nonce'] ), 'les_renardiere_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'erreur', $retour ) . '#formulaire-contact' );
		exit;
	}

	// Champ piège invisible : rempli seulement par les robots.
	if ( ! empty( $_POST['site_web'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'envoye', $retour ) . '#formulaire-contact' );
		exit;
	}

	$objets  = les_renardiere_contact_objets();
	$nom     = isset( $_POST['nom'] ) ? sanitize_text_field( wp_unslash( $_POST['nom'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$objet   = isset( $_POST['objet'] ) ? sanitize_key( $_POST['objet'] ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $nom || ! is_email( $email ) || ! isset( $objets[ $objet ] ) || '' === trim( $message ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'invalide', $retour ) . '#formulaire-contact' );
		exit;
	}

	$destinataire = LES_RENARDIERE_CONTACT_EMAIL ? LES_RENARDIERE_CONTACT_EMAIL : get_option( 'admin_email' );

	$envoye = wp_mail(
		$destinataire,
		sprintf( '[Site du collège] %s', $objets[ $objet ] ),
		sprintf( "Nom : %s\nE-mail : %s\nObjet : %s\n\n%s", $nom, $email, $objets[ $objet ], $message ),
		array( sprintf( 'Reply-To: %s <%s>', str_replace( array( "\r", "\n", '<', '>' ), '', $nom ), $email ) )
	);

	wp_safe_redirect( add_query_arg( 'contact', $envoye ? 'envoye' : 'erreur', $retour ) . '#formulaire-contact' );
	exit;
}
add_action( 'admin_post_les_renardiere_contact', 'les_renardiere_contact_envoyer' );
add_action( 'admin_post_nopriv_les_renardiere_contact', 'les_renardiere_contact_envoyer' );
