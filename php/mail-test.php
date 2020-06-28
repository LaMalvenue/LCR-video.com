<?php
// Préparation du message d'envoi

$to = "a.rouviere@hotmail.com";
$subject = "[LCR-video] Vous avez reçu un mail !";
$header = "MIME-Version: 1.0\r\n";
$header .= 'From: "LCR-video"<yannis@lcr-video.com>'."\n";
$header .= 'Content-Type:text/html; charset="utf-8"'."\n";
$header .= 'Content-Transfer-Encoding: 8bit';

$messageHTML = '
<html>
	<body>
		
		<h4>Sujet :</h4>
		<h4>Expéditeur :</h4>
		<h4>Code postal :</h4>
		<h4>Téléphone :</h4>
		<h4>Site web :</h4>
		<h4>Type de demande :</h4>
		<hr>
		<h4>Message :</h4>
		<p>J\'ai envoyé ce mail avec PHP !</p>
		<br>
		<img src="https://images.ctfassets.net/hrltx12pl8hq/17iLMo2CS9k9k3d2v9uznb/d3e7080e01a1aedca423eb220efc23ee/shutterstock_1096026971_copy.jpg?fit=fill&w=480&h=400">
		
	</body>
</html>
	';

// Envoi des données du formulaire
mail($to, $subject, $messageHTML, $header);
 ?>