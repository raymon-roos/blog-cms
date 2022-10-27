<?php

declare(strict_types=1);

require_once('connection.php');
require_once('queries.php');

$posts = query('findAllPosts');
$warning = is_string($posts) ? $posts : '';
$posts = is_array($posts) ? $posts : [];

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

		<?php if ($posts) {
			foreach ($posts as $post) {
				extract($post); ?>
				<article class="post">
					<div class="header">
						<h2><?= $title ?></h2>
						<img src="<?= $img_url ?>" />
					</div>
					<span class="details">Geschreven op: <?= $date ?> door <?= $author ?> <b></b></span>
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
