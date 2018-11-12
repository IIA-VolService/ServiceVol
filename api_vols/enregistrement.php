<?php
require('script.php');
$lstusers = file_get_contents("user.json");
$lstusers = json_decode($lstusers);
$username = $_GET["username"];
$password = $_GET['password'];
$nom = $_GET['nom'];
$prenom = $_GET['prenom'];
if(isset($username) && isset($password) && isset($prenom) && isset($nom)){
	$compt = 0;
	$trouve = false;
	while(!$trouve && count($lstusers->Users) > $compt){
		if($lstusers->Users[$compt]->username == $username && $lstusers->Users[$compt]->password == $password){
			$trouve = true;
		}
		$compt++;
	}
	if($trouve){
		echo json_encode(array('code' => false, 'message' => "L'utilisateur existe deja"));
	}else{
		function max_attribute_in_array($array, $prop) {
		    return max(
		    	array_map(
		    		function($o) use($prop) {
                        return $o->$prop;
                     },$array)
                     
		    );
		}
		$user = array(
			'id' => (max_attribute_in_array($lstusers->Users, 'id') + 1), 
			'nom' => $nom, 
			'prenom' => $prenom,
			'username' => $username,
			'password' => $password
		);
		array_push($lstusers->Users, $user);
		file_put_contents('user.json', json_encode($lstusers));
		echo json_encode(array('code' => true , 'message' => "Inscription réussite"));
	}
}
