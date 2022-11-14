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
			<h1>New post</h1>
			<a href="index.php"><button>All posts</button></a>
		</article>

		<form action="new_post.php" method="post">
			<label for="title">Title:</label>
			<input type="text" name="title">
			<label for="author">author:</label>
			<select type="drop" name="author">
				<?php foreach ($authors as $author) : ?>
					<option value="<?= $author['id'] ?>"><?= $author['name'] ?></option>
				<?php endforeach ?>
			</select>
			<label for="img_url">IMG Url:</label>
			<input type="text" name="img_url">
			<label for="content">Content:</label>
			<textarea name="content" rows="10" cols="100"></textarea>
			<label for="tags">Tags (comma separated):</label>
			<input type="text" name="tags">
			<input type="submit" value="Publiceer">
		</form>

		<?php renderWarningIfExists($warning); ?>
	</main>
</body>

</html>
