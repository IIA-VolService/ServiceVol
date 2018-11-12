<?php
use ReallySimpleJWT\Token;
require('script.php');
$data = file_get_contents("user.json");
$username = $_GET['username'];
$password = $_GET['password'];

$data = json_decode($data);
$trouve = false;
$compt = 0;
$id = -1;
while(!$trouve && count($data->Users) > $compt) {
	if($data->Users[$compt]->username == $username && $data->Users[$compt]->password == $password) {
		$trouve = true;
		$id = $data->Users[$compt]->id;
	}
	$compt++;
}
if($trouve){
	// Create token header as a JSON string
	$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

	// Create token payload as a JSON string
	$payload = json_encode(['user_id' => $id]);

	// Encode Header to Base64Url String
	$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

	// Encode Payload to Base64Url String
	$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

	// Create Signature Hash
	$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'BDC', true);

	// Encode Signature to Base64Url String
	$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

	// Create JWT
	$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

	//echo json_encode(array('code' => "success" , 'token' => $jwt));
	
	try{
		$inp = file_get_contents('tokens.json');
		$tempArray = json_decode($inp);
		if(!isset($tempArray)){
			$tempArray = array();
		}
		if(!in_array($jwt, $tempArray)){
			array_push($tempArray, $jwt);
			file_put_contents('tokens.json', json_encode($tempArray));
			echo json_encode(array('code' => true , 'message' => "Authentification réussite" , "token" => $jwt));
		}
		echo json_encode(array('code' => true , 'message' => "Authentification réussite" , "token" => $jwt));
	}catch(Execption $e){
		echo json_encode(array('code' => false , 'message' => $e));
	}
}else{
	echo json_encode(array('code' => false , 'message' => "Impossible de retrouver l'utilisateur"));
}


