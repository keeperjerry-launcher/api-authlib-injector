<?php
    // DOCS: https://wiki.vg/Authentication#Authenticate
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
    $email = $json->username;
    $password = $json->password;
    $clientToken = $json->clientToken;

    if (empty($json->requestUser))
    {
        $requestUser = false;
    }
    else
    {
        $requestUser = $json->requestUser;
    }

    if (!preg_match("/^[a-zA-Z0-9_-]+$/", $clientToken))
    {
        request_deny(
            "IllegalArgumentException", 
            "Invalid client token."
        );
    }

    try 
    {
        $sql_auth = $pdo->prepare("SELECT {$config['sql_id']},{$config['sql_username']},{$config['sql_password']} FROM {$config['sql_db_table']} WHERE {$config['sql_email']} = :email LIMIT 1");
        $sql_auth->bindValue(':email', $email);
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
        request_deny(
            "User not Found", 
            "User is not found. Please, register on the site or contact support."
        );
    }

    if (!checkPass($password, $sql_auth_result[$config['sql_password']]))
    {
        request_deny(
            "Invalid password", 
            "Invalid username or password."
        );
    }

    try
    {
        $uuid = uuidConvertShort($sql_auth_result[$config['sql_username']]);
        $username = $sql_auth_result[$config['sql_username']];
        $new_access_token = getRandomMD5();

        $update_access_token = $pdo->prepare("UPDATE {$config['sql_db_table']} SET {$config['sql_uuid']} = :uuid , {$config['sql_access_token']} = :token1 , {$config['sql_client_token']} = :token2 WHERE {$config['sql_id']} = :id");
        $update_access_token->bindValue(':uuid', $uuid);
        $update_access_token->bindValue(':token1', $new_access_token);
        $update_access_token->bindValue(':token2', $clientToken);
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

    request_authserver_profile_auth($uuid, $username, $new_access_token, $clientToken, $requestUser);