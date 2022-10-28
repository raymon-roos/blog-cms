<?php

declare(strict_types=1);

require_once('connection.php');
require_once('queries.php');

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

$posts = query('findAllPosts');
$warning = is_string($posts) ? $posts : '';

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
	<section class="container">
		<article id="header">
			<h1>Foodblog</h1>
			<a href="new_post.php"><button>Nieuwe post</button></a>
		</article>

		<?php if (is_array($posts)) {
			foreach ($posts as $post) {
				extract($post); ?>
				<article class="post">
					<div class="header">
						<h2><?= $title ?></h2>
						<img src="<?= $img_url ?>" />
					</div>
					<span class="details">Geschreven op: <?= $date ?> door <b><?= $author ?></b>
						<form action="index.php" method="post" class="likescontainer">
							<button type="submit" name="upvote" value="<?= $id ?>" class="rate">
								<svg width="24px" height="24px" viewBox="0 0 24 24">
									<path d="M4 14h4v7a1
											1 0 0 0 1 1h6a1
											1 0 0 0 1-1v-7h4a1.001
											1.001 0 0 0 .781-1.625l-8-10c-.381-.475-1.181-.475-1.562 0l-8 10A1.001
										1.001 0 0 0 4 14z">
									</path>
								</svg>
							</button>
							<span class="likes"><b><?= $likes ?></b></span>
							<button type="submit" name="downvote" value="<?= $id ?>" class="rate">
								<svg width="24px" height="24px" viewBox="0 0 24 24">
									<path d="M20.901 10.566A1.001
											1.001 0 0 0 20 10h-4V3a1
											1 0 0 0-1-1H9a1
											1 0 0 0-1 1v7H4a1.001
											1.001 0 0 0-.781 1.625l8 10a1
											1 0 0 0 1.562 0l8-10c.24-.301.286-.712.12-1.059z">
									</path>
								</svg>
							</button>
						</form>
					</span>
					<p><?= $content ?></p>
				</article>
			<?php }
		} ?>

		<article class="warning">
			<h3><?= $warning ?? '' ?></h3>
		</article>
		<?php unset($warning); ?>
	</section>
</body>

</html>
