
<?php
// These lines are mandatory.
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;

// Check for mobile environment.
if ($detect->isMobile() || $detect->isTablet()) {

	if ($detect->isMobile()) {
		header( 'Location: mobile_index.php' ) ;
		exit;

	}
	if($detect->isTablet()){
		header( 'Location: mobile_index.php' ) ;
		exit;

	}
}

require_once 'includes/connection.php';

$zipCode = $_GET['Zip_Code'];

if (!empty($zipCode)) {
	$sqlCode = <<<query
SELECT `zip_codes`.`zip_code`, `zip_codes`.`latitude`, `zip_codes`.`longitude`
  FROM `zip_codes`
  WHERE `zip_codes`.`zip_code` = :searchCode;
query;

	try {
		$database = db_connect();
		$sql = $database->prepare($sqlCode);

		$sql->bindParam(':searchCode', $zipCode, PDO::PARAM_INT);

		$sql->execute();
	} catch (Exception $ex) {

	}

	if ($sql->rowCount() > 0) {
		$dataText = '';
		$infoText = '';

		while (($data = $sql->fetch(PDO::FETCH_ASSOC))) {
			if ($dataText != '') {
				$dataText .= ',';
			}

			if ($infoText != '') {
				$infoText .= ',';
			}

			$dataText .= "['" . $data['zip_code'] . "', " . $data['latitude'] . ", " . $data['longitude'] . "]";
			$infoText .= "['<div class=\"info_content\"><div style=\"height:420px; width:640px;\"><iframe src=\"high-chart.php?zip=" . $data['zip_code'] . "\" height=\"99%\" width=\"100%\" scrolling=\"no\" frameborder=\"0\"></iframe></div></div>']";
		} // end while

		$dataText = "[" . $dataText . "]";
		$infoText = "[" . $infoText . "];";
	}
} // end if (!empty($zipCode))
else {
	
	$sqlCode = <<<query
SELECT `students`.`zip`, `zip_codes`.`zip_code`, `zip_codes`.`longitude`, `zip_codes`.`latitude`, COUNT(`students`.`zip`) AS `ZipCount`
  FROM zip_codes
JOIN students
  ON students.zip = zip_codes.zip_code
GROUP BY `students`.`zip`
ORDER BY `ZipCount` DESC
LIMIT 10
query;
	
	try {
		$database = db_connect();

		$sql = $database->prepare($sqlCode);
		$sql->execute();
	} catch (Exception $ex) {

	}

	if ($sql->rowCount() > 0) {
		$dataText = '';
		$infoText = '';

		while (($data = $sql->fetch(PDO::FETCH_ASSOC))) {
			if ($dataText != '') {
				$dataText .= ',';
			}

			if ($infoText != '') {
				$infoText .= ',';
			}

			$dataText .= "['" . $data['zip_code'] . "', " . $data['latitude'] . ", " . $data['longitude'] . "]";
			$infoText .= "['<div class=\"info_content\"><div style=\"height:420px; width:640px;\"><iframe src=\"high-chart.php?zip=" . $data['zip_code'] . "\" height=\"99%\" width=\"100%\" scrolling=\"no\" frameborder=\"0\"></iframe></div></div>']";
		} // end while

		$dataText = "[" . $dataText . "]";
		$infoText = "[" . $infoText . "];";
	}
}

//$infoText = "[['<div class=\"info_content\"><div style=\"height:420px; width:640px;\"><iframe src=\"high-chart.php?zip=$zipCode\" height=\"99%\" width=\"100%\" scrolling=\"no\" frameborder=\"0\"></iframe></div></div>']];";


require_once 'header.php';
?>
<link href="css/style.css" rel="stylesheet"/>


			<div class="row content">
					<div class="col-xs-2">
						<form class="search" action='index.php' method='get'>
						<input class="textbox" type='text' name='Zip_Code' placeholder="Zip code"><br>
						<input class="search-button" type='submit' value='Search'>
						</form>
						<form action="index.php">
							<input class="search-button" type="submit" value="Reset Map">
						</form>

					</div>
					<div class="col-xs-10 text-center map">
						<div id="map_canvas" class="mapping"></div>
					</div>
				</div>

				<script>
			jQuery(function($) {
				// Asynchronously Load the map API
				var script = document.createElement('script');
				script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
				document.body.appendChild(script);
			});

			function initialize() {
				var map;
				var bounds = new google.maps.LatLngBounds();
				var mapOptions = {
					mapTypeId: 'roadmap',
					zoom: 8
				};

				// Display a map on the page
				map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
				map.setTilt(45);

				// Multiple Markers
				var markers =
				<?php
					echo $dataText;
				?>;

				// Info Window Content
				var infoWindowContent =
						<?php
						echo $infoText;
//echo "[['<div class=\"info_content\"><div style=\"height:420px; width:640px;\"><iframe src=\"high-chart.php?zip=$zipCode\" height=\"99%\" width=\"100%\" scrolling=\"no\" frameborder=\"0\"></iframe></div></div>']];";
						?>
				// Display multiple markers on a map
				var infoWindow = new google.maps.InfoWindow(), marker, i;

				// Loop through our array of markers & place each one on the map
				for( i = 0; i < markers.length; i++ ) {
					var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
					bounds.extend(position);
					marker = new google.maps.Marker({
						position: position,
						map: map,
						title: markers[i][0]
					});

					// Allow each marker to have an info window
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infoWindow.setContent(infoWindowContent[i][0]);
							infoWindow.open(map, marker);
						}
					})(marker, i));

					// Automatically center the map fitting all markers on the screen
					map.fitBounds(bounds);
				}

				// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
				var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
					this.setZoom(8);
					google.maps.event.removeListener(boundsListener);
				});

			}
			</script>
			<?php

			require_once 'footer.php';

