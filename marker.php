<?php

require_once 'includes/connection.php';


function Markers($zipCode = '') {
	if (!empty($zipCode)) {
		$sqlCode = <<<query
SELECT zip_codes.latitude, zip_codes.longitude
  FROM zip_codes
  WHERE zip_codes.zip_code = :searchCode;
query;
		
		try {
			$database = db_connect();
			$sql = $database->prepare($sqlCode);
			
			$sql->bindParam(':searchCode', $zipCode, PDO::PARAM_INT);
			
			$sql->execute();
		} catch (Exception $ex) {
			echo "[['nothing', 0.0, 0.0]]";
		}
		
		if ($sql->rowCount() > 0) {
			$dataText = '';
			
			while (($data = $sql->fetch(PDO::FETCH_ASSOC))) {
				if ($dataText != '') {
					$dataText .= ',';
				}
				
				$dataText .= "['" . $data['zip_code'] . "', " . $data['latitude'] . ", " . $data['longitude'] . "]";
			} // end while
			
			echo "[$dataText]";
		}
	} // end if (!empty($zipCode))
	else {
		$sqlCode = <<<query
SELECT zip_codes.longitude, zip_codes.latitude
  FROM zip_codes;
query;
		
		try {
			$database = db_connect();
			
			$sql = $database->prepare($sqlCode);
			$sql->execute();
		} catch (Exception $ex) {
			echo "[['nothing', 0.0, 0.0]]";
		}
		
		if ($sql->rowCount() > 0) {
			$dataText = '';
			
			while (($data = $sql->fetch(PDO::FETCH_ASSOC))) {
				if ($dataText != '') {
					$dataText .= ',';
				}
				
				$dataText .= "['" . $data['zip_code'] . "', " . $data['latitude'] . ", " . $data['longitude'] . "]";
			} // end while
			
			echo "[$dataText]";
		}
	}
} // end function


/*
$searchZipCode = filter_input(INPUT_GET, 'Zip_Code');

if ($searchZipCode !== null) {
	
	$sqlCode = <<<query
SELECT students.zip, zip_code.latitude, zip_code.longitude
  FROM students
  WHERE students.zip = ?
JOIN zip_code
  ON students.zip = zip_code.zip_code;
query;

	try {
		$database = db_connect();

		$sql = $database->prepare($sqlCode);

		$sql->execute(array($searchZipCode));
	}
	catch (PDOException $ex) {
		//echo "['South Carolina', 34.0, -81.0]";
		echo '';
	}

	if($sql->rowCount() > 0) {

		while ($data = $sql->fetch(PDO::FETCH_ASSOC)) {
			echo "['" . $data['zip'] . "', " . $data['latitude'] .", " . $data['longitude']. "]";
		}
	}
	else {
		echo "['South Carolina', 34.0, -81.0]";
	}
}
else {
	// Populates all data
	$sqlCode = <<<sql
SELECT students.zip, zip_code.latitude, zip_code.longitude
	FROM students
JOIN zip_code
	ON zip_code.zip_code = students.zip;
sql;
	
	try {
		$database = db_connect();
		
		$sql = $database->prepare($sqlCode);
		
		$sql->execute();
	} catch (Exception $ex) {
		
	}

 * }
 */