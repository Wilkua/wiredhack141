<?php

require_once 'includes/connection.php';

$database = db_connect();

$sqlCode = <<<sql
INSERT INTO `zip_codes`
  (`zip_code`, `latitude`, `longitude`)
VALUES
  (:zip_code, :latitude, :longitude);
sql;

$data = file_get_contents('zip_code_database.csv');
$dataLines = explode(PHP_EOL, $data);
unset($dataLines[0]);

$lineNumber = 0;

foreach ($dataLines as $line) {
	$lineNumber++;

	$splitData = explode(',', $line);

	if (count($splitData) != 3) {
		continue;
	}

	$zip_code = str_replace('"', '', $splitData[0]);
	$latitude = str_replace('"', '', $splitData[1]);
	$longitude = str_replace('"', '', $splitData[2]);

	try {
		$sql = $database->prepare($sqlCode);

		$sql->bindParam(':zip_code', $zip_code, PDO::PARAM_STR);
		$sql->bindParam(':latitude', $latitude, PDO::PARAM_STR);
		$sql->bindParam(':longitude', $longitude, PDO::PARAM_INT);

		$sql->execute();
	}
	catch (PDOException $ex) {
		echo 'Error inserting data into table: ' . $ex->getMessage() . '<br />';
		echo 'File: ' . $fileName . '<br />';
		echo 'Line: ' . $lineNumber . '<br />';
	}
}
