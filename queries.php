<?php

declare(strict_types=1);

require_once('connection.php');

function findAllPosts(): array | false
{
	try {
		return connectDB()->query('SELECT * FROM `posts`;')->fetchAll() ?: false;
	} catch (Throwable) {
		return false;
	}
}
