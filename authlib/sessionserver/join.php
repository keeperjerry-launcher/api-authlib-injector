<?php
    // DOCS: https://wiki.vg/Protocol_Encryption#Client
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

    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        request_deny(
            "Method Not Allowed", 
            "Метод, указанный в запросе, не разрешен для ресурса, указанного в URI запроса."
        );
    }

    if (stripos($_SERVER["CONTENT_TYPE"], "application/json") !== 0)
    {
        require_deny(
            "Unsupported Media Type", 
            "Сервер отказывается обслуживать запрос, потому что объект запроса находится в формате, не поддерживаемом запрошенным ресурсом для запрошенного метода."
        );
    }

    $json = json_decode(file_get_contents('php://input'));
    $uuid = $json->selectedProfile;
    $sessionid = $json->accessToken;
    $serverid = $json->serverId;

    if (!preg_match("/^[a-zA-Z0-9_-]+$/", $uuid) || empty($uuid))
    {
        request_deny(
            "IllegalArgumentException", 
            "Неверный UUID. Пожалуйста, обратитесь в поддержку!"
        );
    }

    if (!preg_match("/^[a-zA-Z0-9:_-]+$/", $sessionid) || empty($sessionid))
    {
        request_deny(
            "IllegalArgumentException", 
            "Неверный идентификатор сеанса. Пожалуйста, перезайдите в лаунчер!"
        );
    }

    if (!preg_match("/^[a-zA-Z0-9_-]+$/", $serverid) || empty($serverid))
    {
        request_deny(
            "IllegalArgumentException", 
            "Неверный идентификатор сервера. Пожалуйста, перезайдите в лаунчер!"
        );
    }

    try
    {
        $stmt_select = $pdo->prepare("SELECT {$config['sql_username']},{$config['sql_uuid']} FROM {$config['sql_db_table']} WHERE {$config['sql_access_token']} = :sessionid LIMIT 1");
        $stmt_select->bindValue(':sessionid', $sessionid);
        $stmt_select->execute();
        
        $row = $stmt_select->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
        if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' . $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Извините, сервер не работает! Мы работаем над этой проблемой!"
        );
    }

    if($row == null || $row[$config['sql_uuid']] != $uuid)
    {
        request_deny(
            "User not Found", 
            "Пользователь не найден. Пожалуйста, зарегистрируйтесь на сайте или обратитесь в службу поддержки."
        );
    }

    try
    {
        $stmt_update = $pdo->prepare("UPDATE {$config['sql_db_table']} SET {$config['sql_server_id']} = :serverid WHERE {$config['sql_access_token']} = :sessionid");
        $stmt_update->bindValue(':sessionid', $sessionid);
        $stmt_update->bindValue(':serverid', $serverid);
        $stmt_update->execute();
    }
    catch (PDOException $e)
    {
        if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' . $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Извините, сервер не работает! Мы работаем над этой проблемой!"
        );
    }

    if($stmt_update->rowCount() != 1)
    {
        request_deny(
            "Internal Server Error", 
            "Сервисная ошибка! Обратитесь в администрацию!"
        );
    }

    die(header("status: 204"));
