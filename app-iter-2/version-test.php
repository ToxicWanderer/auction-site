<?php
session_start();

if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') { 
	// we're not running on https
	header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit (0);
}

$hash = '$2y$04$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
$test = crypt("password", $hash);
$pass = $test == $hash;

echo "Test for functionality of compat library: " . ($pass ? "Pass" : "Fail");
echo "\n";
