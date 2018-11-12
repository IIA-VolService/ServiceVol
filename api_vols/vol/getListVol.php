<?php
//
require('../script.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try{
	$data = file_get_contents("../data.json");
	$start = $_GET['start'];
	$end = $_GET['end'];
	$date = $_GET['date'];
	$nbPassager = (int)$_GET['nbPassager'];
	$classVol = $_GET['classVol'];
	$token = $_GET['token'];
}catch(Exeption $e){
	try{
		$postdata = file_get_contents("php://input");
		if(isset($postdata)){
			$data = file_get_contents("../data.json");
			$request = json_decode($postdata);
		    $start = $request->start;
			$end = $request->end;
			$date = $request->date;
			$nbPassager = (int)$request->nbPassager;
			$classVol = $request->classVol;
			$token = $request->token;
		}else{
			echo json_encode(array('code' => false, 'message' => $e));
		}
	}catch(Exception $e){
		echo json_encode(array('code' => false, 'message' => $e));
	}
}
$dateSauve = $date;
try{
	$listToken = file_get_contents('../tokens.json');
	$listToken = json_decode($listToken);
	
	if(in_array($token, $listToken, true)){
		if(isset($start) && isset($end) && isset($date) && isset($nbPassager) && isset($classVol)){
			$data = json_decode($data);
			$query = array();
			$message = "";

			foreach ($data->Vols as $key => $value) {
				# code...
				//$dateDepart = DateTime::createFromFormat('j/m/Y H:i', $value->dateDepart . " " . str_replace("h", ":", $value->heureDepart) ; 
				$node = array();
				$dateDepart = DateTime::createFromFormat('j-m-Y H:i:s', $value->depart); 
				$date = DateTime::createFromFormat('j-m-Y', $dateSauve);
				$date->setTime(00,00,00);
				if(trim(strtoupper($value->villeDepart)) == trim(strtoupper($start)) && trim(strtoupper($value->villeArrivee)) == trim(strtoupper($end)) && $date <= $dateDepart && $date->add(new DateInterval('P1D')) >= $dateDepart)
				{
					foreach ($value->reservations as $key2 => $value2) {
								# code...
						if($value2->nom == $classVol){
							if($value2->placeDisponible - $nbPassager > 0){
								$node = $value;
								$node->reservations = $value2;
								array_push($query, $node);
								$message = "Success";
							}else{
								$message = "il n'y a plus de place pour cette classe sur ce vols";
							}
						}
					}
				}
			}
			echo json_encode(array('code' => true, 'message' => $message ,  "nombre" => count($query), 'result' => $query));
		}else{
			echo json_encode(array('code' => false, 'message' => "il manque au moins l'un des champs"));
		}
	}else{
		echo json_encode(array('code' => false, 'message' => "Impossible de verifier l'authentification de l'utilisateur"));
	}
	
	
}catch(Exception $e){
	echo json_encode(array('code' => false, 'message' => "Erreur"));
}
//}