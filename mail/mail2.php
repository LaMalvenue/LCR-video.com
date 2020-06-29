<?php
require "PHPMailer/PHPMailerAutoload.php";

$mail = new PHPMailer();

$mail->SMTPDebug = 0;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';


$mail->CharSet = "UTF-8";
$mail->IsHTML(true);

function smtpmailer($mail, $subject, $body)
{
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = strip_tags($body);

	if (!$mail->Send()) {
		return "Erreur lors de l'envoi du message, veuillez réessayer";
	} else {
		return "Votre message a bien été envoyé !";
	}
}

function smtpmailerPJ($mail, $subject, $body, $pathFile)
{
	$mail->addAttachment($pathFile);

	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = strip_tags($body);

	if (!$mail->Send()) {
		return "Erreur lors de l'envoi du message, veuillez réessayer";
	} else {
		return "Votre message a bien été envoyé !";
	}
}

if (isset ($_POST["firstName"]) &&
	isset ($_POST["lastName"]) &&
	isset ($_POST["email"]) &&
	isset ($_POST["city"]) &&
	isset ($_POST["videoType"]) &&
	isset ($_POST["message"])) {

	$nameClient = htmlspecialchars($_POST["firstName"]) . ' ' . htmlspecialchars($_POST["lastName"]);
	$email = htmlspecialchars($_POST["email"]);
	$phone = htmlspecialchars($_POST["phone"]);
	$city = htmlspecialchars($_POST["city"]);
	$company = htmlspecialchars($_POST["company"]);
	$web = htmlspecialchars($_POST["web"]);
	$videoType = htmlspecialchars($_POST["videoType"]);
	$message = htmlspecialchars($_POST["message"]);

	$subj = $nameClient;
	$msg .= '
		<html lang="fr">
			<body>
			    <h3>Tu as reçu une demande ';
	switch ($videoType) {
		case "clip" :
			$subj .= ' voudrait un ' . $videoType . ' vidéo ! 👹';
			$msg .= 'de ' . $videoType . ' vidéo';
			break;
		case "corporate":
		case "mode" :
			$subj .= ' voudrait une vidéo ' . $videoType . ' ! 🥳';
			$msg .= 'de film ' . $videoType;
			break;
		case "mariage" :
			$subj .= ' voudrait un film pour son mariage ! 👰🤵';
			$msg .= 'pour un ' . $videoType;
			break;
		case "publicité" :
			$subj .= ' veut tourner une ' . $videoType . ' ! 🙋‍♂️';
			$msg .= 'de ' . $videoType;
			break;
		default :
			$subj .= ' a une demande particulière 👽';
			$msg .= 'spéciale';
			break;
	}
	$msg .= ' à ' . $city . '</h3></p>';


	$msg .= '		<hr>
				<p>' . $message . '</p>
				
				<p>' . $nameClient;
	if ($company != "") {
		$msg .= ' (société ' . $company . ')</p>';
	} else {
		$msg .= '</p>';
	}
	if ($web != "http://") {
		$msg .= '<p><a href="' . $web . '">' . $web . '</a></p>';
	}
	$msg .= '<a>Contact : ' . $phone . ', <a href="mailto: ' . $email . '">' . $email . '</a></p>
				<hr>
                <p>Ceci est un envoi automatique, merci de ne pas répondre à cet e-mail</p>
			</body>
		</html>
	';

	if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == 0) {
		$uploaded_file = $_FILES['uploaded_file'];

		$maxSize = 10000000; // = 10Mo
		$validExtensions = array('.jpg', '.jpeg', '.png', '.pdf', '.doc', '.docx', '.txt');
		$fileSize = $_FILES['uploaded_file']['size'];
		$fileName = $_FILES['uploaded_file']['name'];
		$fileExtension = "." . strtolower(substr(strrchr($fileName, '.'), 1));

		if ($fileSize > $maxSize) {
			echo "Votre fichier dépasse la taille maximum autorisée (10Mo)";
			die;
		}
		if (!in_array($fileExtension, $validExtensions)) {
			echo "Votre extension n'est pas valide";
			die;
		}

		$tmpName = $_FILES['uploaded_file']['tmp_name'];
		$IDfile = md5(uniqid(rand(), true));
		$fileName = "uploads/" . $IDfile . $fileExtension;
		$result = move_uploaded_file($tmpName, $fileName);

		if ($result) {
			echo "Transfert terminé !";
			$error = smtpmailerPJ($mail, $subj, $msg, $fileName);

		} else {
			echo "Une erreur est survenue, veuillez réesssayer.";
		}
	} else {
		$error = smtpmailer($mail, $subj, $msg);
	}
}
header("Refresh:2; url=../index.php");
?>

<html lang="fr">
<head>
    <title>Votre message</title>
</head>
<body style="background: black;padding-top:70px;color: white;font-family:sans-serif;">
<center><h2><?php echo $error; ?></h2>
    <p>Vous allez bientôt être redirigés vers l'accueil</p>
</center>
</body>

</html>