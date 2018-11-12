<?php
//$postdata = file_get_contents("php://input");
require('../script.php');
$data = file_get_contents("../data.json");
$start = $_GET['start'];
$end = $_GET['end'];
$token = $_GET['token'];
//if(isset($postdata)){
	//$request = json_decode($postdata);
    //$start = (int)$request->start;
try{
	$listToken = file_get_contents('../tokens.json');
	$listToken = json_decode($listToken);
	if(isset($start) && isset($end)){
		$data = json_decode($data);
		$query = array();
		foreach ($data->Vols as $key => $value) {
			# code...
			if(trim(strtoupper($value->villeDepart)) == trim(strtoupper($start)) && trim(strtoupper($value->villeArrivee)) == trim(strtoupper($end)))
				array_push($query, $value);
		}
		echo json_encode(array('code' => true, 'result' => $query , 'nombre' => count($query)));
	}else{
		echo json_encode(array('code' => false, 'message' => 'Impossible de trouver la ville de depart'));
	}
}catch(Exception $e){
	echo json_encode(array('code' => false, 'message' => $e));
}
	
//}


