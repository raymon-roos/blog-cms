<?php

require_once('queries.php');

function processTags(string $tags): array | false
{
	$tags = array_filter(
		array_map(
			fn ($i) => strtolower(trim($i)),
			explode(',', $tags)
		)
	);

	return !empty($tags) ? $tags : false;
}

function processPost(array $post): array | string
{
	$post = array_map('htmlspecialchars', array_filter($post));
	$post['author'] = intval($post['author']);

	return ($post && count($post) === 4) ? $post : false;
}

function processSubmit(array $input): bool
{
	$post = processPost(array_slice($input, 0, 4));

	if (!is_array($post)) {
		return false;
	}

	if (!empty($input['tags'])) {
		$tags = processTags($input['tags']);
	}

	$postResult = query('submitPost', $post);

	if (is_array($tags)) {
		$tagsResult = query('insertTags', $tags);
		$tagIDs = query('findTagIDsByName', $tags);
		$tagsResult = (query('linkTagsToPost', $tagIDs));
	}

	return  !empty($postResult) && !empty($tagsResult);
}


/**
 * Outputs a list of given posts to the screen
 *
 * @param null|array $posts previously retrieved posts from the database
 * @param null|string $authorName optional name of author to print if it is not included in the posts' data
 */
function renderPostsList(?array $posts, ?string $authorName = null): void
{
	foreach ($posts as $post) {
		extract($post); ?>
		<article class="post">
			<header class="header">
				<h2><?= $title ?></h2>
				<img src="<?= $img_url ?>" />
			</header>
			<span class="details">
				Geschreven op: <?= $date ?> door <b><?= $authorName ?? $author ?></b>
				<b><?php var_dump($tags) ?></b>
				<form action="index.php" method="post" class="likescontainer">
					<button type="submit" name="upvote" value="<?= $id ?>" class="rate">
						<svg width="24px" height="24px" viewBox="0 0 24 24">
							<path d="M4 14h4v7a1
								1 0 0 0 1 1h6a1
								1 0 0 0 1-1v-7h4a1.001
								1.001 0 0
								0 .781-1.625l-8-10c-.381-.475-1.181-.475-1.562
								0l-8 10A1.001
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
								1 0 0 0 1.562
								0l8-10c.24-.301.286-.712.12-1.059z">
							</path>
						</svg>
					</button>
				</form>
			</span>
			<p><?= $content ?></p>
		</article>
	<?php }
}

function debug(mixed $var): void
{
	echo '<pre><h2>';
	var_dump($var);
	echo '</h2></pre>';
	exit();
}
