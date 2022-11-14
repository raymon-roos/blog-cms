<article class="post">

	<header class="header">
		<h2><?= $title ?></h2>
		<img src="<?= $img_url ?>" />
	</header>

	<span class="details"> Geschreven op: <?= $date ?> door

		<b><a href="lookup.php?author=<?= urlencode($author) ?>">
			<?= $author ?>
		</a></b>

		<span>
			<?php foreach ($tags as $tag) : ?>
				<a href="lookup.php?tag=<?= $tag ?>"><?= $tag ?> </a>
			<?php endforeach ?>
		</span>

		<form action="index.php" method="post" class="likescontainer">
			<button type="submit" name="upvote" class="rate" value="<?= $id ?>">
				<svg width="24px" height="24px" viewBox="0 0 24 24">
					<path d="M4 14h4v7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7h4a1.001 1.001 0 0 0 .781-1.625l-8-10c-.381-.475-1.181-.475-1.562 0l-8 10A1.001 1.001 0 0 0 4 14z" />
				</svg>
			</button>
			<span class="likes"><b><?= $likes ?></b></span>
			<button type="submit" name="downvote" class="rate" value="<?= $id ?>">
				<svg width="24px" height="24px" viewBox="0 0 24 24">
					<path d="M20.901 10.566A1.001 1.001 0 0 0 20 10h-4V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v7H4a1.001 1.001 0 0 0-.781 1.625l8 10a1 1 0 0 0 1.562 0l8-10c.24-.301.286-.712.12-1.059z" />
				</svg>
			</button>
		</form>

	</span>

	<p><?= $content ?></p>

</article>
