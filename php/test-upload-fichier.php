<html>
<head>
    <title>Test d'upload de fichiers</title>
</head>
<body>
<form method="post" action="test-upload-fichier.php" enctype="multipart/form-data" class="col-lg-12">

    <div class="form-row">
        <div class="form-group col-md-4">
            <input type="text" name="nom" id="nom" placeholder="Nom / Société"
                   required="required" class="form-control"/>
        </div>
        <div class="form-group col-md-4">
            <input type="email" name="email" id="email" placeholder="Adresse mail"
                   required="required" class="form-control"/>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-4 form-group">
            <input type="number" name="cp" id="cp" placeholder="Code postal" required="required"
                   class="form-control"/>
        </div>
        <div class="form-group col-md-4">
            <input type="tel" name="tel" id="tel" placeholder="Portable" class="form-control" />
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-8 form-group">
            <input type="text" name="web" id="web" value="http://" class="form-control"
                   aria-describedby="webHelpBlock">
            <small id="webHelpBlock" class="form-text text-muted space" style="text-align: left">
                Lien vers votre travail ou votre site web (facultatif).
            </small>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12 form-group my-auto">
            <fieldset id="object" class="para">
                <legend>Votre demande concerne</legend>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="object"
                           id="clip" value="Clip" checked/>
                    <label class="custom-control-label" for="clip">Clip</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="object"
                           id="mode" value="Mode"/>
                    <label class="custom-control-label" for="mode">Mode</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="object"
                           id="corporate" value="Corporate"/>
                    <label class="custom-control-label" for="corporate">Corporate</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="object"
                           id="pub" value="Publicité"/>
                    <label class="custom-control-label" for="pub">Publicité</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="object"
                           id="mariage" value="Mariage"/>
                    <label class="custom-control-label" for="mariage">Mariage</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="object"
                           id="autre" value="Autre demande"/>
                    <label class="custom-control-label" for="autre">Autre demande</label>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-8 form-group">
                                    <textarea name="message" placeholder="Message" required="required"
                                              class="form-control" aria-describedby="messageHelpBlock"></textarea>
            <small id="messageHelpBlock" class="form-text text-muted" style="text-align: left">
                Décrivez votre projet. Si vous avez une documentation, vous pourrez l'envoyer
                ci-dessous.
            </small>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-8 form-group">
            <div class="custom-file">
                <input name="uploaded_file" type="file" class="custom-file-input" id="customFile"
                       aria-describedby="fichierHelpBlock">
                <label class="custom-file-label" for="customFile" data-browse="Parcourir">Pièce
                    jointe</label>
                <small id="fichierHelpBlock" class="form-text text-muted space"
                       style="text-align: left">
                    Formats acceptés : jpg, jpeg, png, pdf, doc/docx, txt.
                </small>
            </div>
        </div>
    </div>
    <input type="submit" value="Envoyer" class="btn button text-uppercase contact-button">
</form>
</body>
</html>

<?php
// Récupération des données du formulaire
if (isset ($_POST["nom"]) && isset ($_POST["cp"]) && isset ($_POST["email"]) && isset ($_POST["object"]) && isset ($_POST["message"]))
{
	$name = htmlspecialchars($_POST["nom"]);
	$cp = htmlspecialchars($_POST["cp"]);
	$email = htmlspecialchars($_POST["email"]);
	$web = htmlspecialchars($_POST["web"]);
	$tel = htmlspecialchars($_POST["tel"]);
	$object = htmlspecialchars($_POST["object"]);
	$message = htmlspecialchars($_POST["message"]);

	if (isset($_FILES["uploaded_file"]) &&  $_FILES["uploaded_file"]['error'== 0])
    {
        $hasFile = true;

    }

	// Préparation du message d'envoi

	$to = "a.rouviere@hotmail.com";
	$subject = "[LCR-video] Vous avez reçu un mail !";

	$delimitation = md5(uniqid(rand()));

	$header = "MIME-Version: 1.0\r\n";
	$header .= "From: \"$name\"<$email>\" \n";
	$header .= "Content-type:multipart/mixed;boundary=\"$delimitation\" \n";
	$header .= "\n";

	$messageContent = "\n--$delimitation \n";
	$messageContent .= "Content-Type:text/html; charset=\"utf-8\" \n";
	$messageContent .= "Content-Transfer-Encoding: 8bit \n";
	$messageContent .= "\n";
	$messageContent .= '
		<html>
			<body>
				
				<p>Message de '.$name . ' <a href="'.$email.'"></a></p>
				<p>Code postal : ' . $cp . ' </p>
				<p>Téléphone : ' . $tel . ' </p>
				<p>Site web : <a href="' . $web . '"></a></p>
				<p>Type de video : ' . $object . ' </p>
				<hr>
				<p>Message :</p>
				<p>' . $message . '</p>
				<br>
				<img src="https://images.ctfassets.net/hrltx12pl8hq/17iLMo2CS9k9k3d2v9uznb/d3e7080e01a1aedca423eb220efc23ee/shutterstock_1096026971_copy.jpg?fit=fill&w=480&h=400">
				
			</body>
		</html>
	';
	$messageContent .= "\n";

	if ($hasFile &&  $_FILES["uploaded_file"]['error'== 0]) {
		$pieceJointe = file_get_contents($fileName);
		$pieceJointe = chunk_split(base64_encode($pieceJointe));

		$messageContent .= "--$delimitation \n";


		$messageContent .= "Content-Type:image/jpg; name=\"$fileName\" \n";

			$messageContent .= "Content-Transfer-Encoding:base64 \n";
			$messageContent .= "Content-Disposition:inline; filename=\"$fileName\" \n";
			$messageContent .= "\n";
			$messageContent .= $pieceJointe . "\n";
			$messageContent .= "\n";
			echo "envoi avec pj";
		}
echo "envoi sans pj";
		$messageContent .= "\n --$delimitation--";
	    mail($to, $subject, $messageContent, $header);


}
// header('Location: ../index.php');

?>