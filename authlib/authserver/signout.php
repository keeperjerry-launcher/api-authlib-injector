<?php
    // DOCS: https://wiki.vg/Authentication#Signout
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
            "The method specified in the request is not allowed for the resource identified by the request URI."
        );
    }

    if (stripos($_SERVER["CONTENT_TYPE"], "application/json") !== 0)
    {
        request_deny(
            "Unsupported Media Type", 
            "The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method."
        );
    }

    $json = json_decode(file_get_contents('php://input'));
    $user = $json->username;
    $password = $json->password;

    if ($user == NULL || $password == NULL)
    {
        die(header("status: 403"));
    }

    try 
    {
        $sql_auth = $pdo->prepare("SELECT {$config['sql_id']},{$config['sql_username']},{$config['sql_password']},{$config['sql_uuid']} FROM {$config['sql_db_table']} WHERE {$config['sql_email']} = :user1 OR {$config['sql_username']} = :user2 LIMIT 1");
        $sql_auth->bindValue(':user1', $user);
        $sql_auth->bindValue(':user2', $user);
        $sql_auth->execute();
        $sql_auth_result = $sql_auth->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
        if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' .  $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Sorry, server is down! We are working on this problem!"
        );
    }

    if($sql_auth_result == null)
    {
        die(header("status: 403"));
    }

    if (!checkPass($password, $sql_auth_result[$config['sql_password']]))
    {
        die(header("status: 403"));
    }

    try
    {
        $update_access_token = $pdo->prepare("UPDATE {$config['sql_db_table']} SET {$config['sql_access_token']} = NULL , {$config['sql_client_token']} = NULL WHERE {$config['sql_id']} = :id");
        $update_access_token->bindValue(':id', $sql_auth_result[$config['sql_id']]);
        $update_access_token->execute();
    }
    catch (PDOException $e)
    {
        if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' . $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Sorry, server is down! We are working on this problem!"
        );
    }

    die(header("status: 204"));