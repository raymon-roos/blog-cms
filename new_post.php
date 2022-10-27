<?php

declare(strict_types=1);

require_once('queries.php');

function validateInput(array $post): string | bool
{
	$post = array_map('htmlspecialchars', array_filter($post));
	$post['author'] = intval($post['author']);

	if (!$post || count($post) !== 4) {
		return 'Please fill in all the fields';
	}

	return query('submitPost', $post) ?: 'Something went wrong, please try again later';
}

if (!empty($_POST)) {
	if (validateInput($_POST) === true) {
		header('location: ./index.php');
		exit();
	}

	$warning = validateInput($_POST);
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
			<label for="author">author:</label>
			<select type="drop" name="author">
				<?php foreach (query('findAllAuthors') as $author) {
					extract($author) ?>
					<option value="<?= $id ?>"><?= $name ?></option>
				<?php } ?>
			</select>
			<input type="submit" value="Publiceer">
		</form>

		<article class="warning">
			<h3><?= $warning ?? '' ?></h3>
		</article>
		<?php unset($warning); ?>
	</section>
</body>

</html>
