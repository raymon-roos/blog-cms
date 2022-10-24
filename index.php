<?php

require_once('queries.php');

$posts = findAllPosts();

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

		<?php
		if ($posts) {
			foreach ($posts as $post => $postDetails) { ?>
				<div class="post">
					<div class="header">
						<h2><?= $postDetails['title'] ?></h2>
						<img src="<?= $postDetails['img_url'] ?>" />
					</div>

					<span class="details">Geschreven op: <?= $postDetails['date'] ?> door <b> Wim Ballieu</b></span>
					<p><?= $postDetails['content'] ?></p>
				</div>
			<?php }
		} else { ?>
			<h3>No posts to view yet</h3>
		<?php } ?>
	</div>
</body>

</html>
