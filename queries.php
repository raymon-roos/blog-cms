<?php

declare(strict_types=1);

require_once('connection.php');

function findAllPosts($pdo): array | false
{
	return $pdo->query('SELECT * FROM `posts`;')->fetchAll() ?: false;
}

function query(callable $queryfunc): mixed
{
	try {
		return $queryfunc(connectDB());
	} catch (\Throwable) {
		return false;
	}
}
