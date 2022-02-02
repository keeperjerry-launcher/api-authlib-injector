<?php
    // DOCS: https://wiki.vg/Authentication#Refresh
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
    $accessToken = $json->accessToken;
    $clientToken = $json->clientToken;
    $selectedProfile = $json->selectedProfile;

    if ($json->requestUser != true)
    {
        $requestUser = false;
    }
    else
    {
        $requestUser = $json->requestUser;
    }

    try
    {
		$sql_auth = $pdo->prepare("SELECT {$config['sql_username']}, {$config['sql_uuid']}, {$config['sql_id']} FROM {$config['sql_db_table']} WHERE {$config['sql_client_token']} = :client_token");
		$sql_auth->bindValue(':client_token', $clientToken);
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
            "IllegalArgumentException",
            "You passed an incomplete list of data to complete the request."
        );
    }

    try
    {
		$uuid = $sql_auth_result[$config['sql_uuid']];
		$username = $sql_auth_result[$config['sql_username']];
        $new_access_token = getRandomMD5();

		$update_access_token = $pdo->prepare("UPDATE {$config['sql_db_table']} SET {$config['sql_access_token']} = :token WHERE {$config['sql_id']} = :id");
		$update_access_token->bindValue(':token', $new_access_token);
		$update_access_token->bindValue(':id', $sql_auth_result[$config['sql_id']]);
        $update_access_token->execute();
        
        request_authserver_profile_refresh($uuid, $username, $new_access_token, $clientToken, $requestUser);
    }
    catch (PDOException $e)
    {
        if ($config['debug']) die('[DEBUG] Выброшено исключение SQL: ' .  $e->getMessage() . PHP_EOL);
        request_deny(
            "Internal Server Error", 
            "Sorry, server is down! We are working on this problem!"
        );
    }