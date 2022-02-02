<?php
	$db = 'mysql:host='.$config['sql_db_host'] . ';dbname=' . $config['sql_db_database'] . ';charset=utf8';
	$opt = array(
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
		PDO::ATTR_EMULATE_PREPARES   => false
	);
	$pdo = new PDO($db, $config['sql_db_user'], $config['sql_db_password'], $opt);