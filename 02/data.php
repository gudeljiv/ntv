<?php

$dsn = "mysql:host=mariadb;dbname=ntv";
$user = "user";
$passwd = "pass";
$pdo = new PDO($dsn, $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

$date = (isset($_GET["d"]) && validateDate($_GET["d"])) ? $_GET["d"] : date("Y-m-d");

$query = "	SELECT * FROM ntv.county c 
			LEFT JOIN ntv.county_stats cs ON c.county_id = cs.county_id
			WHERE cs.stats_date = '".$date."';
";
$sql = $pdo->query($query);

$stmt = $sql->fetchAll(PDO::FETCH_OBJ);

$response = new stdClass;
$response->data = $stmt;
$response->date = date("d.m.Y", strtotime($date));
$response->dates = date("Ymd", strtotime($date));
$response->validate = validateDate($date);


if(!$response->validate){
	header("HTTP/1.0 400 Bad Request");
	// echo json_encode($response, JSON_NUMERIC_CHECK);
	exit;
} 
echo json_encode($response, JSON_NUMERIC_CHECK);



function validateDate($date, $format = 'Ymd'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}