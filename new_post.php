<?php

declare(strict_types=1);

if (!empty($_POST)) {
	$post = array_filter(array_map('htmlspecialchars', $_POST));
	if ($post && count($post) === 3) {
		require_once('queries.php');
		if (query('submitPost', $post)) {
			header('location: ./index.php');
			exit();
		}
	}

	$warning = 'Please fill in all the fields';
}

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
			<h1>Nieuwe post</h1>
			<a href="index.php"><button>Alle posts</button></a>
		</article>

		<form action="new_post.php" method="post">
			<label for="title">Titel:</label>
			<input type="text" name="title">
			<label for="img_url">URL afbeelding:</label>
			<input type="text" name="img_url">
			<label for="img_url">Inhoud:</label>
			<textarea name="content" rows="10" cols="100"></textarea>
			<input type="submit" value="Publiceer">
		</form>

		<article class="warning">
			<h3><?= $warning ?? '' ?></h3>
		</article>
		<?php unset($warning); ?>
	</section>
</body>

</html>
