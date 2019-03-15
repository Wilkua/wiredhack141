<?php

require_once 'includes/connection.php';

$database = db_connect();

if ($database) {
	echo "Success.";
}