<?php

require_once 'includes/connection.php';

if (filter_input(INPUT_POST, 'submitted') !== null) {
	
	if ($_FILES['csvfile']['error'] > 0) {
		echo 'Error with uploaded file.';
	}
	else {
		$database = db_connect();

		$sqlCode = <<<sql
INSERT INTO `students`
  (`academic_year`,
   `gender`,
   `county`,
   `high_school`,
   `high_school_grad_year`,
   `enrollment_status`,
   `race`,
   `age`,
   `div`,
   `dept`,
   `program`,
   `level`,
   `registration_status`,
   `address`,
   `city`,
   `zip`,
   `student_number`)
VALUES
  (:academic_year,
   :gender,
   :county,
   :high_school,
   :high_school_grad_year,
   :enrollment_status,
   :race,
   :age,
   :div,
   :dept,
   :program,
   :level,
   :registration_status,
   :address,
   :city,
   :zip,
   :student_number);
sql;

		$data = file_get_contents($_FILES['csvfile']['tmp_name']);
		$dataLines = explode(PHP_EOL, $data);
		unset($dataLines[0]);

		$lineNumber = 0;

		foreach ($dataLines as $line) {
			$lineNumber++;

			$splitData = explode(',', $line);

			if (count($splitData) != 17) {
				continue;
			}

			$academic_year = str_replace('"', '', $splitData[0]);
			$gender = str_replace('"', '', $splitData[1]);
			$county = str_replace('"', '', $splitData[2]);
			$high_school = str_replace('"', '', $splitData[3]);
			$high_school_grad_year = str_replace('"', '', $splitData[4]);
			$enrollment_status = str_replace('"', '', $splitData[5]);
			$race = str_replace('"', '', $splitData[6]);
			$age = str_replace('"', '', $splitData[7]);
			$div = str_replace('"', '', $splitData[8]);
			$dept = str_replace('"', '', $splitData[9]);
			$program = str_replace('"', '', $splitData[10]);
			$level = str_replace('"', '', $splitData[11]);
			$registration_status = str_replace('"', '', $splitData[12]);
			$address = str_replace('"', '', $splitData[13]);
			$city = str_replace('"', '', $splitData[14]);
			$zip = str_replace('"', '', $splitData[15]);
			$student_number = str_replace('"', '', $splitData[16]);


			try {
				$sql = $database->prepare($sqlCode);

				$sql->bindParam(':academic_year', $academic_year, PDO::PARAM_STR);
				$sql->bindParam(':gender', $gender, PDO::PARAM_STR);
				$sql->bindParam(':county', $county, PDO::PARAM_STR);
				$sql->bindParam(':high_school', $high_school, PDO::PARAM_STR);
				$sql->bindParam(':high_school_grad_year', $high_school_grad_year, PDO::PARAM_STR);
				$sql->bindParam(':enrollment_status', $enrollment_status, PDO::PARAM_STR);
				$sql->bindParam(':race', $race, PDO::PARAM_STR);
				$sql->bindParam(':age', $age, PDO::PARAM_INT);
				$sql->bindParam(':div', $div, PDO::PARAM_STR);
				$sql->bindParam(':dept', $dept, PDO::PARAM_STR);
				$sql->bindParam(':program', $program, PDO::PARAM_STR);
				$sql->bindParam(':level', $level, PDO::PARAM_STR);
				$sql->bindParam(':registration_status', $registration_status, PDO::PARAM_STR);
				$sql->bindParam(':address', $address, PDO::PARAM_STR);
				$sql->bindParam(':city', $city, PDO::PARAM_STR);
				$sql->bindParam(':zip', $zip, PDO::PARAM_INT);
				$sql->bindParam(':student_number', $student_number, PDO::PARAM_STR);

				$sql->execute();
			}
			catch (PDOException $ex) {
				echo 'Error inserting data into table: ' . $ex->getMessage() . '<br />';
				echo 'File: ' . $fileName . '<br />';
				echo 'Line: ' . $lineNumber . '<br />';
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf8" />
		<title>Import Data</title>
	</head>
	<body>
		<form method="post" action="import_data.php" enctype="multipart/form-data">
			<input type="hidden" name="submitted" value="1" />
			<label for="csvfile">Upload a CSV file to insert data.</label><br />
			<input type="file" name="csvfile" /><br />
			<button name="submit" type="submit">Import data</button>
		</form>
	</body>
</html>