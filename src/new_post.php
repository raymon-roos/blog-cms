<?php

declare(strict_types=1);

require_once('queries.php');
require_once('helpers.php');

try {
	if (!empty($_POST)) {
		$processSubmitIsSuccess = processSubmit($_POST);
	}

	if ($processSubmitIsSuccess ?? false) {
		header('location: ./index.php');
		exit();
	}

	$authors = performQuery('findAllAuthors');
	require_once('templates/new_post.php');
} catch (\Throwable $th) {
	$warning = $th->getMessage();
	require_once('templates/error.php');
}
