<?php
// Activation de l'affichage des erreurs pour le débogage (à retirer en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération et nettoyage des données
    $nom = isset($_POST["nom"]) ? strip_tags(trim($_POST["nom"])) : '';
    $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
    $sujet = isset($_POST["sujet"]) ? strip_tags(trim($_POST["sujet"])) : '';
    $message = isset($_POST["message"]) ? strip_tags(trim($_POST["message"])) : '';
    
    // Validation des champs
    $erreurs = [];
    
    if (empty($nom)) {
        $erreurs[] = "Le nom est requis";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'email n'est pas valide";
    }
    
    if (empty($sujet)) {
        $erreurs[] = "Le sujet est requis";
    }
    
    if (empty($message)) {
        $erreurs[] = "Le message est requis";
    }
    
    // S'il n'y a pas d'erreurs, envoyer l'email
    if (empty($erreurs)) {
        
        // Destinataire (remplacez par VOTRE adresse email)
        $destinataire = "votre-email@exemple.com";
        
        // Sujet de l'email
        $email_sujet = "Nouveau message de portfolio - $sujet";
        
        // Construction du corps du message en HTML
        $contenu_html = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                h2 { color: #d9b504; margin-top: 0; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #333; }
                .value { color: #666; padding: 5px 0; }
                hr { border: 1px solid #eee; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Nouveau message de contact</h2>
                <div class='field'>
                    <div class='label'>Nom :</div>
                    <div class='value'>$nom</div>
                </div>
                <div class='field'>
                    <div class='label'>Email :</div>
                    <div class='value'>$email</div>
                </div>
                <div class='field'>
                    <div class='label'>Sujet :</div>
                    <div class='value'>$sujet</div>
                </div>
                <hr>
                <div class='field'>
                    <div class='label'>Message :</div>
                    <div class='value'>" . nl2br($message) . "</div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Version texte pour les clients email qui n'affichent pas le HTML
        $contenu_texte = "Nouveau message de contact\n\n";
        $contenu_texte .= "Nom: $nom\n";
        $contenu_texte .= "Email: $email\n";
        $contenu_texte .= "Sujet: $sujet\n\n";
        $contenu_texte .= "Message:\n$message\n";
        
        // En-têtes pour l'email HTML
        $headers = "From: $nom <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Envoi de l'email
        if (mail($destinataire, $email_sujet, $contenu_html, $headers)) {
            // Redirection vers une page de succès
            header("Location: contact-success.html");
            exit();
        } else {
            // Erreur d'envoi
            echo "<h2 style='color: #d9b504;'>Erreur d'envoi</h2>";
            echo "<p style='color: #f2e63d;'>Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.</p>";
            echo "<a href='contact.html' style='color: #d9b504;'>Retour au formulaire</a>";
        }
    } else {
        // Afficher les erreurs de validation
        echo "<h2 style='color: #d9b504;'>Erreurs de validation</h2>";
        echo "<ul style='color: #f2e63d;'>";
        foreach ($erreurs as $erreur) {
            echo "<li>$erreur</li>";
        }
        echo "</ul>";
        echo "<a href='contact.html' style='color: #d9b504;'>Retour au formulaire</a>";
    }
} else {
    // Si quelqu'un essaie d'accéder directement au fichier PHP sans passer par le formulaire
    header("Location: contact.html");
    exit();
}
?>