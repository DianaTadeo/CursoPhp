<?php
	require_once('display_error.php');
	require_once('/var/www/google-api-php-client-2.2.2/vendor/autoload.php');  //descarga y descomprime https://github.com/googleapis/google-api-php-client/releases/download/v2.2.2/google-api-php-client-2.2.2.zip
	//toda la carpeta que se descomprimió la mueves a /var/www/
	if(!session_id()){
		session_start();
	}
	if(isset($_SESSION["username"]) || isset($_SESSION["access_token"]))
		header("Location: ingresar.php");
	//En tu /etc/hosts agregas en 127.0.0.1 aquitunombredemaquina test.13g.com ->esto lo usaras cuando crees tus credenciales después
	/*crear una cuenta de gmail y luego en https://console.developers.google.com creas un nuevo proyecto con el nombre que quieras (nosotros le
	pusimos OAuth) y buscar en la barra inmediata superior "google+", le das agregar o agregar, algo así jaja y luego en el menú de la izquierda en
	credenciales, le das agregar y le dejas el nombre de "cliente web 1", tipo: web application y te pide un nombre de dominio me parece
	casi hasta abajo: test.13g.com, luego se crean tus crdenciales y le das en editar y agregas hasta abajo: test.13g.com/index.php */
	$clientId = '1040657052818-gpr2r9vpeph9du1p2egjltn9vgoqns41.apps.googleusercontent.com';  // aqui va la cadena que viene en el archivo json que se descarga
	$clientSecret = '7moDflFpXS6InmNBpf-Kgg8d';  // aqui va la cadena que viene en el archivo json de tus credenciales
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
	$hReflogin = $client->createAuthUrl(); //
/*			echo "<pre>";
			var_dump($_SESSION);
			echo "</pre>";*/
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
			<br />
			<input type="submit" name="entrar" value="ingresar">
		</form>
		<br /><p>O ingresa con:</p><br />
		<div>
			<a href="<?php echo $hReflogin;?>">Aqui</a>
		</div>
	</body>
</html>
