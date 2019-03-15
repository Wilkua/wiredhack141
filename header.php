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
				height:(bodyheight*.1)+"px"
			});
			$( '.content' ).css({
				height:(bodyheight*.9)+"px"
			});
		});
		$( window ).resize(function() {
			var bodyheight = $(window).height();
		
			$( '.mainContainer' ).css({
				height:bodyheight+"px"
			});
			$( '.header' ).css({
				height:(bodyheight*.1)+"px"
			});
			$( '.content' ).css({
				height:(bodyheight*.9)+"px"
			});
		});
	</script>
	
  </head>
  <body>
		
    <div class="container-fluid mainContainer">
	<div class="row header">
		<div class="col-xs-4">	
		</div>
		<div class="col-xs-4 text-center">	
			<h1>Map Hack</h1> 
			<small>High School Data Map</small>
		</div>
		<div class="col-xs-4">	
		</div>
	</div>