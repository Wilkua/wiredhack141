<?php

require_once 'includes/connection.php';

//$zipCode = $_GET['zip'];
$zipCode = filter_input(INPUT_GET, 'zip');

$sqlCode = <<<query
SELECT students.high_school
  FROM students
  WHERE students.zip = :zipCode;
query;

$schoolInfo = array();

try {
	$database = db_connect();
	$sql = $database->prepare($sqlCode);
	$sql->bindParam(':zipCode', $zipCode, PDO::PARAM_INT);
	$sql->execute();
} catch (Exception $ex) {

}

while (($data = $sql->fetch(PDO::FETCH_ASSOC))) {
	$data['high_school'] = addslashes($data['high_school']);

	if (!isset($schoolInfo[$data['high_school']])) {
		$schoolInfo[$data['high_school']] = 1;
	}
	else {
		$schoolInfo[$data['high_school']]++;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<link href="css/style.css" rel="stylesheet"/>
<script>
$(document).ready(function () {
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: <?php echo "\"High schools in $zipCode\""; ?>
            //text: "High schools in " + <?php echo '"' . $zipCode . '"'; ?> //Title of the high chart (main header)
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.y:.1f} Students</b>'//Don't worry about it.
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y:.1f} Students',//Points to data
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'//Theme (no require to change
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'High school students',
            data: [//First bracket stays. it WILL double bracket
				<?php
				$outtext = '';

				foreach ($schoolInfo as $school => $studentCount) {
					if ($outtext != '') {
						$outtext .= ',';
					}
					$outtext .= "['" . $school . "', " . $studentCount . ']';
				}

				echo $outtext;
				?>
            ]//End of data
        }]
    });
});
</script>

</head>
<body bgcolor="white">
	<div id="container"></div>
</body>
</html>
