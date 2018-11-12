<?php
require('../script.php');
$data = file_get_contents("../data.json");
$data = json_decode($data);
$response = array("result" => array('code' => "sucess", "result" => $data, "nombre" => count($data)));
echo json_encode($response);
