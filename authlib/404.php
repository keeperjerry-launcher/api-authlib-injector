<?php
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_settings.php';
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_request.php';

	if ($config['debug'])
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}

	request_deny();