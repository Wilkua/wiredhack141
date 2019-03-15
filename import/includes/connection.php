<?php

function db_connect() {
	$dbHost = '';
	//$dbHost = 'localhost';
	$dbName = '';
	$username = '';
	$password = '';
	
	//$username = 'root';
	//$password = '';
	
	try {
		$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $username, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
	}
	catch (PDOException $e) {
		throw new RuntimeException("Cannot connect to database: " . $e->getMessage());
	}

	return $db;
}
