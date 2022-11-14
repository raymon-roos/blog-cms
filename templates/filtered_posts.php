<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= (urldecode($_GET[array_key_first($_GET)])) ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="style.css" rel="stylesheet">
</head>

<body>
	<main class="container">
		<article id="header">
			<h1><?= (rawurldecode($_GET[array_key_first($_GET)])) ?>:</h1>
			<a href="index.php"><button>Back to home</button></a>
		</article>

		<section>
			<?php renderPostsOverview($postsToShow); ?>
		</section>

		<?php renderWarningIfExists($warning); ?>
	</main>
</body>

</html>
