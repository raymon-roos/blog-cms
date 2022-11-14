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

	return !empty($tags)
		? $tags
		: throw new Exception('Please fill in the fields properly');
}

function processPost(array $post): array | string
{
	$post = array_map('htmlspecialchars', array_filter($post));
	$post['author'] = intval($post['author']);

	return (!empty($post) && count($post) === 4)
		? $post
		: throw new Exception('Please fill in the fields properly');
}

function processSubmit(array $input): bool
{
	$post = processPost(array_slice($input, 0, 4));

	$postSubmitIsSucess = performQuery('insertPost', $post);

	if (!empty($input['tags'])) {
		$tags = processTags($input['tags']);
		performQuery('insertTags', $tags);
		performQuery('linkTagsToPost', performQuery('findTagIDsByName', $tags));
	}

	return  $postSubmitIsSucess
		?: throw new Exception('An error occured, please try again later');
}

function processUpOrDownVote(array $vote): void
{
	match (array_key_first($vote)) {
		'upvote' => performQuery('submitRating', [1, (intval($_POST['upvote']))]),
		'downvote' => performQuery('submitRating', [-1, intval($_POST['downvote'])]),
	};
}

function renderPostsOverview(?array $posts): void
{
	if (empty($posts)) {
		throw new Exception('Nothing here yet, come back later');
	}

	foreach ($posts as $post) {
		$post['tags'] = performQuery('findTagsOnPost', [$post['id']]);
		extract($post);
		include('templates/components/post_listing.php');
	}
}

function renderOptionalComponent(string $component, ?array $setOfDataToRender = null): void
{
	if (!file_exists("templates/components/$component.php")) {
		throw new Exception('component not found');
	}

	if (empty($setOfDataToRender)) {
		return;
	}

	foreach ($setOfDataToRender as $dataSubset) {
		extract($dataSubset);
		include("templates/components/$component.php");
	}
}

function gatherApropriatePostsBasedOnGETVar(array $get): array
{
	$posts = match (array_key_first($get)) {
		'author' => performQuery('findPostsWithThisAuthor', [$get['author']]),
		'tag' => performQuery('findPostsWithThisTag', [$get['tag']]),
	};

	return !empty($posts) ? $posts : throw new Exception('No posts found');
}

function renderWarningIfExists(?string &$warning): void
{
	if (!empty($warning)) {
		require_once('templates/components/warning.php');
		unset($warning);
	}
}

function debug(mixed ...$vars): void
{
	echo count($vars);
	require_once('templates/components/debug.php');
	exit();
}
