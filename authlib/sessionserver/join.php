<?php
    // DOCS: https://wiki.vg/Protocol_Encryption#Client
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