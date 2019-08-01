<?php
	
	/** 
	*	Dependecias:
	*		Ninguna.
	*
	*	Descripcion: 
	*/


	ini_set("display_errors", "On");
	error_reporting(E_ALL | E_STRICT);

 	header("Content-Type: text/html; charset=UTF-8"); 
 	date_default_timezone_set('Etc/UTC');
 	
	require_once(LIB_PATH.DS."config/config.php");

	class Email {

		private $emailsDestino = array();

		/**
		*	NO FUNCIONA BIEN.
		*	Envia emails.
		*
		*	Notas:Es correcto, pero inestable.
		*	Depende de un servidor de correos como postfix. 
		*/
		public static function enviar_email () {
			$para = "tucbox@gmail.com, pablo7799@hotmail.com";
			$titulo = "El titulo";
			$mensaje = "Hola";
			$cabeceras = "From: tucbox@gmail.com" . "\r\n" .
		    "Reply-To: tucbox@gmail.com" . "\r\n" .
		    "X-Mailer: PHP/" . phpversion();

			if(mail($para, $titulo, $mensaje, $cabeceras)) {
				echo "Emails enviados.";
			}
			else {
				echo date("d/m/Y-G:i") . " - ERROR: al intentar enviar los emails.";
				error_log(date("d/m/Y-G:i") . " - ERROR: al intentar enviar los emails.\n", 3, LOG_ERRORS);
				//enviar a log
			}
		}

		/**
		*	Envia emails desde gmail.
		*
		*	Dependecias 
		*		Depende de PHPMailer.
		*		require_once("PHPMailer-master/PHPMailerAutoload.php");
		*	
		*	Notas:	
		*		Para hacer que funcione tuve que deshabilitar una caracteristica de seguridad:
		*			https://www.google.com/settings/u/1/security/lesssecureapps
		*/
		public static function gmail ($body="Vacio") {
			$mail = new PHPMailer;
			$mail->isSMTP(); //Set mailer to use SMTP
			$mail->Host = "smtp.gmail.com"; //Specify main and backup server
			$mail->SMTPAuth = true; //Enable SMTP authentication
			$mail->Username = "cabeceraccc@gmail.com"; //SMTP username
			$mail->Password = "sanlorenzo6712"; //SMTP password
			$mail->SMTPSecure = "tls"; //Enable encryption, "ssl" also accepted
			$mail->Port = 587;	//Set the SMTP port number - 587 for authenticated TLS
			$mail->setFrom("cabeceraccc@gmail.com", "Servidor CCC"); //Set who the message is to be sent from
			//$mail->addReplyTo("labnol@gmail.com", "First Last");  //Set an alternative reply-to address
			$mail->addAddress("tucbox@gmail.com", "Pablo Cristo");  //Add a recipient
			$mail->addAddress("pablo7799@hotmail.com", "Pablo Cristo"); //Name is optional
			//$mail->addCC("cc@example.com");
			//$mail->addBCC("bcc@example.com");
			$mail->WordWrap = 50; // Set word wrap to 50 characters
			//$mail->addAttachment("/usr/labnol/file.doc"); // Add attachments
			//$mail->addAttachment("/images/image.jpg", "new.jpg"); // Optional name
			$mail->isHTML(true); // Set email format to HTML

			$mail->Subject = "ERROR durante la carga del EPG.";
			$mail->Body    = $body;
			$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			//$mail->msgHTML(file_get_contents("contents.html"), dirname(__FILE__));

			if($mail->send()) {
				echo "Emails enviados.";
			}
			else {
				echo "Message could not be sent.";
				echo "Mailer Error: " . $mail->ErrorInfo;
				exit;
			}
		}
	}

?>