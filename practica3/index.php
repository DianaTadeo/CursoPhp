<?php
		$msg_salida = "mensaje";
		$estilo = "background-color:blue; color:white;";

		require '/var/www/PHPMailer/src/Exception.php';
		require '/var/www/PHPMailer/src/PHPMailer.php';
		require '/var/www/PHPMailer/src/SMTP.php';

		use PHPMailer\PHPMailer\PHPMailer;
		use PHPMailer\PHPMailer\Exception;

	class Mail{
	 	private $mail; 
		private $from;
		private $smtp_server;
		private $port;
		private $pass;
		private $to;
		private $subject;
		private $message;
		
		public function __construct(){
			$this->mail=new PHPMailer(true); 
			$this->from = $_POST['from'];
			$this->smtp_server = $_POST['smtp_server'];
			$this->port = $_POST['port'];
			$this->pass = $_POST['pass'];
			$this->to = $_POST['to'];
			$this->subject = $_POST['subject'];
			$this->message = $_POST['mensaje'];
		}
		
		public function enviar($archivo_temp, $archivo_nom){
			try{
				$this->mail->SMTPDebug = 2;
				$this->mail->isSMTP(); 
				$this->mail->Host = $this->smtp_server;
				$this->mail->SMTPAuth = true;
				$this->mail->Username = $this->from;
				$this->mail->Password = $this->pass;
				$this->mail->SMTPSecure = 'tls';
				$this->mail->Port = $this->port;
				$this->mail->setFrom($this->from);
				$this->mail->addAddress($this->to);
				$this->mail->Subject = $this->subject;
				$this->mail->Body = $this->message;
				if(file_exists($archivo_temp))
					$this->mail->AddAttachment($archivo_temp,$archivo_nom);
				ob_start();
				$this->mail->send();
				$msg_salida = $this->mail->send();
				$estilo = "background-color:blue; color: white;";
			}catch(Exception $e){
				$estilo = "background-color:red; color: white;";
				$msg_salida = $this->mail->ErrorInfo;
			}
			}
	}
	
	$nuevoMsg= new Mail();
	$nuevoMsg->enviar($_FILES['archivo']['tmp_name'],$_FILES['archivo']['name']);
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
