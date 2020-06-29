<?php
require "PHPMailer/PHPMailerAutoload.php";

// ***************************  Envoi du message du formulaire ***************************
$mail = new PHPMailer();

$mail->SMTPDebug = 0;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';

$mail->Host = 'mail.lcr-video.com';
$mail->Port = 465;
$mail->Username = 'noreply@lcr-video.com';
$mail->Password = '*******';

$mail->From = "noreply@lcr-video.com";
$mail->FromName = $from_name = 'LCR Video';
$mail->Sender = $from = 'noreply@lcr-video.com';
$mail->AddReplyTo($from, $from_name);
$mail->AddAddress('contact@lcr-video.com');

$mail->CharSet = "UTF-8";
$mail->IsHTML(true);

function smtpmailer($mail, $subject, $body)
{
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = strip_tags($body);

	try {
		if (!$mail->Send()) {
			return "Erreur lors de l'envoi du message, veuillez réessayer";
		} else {
			return "Votre message a bien été envoyé !";
		}
	} catch (phpmailerException $e) {
		echo "Erreur lors de l'envoi du message, veuillez réessayer. Mailer Error: {$mail->ErrorInfo}";
	}
}

function smtpmailerPJ($mail, $subject, $body, $pathFile)
{
	try {
		$mail->addAttachment($pathFile);
	} catch (phpmailerException $e) {
		echo "Erreur lors de l'envoi de la pièce jointe, veuillez réessayer. Mailer Error: {$mail->ErrorInfo}";
	}

	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = strip_tags($body);

	try {
		if (!$mail->Send()) {
			return "Erreur lors de l'envoi du message, veuillez réessayer";
		} else {
			return "Votre message a bien été envoyé !";
		}
	} catch (phpmailerException $e) {
		echo "Erreur lors de l'envoi du message, veuillez réessayer. Mailer Error: {$mail->ErrorInfo}";
	}
}

if (isset ($_POST["firstName"]) &&
	isset ($_POST["lastName"]) &&
	isset ($_POST["email"]) &&
	isset ($_POST["city"]) &&
	isset ($_POST["videoType"]) &&
	isset ($_POST["message"])) {

	$nameClient = htmlspecialchars($_POST["firstName"]) . ' ' . htmlspecialchars($_POST["lastName"]);
	$firstName = htmlspecialchars($_POST["firstName"]);
	$email = htmlspecialchars($_POST["email"]);
	$phone = htmlspecialchars($_POST["phone"]);
	$city = htmlspecialchars($_POST["city"]);
	$company = htmlspecialchars($_POST["company"]);
	$web = htmlspecialchars($_POST["web"]);
	$videoType = htmlspecialchars($_POST["videoType"]);
	$message = htmlspecialchars($_POST["message"]);

	$subj = $nameClient;
	$msg = '
		<html>
			<body>
			<img src="https://lcr-video.com/mail/img/banner-mail-message.png" alt="banniere LCR video">
			    <h3 style="margin: 10px 0">Tu as reçu une demande ';
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
	$msg .= ' à ' . $city . ' 👌</h3></p>';


	$msg .= '		<img src="https://lcr-video.com/mail/img/separator.png" alt="separator">
				<p><blockquote style="font-size: 1.1rem;">«' . $message . '»</blockquote></p>
				
				<p><adress style="text-align: center; font-size: 0.8rem;">' . $nameClient;
	if ($company != "") {
		$msg .= ' (société ' . $company . ')<br>';
	} else {
		$msg .= '<br>';
	}
	if ($web != "http://") {
		$msg .= '<a href="' . $web . '">' . $web . '</a><br>';
	}
	$msg .= '<a>Contact : <a href="tel:+33' . $phone . '">' . $phone . '</a>, <a href="mailto: ' . $email . '">' . $email . '</a></adress></p>
				<img src="https://lcr-video.com/mail/img/banner-bottom.png" alt="banniere LCR video">
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
			$error = smtpmailerPJ($mail, $subj, $msg, $fileName);

		} else {
			echo "Une erreur est survenue, veuillez réesssayer.";
		}
	} else {
		$error = smtpmailer($mail, $subj, $msg);
	}

	// ***************************  Envoi de l'accusé de réception ***************************
	$deliveryreceipt = new PHPMailer();

	$deliveryreceipt->SMTPDebug = 0;
	$deliveryreceipt->IsSMTP();
	$deliveryreceipt->SMTPAuth = true;
	$deliveryreceipt->SMTPSecure = 'ssl';

	$deliveryreceipt->Host = 'mail.lcr-video.com';
	$deliveryreceipt->Port = 465;
	$deliveryreceipt->Username = 'noreply@lcr-video.com';
	$deliveryreceipt->Password = '*******';

	$deliveryreceipt->From = "noreply@lcr-video.com";
	$deliveryreceipt->FromName = $from_name = 'LCR Video';
	$deliveryreceipt->Sender = $from = 'noreply@lcr-video.com';
	$deliveryreceipt->AddAddress($email);

	$deliveryreceipt->CharSet = "UTF-8";
	$deliveryreceipt->IsHTML(true);

	$deliveryreceipt->Subject = "Accusé de réception de votre message 📧";

	$messagedelivery = '
<html>
<body>
<img src="https://lcr-video.com/mail/img/banner-accuse-message.png" alt="banniere LCR video">
<h3 style="margin: 10px 0">Votre message a bien été envoyé ! 👍</h3></p>';

	$messagedelivery .= '<p>Bonjour ' . $firstName . ',</p>';
	$messagedelivery .= '<p>Vous m\'avez envoyé le message suivant : </p>';
	$messagedelivery .= '<img src="https://lcr-video.com/mail/img/separator.png" alt="separator">';
	$messagedelivery .= '<p><blockquote style="font-size: 1.1rem; text-align: center; font-style: italic;">«' . $message . '»</blockquote></p>

<p><adress style="text-align: center; font-size: 0.8rem; font-style: italic;">' . $nameClient;
	if ($company != "") {
		$messagedelivery .= ' (société ' . $company . ')<br>';
	} else {
		$messagedelivery .= '<br>';
	}
	if ($web != "http://") {
		$msg .= '<a href="' . $web . '">' . $web . '</a><br>';
	}
	$messagedelivery .= '<a>Contact : <a href="tel:+33' . $phone . '">' . $phone . '</a>, <a href="mailto: ' . $email . '">' . $email . '</a></adress></p>

<img src="https://lcr-video.com/mail/img/separator.png" alt="separator">
<p>Vous recevrez une réponse dans les plus brefs délais, n\'hésitez pas à surveiller vos e-mails ! 😀</p>
	<p>A très bientôt,</p>
	<h4>Yannis de LCR Video</h4>
<img src="https://lcr-video.com/mail/img/banner-bottom.png" alt="banniere LCR video">
</body>
</html>
';
	$deliveryreceipt->Body = $messagedelivery;
	$deliveryreceipt->AltBody = strip_tags($messagedelivery);

	$deliveryreceipt->Send();
}

header("Refresh:2; url=../index.php");
?>

<html>
<head>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png"/>
    <title>Votre message</title>
</head>
<body style="background: black;padding-top:70px;color: white;font-family:sans-serif; text-align: center">
<h2><?php echo $error; ?></h2>
<p>Vous allez bientôt être redirigés vers l'accueil</p>

</body>
</html>