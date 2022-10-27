<?php

declare(strict_types=1);

require_once('connection.php');

function findAllPosts(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT
			`posts`.`title`, 
			`posts`.`date`, 
			`posts`.`img_url`, 
			`authors`.`name` as `author`, 
			`posts`.`content`
	    FROM `posts`
		LEFT JOIN `authors` ON `authors`.`id` = `posts`.`author_id`
		ORDER BY `date` DESC;'
	)->fetchAll();
}

function findAllAuthors(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT `id`, `name` FROM `authors`;'
	)->fetchAll();
}

function submitPost(PDO $pdo, array $data): bool
{
	return $pdo->prepare(
		"INSERT INTO `posts` (`title`, `img_url`, `author_id`, `content`) 
		VALUES (:title, :img_url, :author, :content);"
	)->execute($data);
}

function query(callable $queryfunc, array $data = []): mixed
{
	try {
		return $queryfunc(DBService::connectDB(), $data);
	} catch (\Throwable $th) {
		return  $th->getMessage();
		/* return  "*Ominous silence* Something is off..."; */
	}
}
