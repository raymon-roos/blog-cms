<?php

declare(strict_types=1);

require_once('connection.php');

function findAllPosts(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT `posts`.`id`, `title`, `date`, `img_url`, `likes`, `content`,
			`authors`.`name` as `author`
		FROM `posts`
		LEFT JOIN `authors` ON `authors`.`id` = `posts`.`author_id`
		ORDER BY `likes` DESC;'
	)->fetchAll();
}

function findTopAuthors(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT SUM(`posts`.`likes`) as `total_likes`, `authors`.`name`
		FROM `authors`
		LEFT JOIN `posts` ON `posts`.`author_id` = `authors`.`id`
		GROUP BY `name`
		HAVING `total_likes` > 10;'
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

function submitRating(PDO $pdo, array $rating): bool
{
	[$up_down, $id] = $rating;
	$stmt = $pdo->prepare(
		"UPDATE `posts` SET `likes` = `likes` + :rating
		WHERE id = :id"
	);
	$stmt->bindValue(':rating', ($up_down) ? 1 : -1, PDO::PARAM_INT);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);

	return $stmt->execute();
}

function query(callable $queryfunc, array $data = []): mixed
{
	try {
		return $queryfunc(DBService::connectDB(), $data)
			?: throw new Exception();
	} catch (\Throwable) {
		return  "*Ominous silence* Something is off...";
	}
}
