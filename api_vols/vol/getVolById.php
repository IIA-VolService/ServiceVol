<?php
//$postdata = file_get_contents("php://input");
require('../script.php');
$data = file_get_contents("../data.json");
$id = (int)$_GET['id'];
$token = $_GET['token'];
//if(isset($postdata)){
	//$request = json_decode($postdata);
    //$id = (int)$request->id;
try{
	$listToken = file_get_contents('../tokens.json');
	$listToken = json_decode($listToken);
	
	if(in_array($token, $listToken, true)){
		if(isset($id)){
			$data = json_decode($data);
			$query = array();
			$trouve = false;
			$compt = 0;
			while(!$trouve && count($data->Vols) > $compt) {
				# code...
				if($data->Vols[$compt]->id == $id){
					$trouve = true;
					array_push($query, $data->Vols[$compt]);
				}
				$compt++;
			}


			if(count($query) > 0)
				echo json_encode(array('code' => true, 'message' => "1 vol trouvé" ,  "nombre" => count($query), 'result' => $query));
			else
				echo json_encode(array('code' => false, 'message' => "Id inexsistant !"));
		}
	}
}catch(Exception $e){
	echo json_encode(array('code' => false, 'message' => $e));
}