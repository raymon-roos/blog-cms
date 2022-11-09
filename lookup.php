<?php

declare(strict_types=1);

require_once('queries.php');
require_once('helpers.php');

if (!empty($_GET['author'])) {
	$authorPosts = query(
		'findPostsByAuthor',
		['id' => intval($_GET['author'])]
	);
	$authorName = query(
		'findAuthorNameByID',
		['id' => intval($_GET['author'])]
	)['name'];
}

$warning = (!$authorPosts || !$authorName) ? 'No posts were found for this author' : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= $authorName ?>'s posts</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<main class="container">
		<article id="header">
			<h1><?= $authorName ?>'s posts</h1>
			<a href="index.php"><button>Back to home</button></a>
		</article>

		<section>
			<?php renderPostsList($authorPosts, $authorName); ?>
		</section>

		<article class="warning">
			<h3><?= $warning ?? '' ?></h3>
		</article>
		<?php unset($warning); ?>
	</main>
</body>

</html>
