<?php
// These lines are mandatory.
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
?>
<?php
		// Check for mobile environment.
		if ($detect->isMobile() || $detect->isTablet()) {
			
			if ($detect->isMobile()) {
				// Your code here.
			}
			if($detect->isTablet()){
				// Your code here.
			}
		}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Map Hack</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link href="css/style.css" rel="stylesheet"/>
	<link href="js/bootstrap.min.js"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script>
		$(document).ready(function() {
			var bodyheight = $(window).height();
			$( '.mainContainer' ).css({
				height:bodyheight+"px"
			});
			$( '.header' ).css({
				height:(bodyheight*.2)+"px"
			});
			$( '.content' ).css({
				height:(bodyheight)+"px"
			});
		});
		$( window ).resize(function() {
			var bodyheight = $(window).height();
		
			$( '.mainContainer' ).css({
				height:bodyheight+"px"
			});
			
			$( '.content' ).css({
				height:(bodyheight)+"px"
			});
		});
	</script>
	
  </head>
  <body>
	<?php	
    require_once 'includes/connection.php';

$zipCode = $_GET['Zip_Code'];

if (!empty($zipCode)) {
	$sqlCode = <<<query
SELECT zip_codes.zip_code, zip_codes.latitude, zip_codes.longitude
  FROM zip_codes
  WHERE zip_codes.zip_code = :searchCode;
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
SELECT DISTINCT zip_codes.zip_code, zip_codes.longitude, zip_codes.latitude
  FROM zip_codes;
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


?>
<link href="css/style.css" rel="stylesheet"/>


			<div class="row content">
					<div class="col-xs-12 text-center map">
						<div style="position:absolute; z-index:1; border:1px solid; border-radius:25px; background-color:white; width:300px; padding:10px; -moz-box-shadow:8px 8px 8px rgba(0,0,0,0.5); -webkit-box-shadow:8px 8px 8px rgba(0,0,0,0.5);box-shadow:8px 8px 8px rgba(0,0,0,0.5);">
						<form class="mobilesearch" action='mobile_index.php' method='get'>
						<input class="mobiletextbox" type='text' name='Zip_Code' placeholder="Zip code"><br>
						<input class="mobilesearch-button pull-left" style="margin-left:10%;" type='submit' value='Search'>
						</form>
						<form action="mobile_index.php">
							<input class="mobilesearch-button pull-right" style="margin-right:10%;" type="submit" value="Reset Map">
						</form>
						</div>
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

	
	</div> <!-- end cotainer -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>