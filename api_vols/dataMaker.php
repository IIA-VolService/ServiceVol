<?php
$data = file_get_contents("AirPort.json");
$data = json_decode($data);
$vols = file_get_contents("data.json");
$vols = json_decode($vols);
$airlines = file_get_contents("airlines.json");
$airlines = json_decode($airlines);
if(!isset($vols)){
	$vols = array();
}
$ecart1 = rand(10, 365);
$ecart2 = rand(15, 40);
for ($i=4; $i < 1000; $i+=2) { 
	# code...
	$date = DateTime::createFromFormat('j-m-Y', "09-11-2018");
	$date->add(new DateInterval('P'.$ecart1.'D'));
	$indexDepart = rand(0,3884);
	$indexArriver = rand(0,3884);
	$heureDepart = rand(0,23);
	$placeDisponible = rand(100, 500);
	$query = array(
		"id" => $i,
		"villeDepart" => $data[$indexDepart]->city,
		"codeAeroportDepart" => $data[$indexDepart]->code,
		"villeArrivee" => $data[$indexArriver]->city,
		"codeAeroportArrivee" => $data[$indexArriver]->code,
		"compagnie" => $airlines->Airlines[rand(0, 805)]->name,
		"depart" => date_format($date->add(new DateInterval('PT'.$heureDepart.'H')), "j-m-Y H:i:s" ),
		"arrivee" => date_format($date->add(new DateInterval('PT'.rand(0,14).'H')), "j-m-Y H:i:s" ),
		"reservations" => 
		array(
			array("nom" => "econmique", "placeDisponible" => intdiv($placeDisponible, 2), "placesReservees" => 0, "prix" => rand(200, 400)), 
			array("nom" => "premium", "placeDisponible" => intdiv($placeDisponible, 3), "placesReservees" => 0, "prix" => rand(400, 600)),
			array("nom" => "business", "placeDisponible" => $placeDisponible - (intdiv($placeDisponible, 2) + intdiv($placeDisponible, 3)), "placesReservees" => 0, "prix" => rand(700, 1500))
			)
	);
	array_push($vols->Vols, $query); 
	$query = array(
		"id" => ($i + 1),
		"villeDepart" => $data[$indexArriver]->city,
		"codeAeroportDepart" => $data[$indexArriver]->code,
		"villeArrivee" => $data[$indexDepart]->city,
		"codeAeroportArrivee" => $data[$indexDepart]->code,
		"compagnie" => $airlines->Airlines[rand(0, 805)]->name,
		"depart" => date_format($date->add(new DateInterval('P'.$ecart2.'DT'.$heureDepart.'H')), "j-m-Y H:i:s" ),
		"arrivee" => date_format($date->add(new DateInterval('PT'.rand(0,14).'H')), "j-m-Y H:i:s" ),
		"reservations" => 
		array(
			array("nom" => "econmique", "placeDisponible" => intdiv($placeDisponible, 2), "placesReservees" => 0, "prix" => rand(200, 400)), 
			array("nom" => "premium", "placeDisponible" => intdiv($placeDisponible, 3), "placesReservees" => 0, "prix" => rand(400, 600)),
			array("nom" => "business", "placeDisponible" => $placeDisponible -(intdiv($placeDisponible, 2) + intdiv($placeDisponible, 3)), "placesReservees" => 0, "prix" => rand(700, 1500))
			)
	);
	array_push($vols->Vols, $query); 
}

$jsonData = json_encode($vols);
file_put_contents('data.json', $jsonData);