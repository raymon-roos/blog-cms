<?php

declare(strict_types=1);

require_once('queries.php');
require_once('helpers.php');

try {
	if (!empty($_GET)) {
		$postsToShow = gatherApropriatePostsBasedOnGETVar($_GET);
	}
	require_once('templates/filtered_posts.php');
} catch (\Throwable $t) {
	$warning = $t->getMessage();
	require_once('templates/error.php');
}

