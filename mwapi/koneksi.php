<?php 
	define('DB_HOST', 'localhost');
	define('DB_USER', 'jakitran_ojol');
	define('DB_PASS', 'jakitrans95#@!');
	define('DB_NAME', 'jakitran_ojol');
	$konek = new Mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die(Mysqli_errno());

 ?>