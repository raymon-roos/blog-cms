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

		<article>
			<?php renderOptionalComponent('top_authors_list', $topAuthors); ?>
		</article>

		<section>
			<?php renderPostsOverview($posts); ?>
		</section>
	</main>
</body>

</html>
