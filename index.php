<?php

declare(strict_types=1);

require_once('queries.php');

$warning = ($posts = query('findAllPosts'))
	? null
	: 'No posts found';

?>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

	<div class="container">
		<div id="header">
			<h1>Foodblog</h1>
			<a href="new_post.php"><button>Nieuwe post</button></a>
		</div>

		<?php if ($posts) {
			foreach ($posts as $post) {
				extract($post); ?>
				<div class="post">
					<div class="header">
						<h2><?= $title ?></h2>
						<img src="<?= $img_url ?>" />
					</div>

					<span class="details">Geschreven op: <?= $date ?> door <b> Wim Ballieu</b></span>
					<p><?= $content ?></p>
				</div>
		    <?php }
		} ?>
		<h3><?= $warning ?? '' ?></h3>
	</div>
</body>

</html>
