<?php

declare(strict_types=1);

require_once('connection.php');

function findAllPosts(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT `title`, `date`, `img_url`, `author`, `content` FROM `posts`
		ORDER BY `date` DESC;'
	)->fetchAll();
}

function submitPost(PDO $pdo, array $data): bool
{
	return $pdo->prepare(
		"INSERT INTO `posts` (`title`, `img_url`, `content`, `author`) 
		VALUES (:title, :img_url, :content, 'Raymon');"
	)->execute($data);
}

function query(callable $queryfunc, array $data = []): mixed
{
	try {
		return $queryfunc(DBService::connectDB(), $data) ?:
			throw new Exception("*Ominous silence* Something is off...");
	} catch (\Throwable $th) {
		return $th->getMessage();
	}
}
