<?php
    // DOCS: https://wiki.vg/Mojang_API#UUID_to_Profile_and_Skin.2FCape
    // Скрипт готов. Почти... (скины)
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_settings.php';
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_request.php';
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_pdo_mysql.php';
    include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_utils.php';

    if ($config['debug'])
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
    }

    if (!isset($_GET['uuid']) || !preg_match("/^[a-zA-Z0-9_-]+$/", $_GET['uuid']))
    {
        if ($config['debug'])
        {
            request_deny(
                "IllegalArgumentException", 
                "Invalid UUID."
            );
        }
        die();
    }

    $uuid = shortUuid(filter_input(INPUT_GET,'uuid',FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $unsigned = filter_input(INPUT_GET,'unsigned',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	if(empty($unsigned)) $unsigned = true;

    try
    {
        $stmt = $pdo->prepare("SELECT {$config['sql_username']},{$config['sql_uuid']},{$config['sql_skin_hash']},{$config['sql_cloak_hash']},{$config['sql_skin_type']} FROM {$config['sql_db_table']} WHERE {$config['sql_uuid']} = :uuid LIMIT 1");
		$stmt->bindValue(':uuid', $uuid);
        $stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
	{
		if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' . $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Извините, сервер не работает! Мы работаем над этой проблемой!"
        );
    }

    if($row == null)
    {
        if ($config['debug']) 
        {
            request_deny(
                "User not Found", 
                "Пользователь не найден. Пожалуйста, обратитесь в службу поддержки!"
            );
        }
        die();
    }

    checkKeyPair(); // проверяем приватные ключи
    request_get_session_profile($row[$config['sql_username']], $uuid, $config['server_url_skins'], $row[$config['sql_skin_hash']], $row[$config['sql_cloak_hash']], $row[$config['sql_skin_type']], $unsigned);