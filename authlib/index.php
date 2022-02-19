<?php
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_settings.php';
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_request.php';
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_utils.php';

	if ($config['debug'])
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}

    request_die_json(
		array(
			'meta' => array (
				"serverName" => $config['server_name'],
				"implementationName" => $config['server_description'],
				"implementationVersion" => $config['server_version'],
				"feature.no_mojang_namespace" => $config['server_mojang_namespace'],
				"links" => array (
					"homepage" => $config['server_link_homepage'],
					"register" => $config['server_link_register']
				)
			),
			'skinDomains' => array (
				$config['server_domain'],
				".".$config['server_domain']
			),
			'signaturePublickey' => getPublicKey()
		)
	);