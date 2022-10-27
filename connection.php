<?php

declare(strict_types=1);

class DBService
{
	private static PDO $pdo;

	public static function connectDB()
	{
		return self::$pdo
			?? (new self())::$pdo;
	}

	private function __construct()
	{
		$newPDO = new PDO(
			'mysql:host=127.0.0.1;dbname=foodblog',
			'bit_academy',
			'bit_academy',
		);
		$newPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		return self::$pdo = $newPDO;
	}
}
