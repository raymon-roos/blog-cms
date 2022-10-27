<?php

declare(strict_types=1);

class DBService
{
	private static PDO $pdo;

	public static function connectDB()
	{
		if (isset(self::$pdo)) {
			return self::$pdo;
		}

		$newPDO = new PDO(
			'mysql:host=127.0.0.1;dbname=foodblog',
			'bit_academy',
			'bit_academy',
		);
		$newPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		self::$pdo = $newPDO;

		return self::$pdo;
	}

	private function __construct()
	{
	}
}
