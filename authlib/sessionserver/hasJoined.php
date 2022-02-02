<?php
    // DOCS: https://wiki.vg/Protocol_Encryption#Server
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