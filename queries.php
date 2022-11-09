<?php

declare(strict_types=1);

require_once('connection.php');
require_once('helpers.php');

function findAllPosts(PDO $pdo): array | false
{
	$postIDs = $pdo->query('SELECT `id` FROM `posts`;')->fetchAll();
	$postsCollection = $pdo->query(
		'SELECT `posts`.`id`, `title`, `date`, `img_url`, `likes`, `content`,
			`authors`.`name` AS `author`
		FROM `posts`
		LEFT JOIN `authors` ON `authors`.`id` = `posts`.`author_id`
		ORDER BY `likes` DESC;'
	);

	$tagsOnPost = implode(
		', ',
		array_map(
			'$pdo->query(
				"SELECT `tag` from `tags`
				WHERE `tags`.`id` = `posts_tags`.`tag_id`
				FROM `tags`
				LEFT JOIN `tags` ON `tags`.`id` = `posts_tags`.`tag_id`;"
			)->fetch',
			$postIDs
		)
	);

	debug($tagsOnPost);
	exit();

	return !empty($posts) ? $posts : false;
}

function findTagsAttachedToPost(PDO $pdo, array $postID): array
{
	$stmt = $pdo->prepare(
		'SELECT `tag`
		FROM `posts_tags`
		LEFT JOIN `tags` ON `tags`.`id` = `posts_tags`.`tag_id`
		WHERE `posts_tags`.`post_id` = ?'
	);
	$stmt->execute($postID);

	return $stmt->fetchAll();
}

function findTopAuthors(PDO $pdo): array | false
{
	return $pdo->query(
		'SELECT SUM(`posts`.`likes`) as `total_likes`, `authors`.`name`, `authors`.`id`
		FROM `authors`
		LEFT JOIN `posts` ON `posts`.`author_id` = `authors`.`id`
		GROUP BY `name`
		HAVING `total_likes` > 10
		ORDER BY `total_likes` DESC;'
	)->fetchAll();
}

function findPostsByAuthor(PDO $pdo, array $id): array | false
{
	$stmt = $pdo->prepare(
		'SELECT `posts`.`id`, `title`, `date`, `img_url`, `likes`, `content`, `authors`.`id`
		FROM `posts`
		LEFT JOIN `authors` ON `authors`.`id` = `posts`.`author_id`
		WHERE `posts`.`author_id` = :id
		ORDER BY `likes` DESC;'
	);
	$stmt->execute($id);

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
	return $pdo->query(
		'SELECT `id`, `name` FROM `authors`;'
	)->fetchAll();
}

function submitPost(PDO $pdo, array $data): bool
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
	[$up_down, $id] = $rating;
	$stmt = $pdo->prepare(
		'UPDATE `posts` SET `likes` = `likes` + :rating
		WHERE id = :id'
	);
	$stmt->bindValue(':rating', ($up_down) ? 1 : -1, PDO::PARAM_INT);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);

	return $stmt->execute();
}

/**
 * Execute a previously defined database function, catch any exception.
 * Returns query result on success or false if query was unsuccesful or
 * if an error occured.
 *
 * @param callable $queryfunc Database function to execute
 * @param aray $data optional array of data to pass on to database function
 * @return array | false
 */
function query(callable $queryfunc, array $data = []): mixed
{
	try {
		return $queryfunc(DBService::connectDB(), $data);
	} catch (\Throwable $e) {
		return  $e;
		/* return  false; */
	}
}
