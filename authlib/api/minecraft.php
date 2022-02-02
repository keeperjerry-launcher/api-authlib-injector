<?php
    // DOCS: https://wiki.vg/Mojang_API#Usernames_to_UUIDs
    // Почти готово, попозже доделаю
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

    $json = json_decode(file_get_contents('php://input'), true);
    
    if (count($json) > 10)
    {
        request_deny(
            "IllegalArgumentException",
            "Not more that 10 profile name per call is allowed."
        );
    }

    try
    {
        $sql_auth = $pdo->prepare("SELECT {$config['sql_username']},{$config['sql_uuid']} FROM {$config['sql_db_table']} WHERE {$config['sql_username']} IN ( :usernames )");
        $sql_auth->bindValue(':usernames', $json[0]);
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

    request_die_json(
        array(
            array(
                'id' => $sql_auth_result[$config['sql_uuid']],
                'name' => $sql_auth_result[$config['sql_username']]
            )
        )
    );