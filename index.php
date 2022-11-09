<?php

declare(strict_types=1);

require_once('queries.php');
require_once('helpers.php');

if (!empty($_POST['upvote'])) {
	$vote = query(
		'submitRating',
		[1, (intval($_POST['upvote']))]
	);
	unset($_POST);
}

if (!empty($_POST['downvote'])) {
	$vote = query(
		'submitRating',
		[0, intval($_POST['downvote'])]
	);
	unset($_POST);
}

$topAuthors = query('findTopAuthors');
$posts = query('findAllPosts');

$warning = (!$posts || !$topAuthors) ? 'something went wrong' : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<main class="container">
		<article id="header">
			<h1>Foodblog</h1>
			<a href="new_post.php"><button>Create new post</button></a>
		</article>

		<?php if (!empty($topAuthors)) { ?>
			<ul>
				<?php foreach ($topAuthors as $author) { ?>
					<?php list('total_likes' => $likes, 'id' => $id, 'name' => $name) = $author ?>
					<li>
						<a href="lookup.php?author=<?= $id ?>">
							<?= $name ?></a>, total likes: <?= $likes ?>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>

		<section>
			<?php renderPostsList($posts); ?>
		</section>

		<article class="warning">
			<h3><?= $warning ?? '' ?></h3>
		</article>
		<?php unset($warning); ?>
	</main>
</body>

</html>
