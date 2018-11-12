<?php
require('../script.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = file_get_contents("../reservations.json");
$vols = file_get_contents("../data.json");


try{
	try{
		$nom = $_GET['nom'];
		$prenom = $_GET['prenom'];
		$idVol = (int)$_GET['idVol'];
		$classVol = $_GET['classVol'];
		$message = "";
		$token = $_GET['token'];
	}catch(Exeption $e){
		try{
			$postdata = file_get_contents("php://input");
			if(isset($postdata)){
				$request = json_decode($postdata);
			    $start = $request->nom;
				$end = $request->prenom;
				$date = (int)$request->idVol;
				$nbPassager = $request->classVol;
				$message = "";
				$token = $request->token;
			}else{
				echo json_encode(array('code' => false, 'message' => $e));
				die;
			}
		}catch(Exception $e){
			echo json_encode(array('code' => false, 'message' => $e));
			die;
		}
	}
	$listToken = file_get_contents('../tokens.json');
	$listToken = json_decode($listToken);
	if(in_array($token, $listToken, true)){
		if(isset($nom) && isset($prenom) && isset($idVol) && isset($classVol))
		{
			$reservation = array(
				'nom' => $nom,
				'prenom' => $prenom,
				'idVol' => $idVol,
				'classVol' => $classVol
				);
			
			try{
				$data = json_decode($data, true);
				if(!isset($data)){
					$data = array();
				}
				if(!in_array($reservation, $data, true)){
					array_push($data, $reservation);
					$jsonData = json_encode($data);
					file_put_contents('../reservations.json', $jsonData);

					$vols = json_decode($vols);
					$trouve = false;
					$compt = 0;
					while(!$trouve && count($vols->Vols) > $compt){
						if($vols->Vols[$compt]->id == $idVol){
							$trouve = true;
							foreach ($vols->Vols[$compt]->reservations as $key => $value) {
								# code...
								if($value->nom == $classVol){
									if($value->placeDisponible > 0){
										$value->placeDisponible--;
										$value->placesReservees++;
										$message = "Reservation Ajoute";
									}else{
										$message = "il n'y a plus de place pour cette classe sur ce vols";
									}
								}
							}
						}
						$compt++;
					}
					$jsonData = json_encode($vols);
					file_put_contents('../data.json', $jsonData);
					$code = true;
					echo json_encode(array("success" => $code , "message" => $message));
				}else{
					echo json_encode(array("success" => false , "message" => "Cette personne est deja sur ce trajet"));
				}
			}catch(Exception $e){
				echo json_encode(array("success" => false , "message" => "Erreur"));
			}
		}else{
			echo json_encode(array("success" => false , "message" => "il manque au moins l'un des champs"));
		}
	}else{
		echo json_encode(array('code' => false, 'message' => "Impossible de verifier l'authentification de l'utilisateur"));
	}
}catch(Exception $e){
	echo json_encode(array("success" => false , "message" => $e));
}

