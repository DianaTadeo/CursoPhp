<?php
			require_once('display_error.php');
			require_once '/var/www/google-api-php-client-2.2.2/vendor/autoload.php'; 
			if(!session_id()){
					session_start();
			}

			if(isset($_SESSION["username"]) || isset($_SESSION["access_token"]))
				header("Location: ingresar.php");
			$clientId = '1040657052818-gpr2r9vpeph9du1p2egjltn9vgoqns41.apps.googleusercontent.com';
			$clientSecret = '{"web":{"client_id":"1040657052818-gpr2r9vpeph9du1p2egjltn9vgoqns41.apps.googleusercontent.com","project_id":"oauth-225400","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://www.googleapis.com/oauth2/v3/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs"}}';  // aqui va la cadena que viene en el archivo json de tus credenciales
			$redirectURL = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

			$client = new Google_Client();
			$client->setApplicationName("Cliente web 1");
			$client->setClientId($clientId);
			$client->setClientSecret($clientSecret);
			$client->setRedirectUri($redirectURL);
			$client->setScopes(array(Google_Service_Plus::PLUS_ME));
			$plus = new Google_Service_Plus($client);

			if (isset($_GET['code'])){
				if(strval($_SESSION['state']) !== strval($_GET['state'])) {
					echo 'El estado de la sesion no coincide';
					exit(1);
				}
				$client->authenticate($_GET['code']);
				$_SESSION['access_token'] = $client->getAccessToken();
				$_SESSION['info_user'] = $plus->people->get('me');
				header('Location: ingresar.php');
			}

			if (isset($_SESSION['access_token'])){
				$client->setAccessToken($_SESSION['access_token']);
			}
			$state = mt_Rand();
			$client->setState($state);
			$_SESSION['state'] = $state;
			$hReflogin = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html>
	<body>
		<form method="POST" action="ingresar.php">
			<p>Usuario: </p>
			<input type="text" name="usuario">
 			<p>Contraseña: </p>
			<input type="text" name="pass">
			<br />
			<input type="checkbox" name="reg" value="si"/> Registrar y entrar		
			<br />
			<br />
			<input type="submit" name="entrar" value="ingresar">
		</form>
		<br /><p>O ingresa con:</p><br />
		<div>
			<a href="<?php echo $hReflogin;?>">Aqui</a>
		</div>
	</body>
</html>
