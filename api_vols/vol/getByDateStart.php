<?php
//$postdata = file_get_contents("php://input");
require('../script.php');
$data = file_get_contents("../data.json");
$start = $_GET['start'];
$token = $_GET['token'];
$date = $_GET['date'];
//if(isset($postdata)){
	//$request = json_decode($postdata);
    //$start = (int)$request->start;
$dateSauve = $date;
try{
	$listToken = file_get_contents('../tokens.json');
	$listToken = json_decode($listToken);
	if(isset($start) && isset($date)){
		$data = json_decode($data);
		$query = array();
		foreach ($data->Vols as $key => $value) {
			# code...
			$dateDepart = DateTime::createFromFormat('j-m-Y H:i:s', $value->depart); 
			$date = DateTime::createFromFormat('j-m-Y', $dateSauve);
			$date->setTime(00,00,00);
			if(trim(strtoupper($value->villeDepart)) == trim(strtoupper($start)) && $date <= $dateDepart && $date->add(new DateInterval('P1D')) >= $dateDepart){
				array_push($query, $value);
			}
		}
		echo json_encode(array('code' => true, 'result' => $query , 'nombre' => count($query)));
	}else{
		echo json_encode(array('code' => false, 'message' => 'Impossible de trouver la ville de depart'));
	}
}catch(Exception $e){
	echo json_encode(array('code' => false, 'message' => $e));
}
	
//}


