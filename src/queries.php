<?php

declare(strict_types=1);

require_once('connection.php');
require_once('helpers.php');

function findAllPosts(PDO $pdo): array | false
{
	$posts = $pdo->query(
		'SELECT `posts`.`id`, `title`, `date`, `img_url`, `likes`, `content`,
			`authors`.`name` AS `author`
		FROM `posts`
		LEFT JOIN `authors` ON `authors`.`id` = `posts`.`author_id`
		ORDER BY `likes` DESC;'
	)->fetchAll();

	return !empty($posts) ? $posts : false;
}

function findTagsOnPost(PDO $pdo, array $postID): array | false
{
	$stmt = $pdo->prepare(
		'SELECT `tag`
		FROM `posts_tags`
		LEFT JOIN `tags` ON `tags`.`id` = `posts_tags`.`tag_id`
		WHERE `posts_tags`.`post_id` = ?'
	);
	$stmt->execute($postID);

	return array_column($stmt->fetchAll(), 'tag');
}

function findTopAuthors(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT SUM(`posts`.`likes`) AS `total_likes`, `authors`.`name`, `authors`.`id`
		FROM `authors`
		LEFT JOIN `posts` ON `posts`.`author_id` = `authors`.`id`
		GROUP BY `name`
		HAVING `total_likes` > 10
		ORDER BY `total_likes` DESC;'
	)->fetchAll();
}

function findPostsWithThisAuthor(PDO $pdo, array $name): array | false
{
	$stmt = $pdo->prepare(
		'SELECT `posts`.`id`, `title`, `date`, `img_url`, `likes`, `content`,
			`authors`.`name` AS `author`
		FROM `posts`
		LEFT JOIN `authors` ON `authors`.`id` = `posts`.`author_id`
		WHERE `authors`.`name` = ?
		ORDER BY `likes` DESC;'
	);
	$stmt->execute($name);

	return $stmt->fetchAll();
}

function findPostsWithThisTag(PDO $pdo, array $tags): array | false
{
	$stmt = $pdo->prepare(
		'SELECT `posts`.`id`, `title`, `date`, `img_url`, `likes`, `content`,
			`authors`.`name` AS `author`
		FROM `posts`
		INNER JOIN `authors` on `authors`.`id` = `posts`.`author_id`
		WHERE `posts`.`id` IN
			(SELECT `post_id` FROM `posts_tags`
			INNER JOIN `tags` on `tags`.`id` = `posts_tags`.`tag_id`
			WHERE `tags`.`tag` = ?);'
	);
	$stmt->execute($tags);

	return $stmt->fetchAll();
}


function findAuthorNameByID(PDO $pdo, array $id): array | false
{
	$stmt = $pdo->prepare(
		'SELECT `authors`.`name`
		FROM `authors`
		WHERE `authors`.`id` = :id;'
	);
	$stmt->execute($id);

	return $stmt->fetch();
}

function findAllAuthors(PDO $pdo): array | false
{
	$allAuthors = $pdo->query(
		'SELECT `id`, `name` FROM `authors`;'
	)->fetchAll();

	return !empty($allAuthors) ? $allAuthors : false;
}

function insertPost(PDO $pdo, array $data): bool
{
	return $pdo->prepare(
		'INSERT INTO `posts` (`title`, `img_url`, `author_id`, `content`) 
		VALUES (:title, :img_url, :author, :content);'
	)->execute($data);
}

function linkTagsToPost(PDO $pdo, array $tagIDs): bool
{
	$stmt = $pdo->prepare(
		"INSERT INTO `posts_tags` (`post_id`, `tag_id`)
		VALUES ( (SELECT MAX(`id`) FROM `posts`), :tagID);"
	);

	foreach ($tagIDs as $tagID) {
		$stmt->bindValue(':tagID', $tagID);
		$result = $stmt->execute();
	}

	return $result;
}

function insertTags(PDO $pdo, array $tags): bool
{
	$params = implode(', ', array_pad([], count($tags), '(?)'));

	return $pdo->prepare(
		"INSERT IGNORE INTO `tags` (`tag`) VALUES $params;"
	)->execute($tags);
}

function findTagIDsByName(PDO $pdo, array $tags): array | false
{
	$params = implode(', ', array_pad([], count($tags), '?'));
	$stmt = $pdo->prepare(
		"SELECT `id` from `tags` WHERE `tag` IN ($params);"
	);
	$stmt->execute($tags);

	foreach ($stmt->fetchAll() as $record) {
		$tagIDs[] = intval($record['id']);
	}

	return !empty($tagIDs) ? $tagIDs : false;
}

function submitRating(PDO $pdo, array $rating): bool
{
	[$plusOrMinusOne, $id] = $rating;
	$stmt = $pdo->prepare(
		'UPDATE `posts` SET `likes` = `likes` + :rating
		WHERE id = :id'
	);
	$stmt->bindValue(':rating', $plusOrMinusOne, PDO::PARAM_INT);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);

	return $stmt->execute();
}

function performQuery(callable $queryFunc, array $data = []): mixed
{
	return $queryFunc(DBService::connectDB(), $data);
}
