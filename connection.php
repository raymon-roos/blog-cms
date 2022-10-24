<?php

function connectDB(): PDO | false
{
	$host = '127.0.0.1';
	$db = 'foodblog';
	$user = 'bit_academy';
	$pass = 'bit_academy';
	$dsn = "mysql:host=$host;dbname=$db";

	try {
		$pdo = new PDO($dsn, $user, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $pdo;
	} catch (Throwable) {
		return false;
	}
}
