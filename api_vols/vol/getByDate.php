<?php
//$postdata = file_get_contents("php://input");
$data = file_get_contents("../data.json");
$date = $_GET['date'];
$data = json_decode($data);
$query = array();
//if(isset($postdata)){
	//$request = json_decode($postdata);
	//$date = $request->date;
	foreach ($data->Vols as $key => $value) {
		# code...
		$dateDepart = DateTime::createFromFormat('j/m/Y', $value->dateDepart); 
		$date = DateTime::createFromFormat('j/m/Y', $date);

		if($date == $dateDepart){
			array_push($query, $value);
		}
	}
//}
echo json_encode($query);