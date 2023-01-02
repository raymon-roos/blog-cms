<?php

declare(strict_types=1);

require_once('queries.php');
require_once('helpers.php');

try {
	if (!empty($_POST)) {
		processUpOrDownVote($_POST);
	}

	$topAuthors = performQuery('findTopAuthors');
	$posts = performQuery('findAllPosts');
	require_once('templates/home.php');
} catch (\Throwable $t) {
	$warning = $t->getMessage();
	require_once('templates/error.php');
}
