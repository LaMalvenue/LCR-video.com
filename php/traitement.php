<?php
// Récupération des données du formulaire

if (isset ($_POST["nom"]) && isset ($_POST["cp"]) && isset ($_POST["email"]) && isset ($_POST["typeDemande"]) && isset ($_POST["message"])) {
	$name = htmlspecialchars($_POST["nom"]);
	$cp = htmlspecialchars($_POST["cp"]);
	$email = htmlspecialchars($_POST["email"]);
	$web = htmlspecialchars($_POST["web"]);
	$tel = htmlspecialchars($_POST["tel"]);
	$object = htmlspecialchars($_POST["object"]);
	$message = htmlspecialchars($_POST["message"]);

	if (!isset($_POST['pieceJointe'])) {
		// Préparation du message d'envoi

		$to = "a.rouviere@hotmail.com";
		$subject = "[LCR-video] Vous avez reçu un mail !";
		$header = "MIME-Version: 1.0\r\n";
		$header .= 'From: "LCR-video"<yannis@lcr-video.com>' . "\n";
		$header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
		$header .= 'Content-Transfer-Encoding: 8bit';
		$messageHTML = '
		<html>
			<body>
				
				<p>Expéditeur : ' . $name . ' <a href="' . $email . '"></a> </p>
				<p>Code postal : ' . $cp . ' </p>
				<p>Téléphone : ' . $tel . ' </p>
				<p>Site web : <a href="' . $web . '"></a></p>
				<p>Type de demande : ' . $object . ' </p>
				<hr>
				<p>Message :</p>
				<p>' . $message . '</p>
				<br>
				<img src="https://images.ctfassets.net/hrltx12pl8hq/17iLMo2CS9k9k3d2v9uznb/d3e7080e01a1aedca423eb220efc23ee/shutterstock_1096026971_copy.jpg?fit=fill&w=480&h=400">
				
			</body>
		</html>
			';
		mail($to, $subject, $messageHTML, $header);
	}
}

header('Location: ../index.php');
?>
