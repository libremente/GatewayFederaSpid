<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use Base64Url\Base64Url;


// PAGINA DI DEFAULT PER IL CONSUMO DELLE CREDENZIALI
$landingPage = "http://pmlab.comune.rimini.it/federa/cli-landing.php";


echo "<pre>";

echo "<h1>Client test gateway</h1>";
echo "<br>";
echo "Url landingPage:" . $landingPage;
echo "<br>";
echo "<h2>Test cifratura</h2>";


$ts = date("YmdHis", time() - date("Z"));


$ts = $ts . ";" . $landingPage;


$fp=fopen("./cli_certs/private.pem","r") or die('ERROR: private certificate not found!');
$private_key_string=fread($fp,8192);
fclose($fp);

echo $private_key_string;
echo "<br>";

openssl_private_encrypt($ts,$ts_crypted,$private_key_string);

echo $ts;
echo "<br>";

// echo $ts_crypted;echo "<br>";

// $b64_ts_crypted =  base64_encode($ts_crypted);
$b64_ts_crypted =  Base64Url::encode($ts_crypted);
echo $b64_ts_crypted; echo "<br>";
					

// $ts_crypted_out = base64_decode($b64_ts_crypted);
$ts_crypted_out = Base64Url::decode($b64_ts_crypted);

$fp=fopen("./cli_certs/public.crt","r") or die('ERROR: public certificate not found!');
$public_key_string=fread($fp,8192);
fclose($fp);

echo $public_key_string;
echo "<br>";


openssl_public_decrypt($ts_crypted_out, $ts_out, $public_key_string);
echo $ts_out;
echo "<br>";


?>

<h1>Federa Test</h1>
<h1><a href="https://pmlab.comune.rimini.it/federa/auth.php?appdemo=<?php echo $b64_ts_crypted ?>">login</a></h1>
<br/>
<h1><a href="https://pmlab.comune.rimini.it/federa/metadata/">metadata</a></h1>
