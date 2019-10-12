<?php
		$msg_salida = "mensaje";
		$estilo = "background-color:blue; color:white;";

		require '/var/www/PHPMailer/src/Exception.php';
		require '/var/www/PHPMailer/src/PHPMailer.php';
		require '/var/www/PHPMailer/src/SMTP.php';

		use PHPMailer\PHPMailer\PHPMailer;
		use PHPMailer\PHPMailer\Exception;

		$mail = new PHPMailer(true);
		$from = $_POST['from'];
		$smtp_server = $_POST['smtp_server'];
		$port = $_POST['port'];
		$pass = $_POST['pass'];
		$to = $_POST['to'];
		$subject = $_POST['subject'];
		$message = $_POST['mensaje'];
		$file_name=$_FILES['archivo']['name'];
		$file_tmp=$_FILES['archivo']['tmp_name'];
		try{
					$mail->SMTPDebug = 2;
					$mail->isSMTP(); 
					$mail->Host = $smtp_server;
					$mail->SMTPAuth = true;
					$mail->Username = $from;
					$mail->Password = $pass;
					$mail->SMTPSecure = 'tls';
					$mail->Port = $port;

					$mail->setFrom($from);
					$mail->addAddress($to);
					$mail->Subject = $subject;
					$mail->Body = $message;
					if(file_exists($file_tmp))
						$mail->AddAttachment($file_tmp,$file_name);
					ob_start();
					$mail->send();
					$msg_salida = $mail->send();
					$estilo = "background-color:blue; color: white;";
		}
		catch(Exception $e){
			$estilo = "background-color:red; color: white;";
			$msg_salida = $mail->ErrorInfo;
		}

?>

<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="UTF-8">
		<title>CSI/UNAM-CERT</title>
		<link rel="stylesheet" href="style.css">
		<link rel="shortcut icon" href="https://132.247.146.130/loginApp/img/dsc.ico" type="image/x-icon">
	</head>

	<body>
		<div id="divError" style="<?php echo $estilo;?>">
				<p><?php echo $msg_salida;?></p>
		</div>
		<div class="form">
			<h2>Enviar correo electronico</h2>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
				
				<input class="campo" type="text" name="from" placeholder="De: " value="bec13g@gmail.com">
				<input class="campo" type="text" name="smtp_server" placeholder="Servidor smtp" value="smtp.gmail.com">
				<input class="campo" type="text" name="port" placeholder="Puerto" value="587">
				<input class="campo" type="password" name="pass" placeholder="Contraseña" value="hola123.,">
				<input class="campo" type="text" name="to" placeholder="Para: " value="bec13g@gmail.com">
				<input class="campo" type="text" name="subject" placeholder="Asunto: " value="Prueba">
				<input type="file" name="archivo"/>
				<textarea class="campo" name="mensaje" placeholder="Mensaje" rows="6">Correo de prueba</textarea>
				<div id="formsubmitbutton">
					<input class="boton" type="submit" name="sendEmail" value="enviar">
				</div>
			</form>
		</div>


</body></html>
