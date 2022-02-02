<?php
    // DOCS: https://wiki.vg/Protocol_Encryption#Server
    // Скрипт готов
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_settings.php';
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_request.php';
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_pdo_mysql.php';
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_utils.php';

    if ($config['debug'])
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'GET')
    {
        request_deny(
            "Method Not Allowed", 
            "The method specified in the request is not allowed for the resource identified by the request URI."
        );
    }

    if (!isset($_GET['username']))
    {
        request_deny(
            "IllegalArgumentException", 
            "Username is null."
        );
    }

    if (!isset($_GET['serverId']))
    {
        request_deny(
            "IllegalArgumentException", 
            "ServerId is null."
        );
    }

    if (!preg_match("/^[a-zA-Z0-9_-]+$/", $_GET['username']))
    {
        request_deny(
            "IllegalArgumentException", 
            "Invalid Username."
        );
    }

    if (!preg_match("/^[a-zA-Z0-9_-]+$/", $_GET['serverId']) || !strlen($_GET['serverId']) >= 40)
    {
        request_deny(
            "IllegalArgumentException", 
            "Invalid ServerId."
        );
    }

    $username = filter_input(INPUT_GET,'username',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $serverid = filter_input(INPUT_GET,'serverId',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $ip_server = filter_input(INPUT_GET,'ip',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    try {
        $select_joined = $pdo->prepare("SELECT {$config['sql_username']},{$config['sql_uuid']},{$config['sql_skin_hash']},{$config['sql_cloak_hash']} FROM {$config['sql_db_table']} WHERE {$config['sql_username']} = :user AND {$config['sql_server_id']} = :serverid LIMIT 1");
        $select_joined->bindValue(':user', $username);
        $select_joined->bindValue(':serverid', $serverid); 
        $select_joined->execute();

        $row = $select_joined->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
        if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' . $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Sorry, server is down! We are working on this problem!"
        );
    }

    if($username != $row[$config['sql_username']])
    {
        request_deny(
            "Not Found", 
            "Invalid username or password."
        );
    }

    request_get_session_profile($row[$config['sql_username']], $row[$config['sql_uuid']], $config['server_url_skins'], $row[$config['sql_skin_hash']] , $row[$config['sql_cloak_hash']]);