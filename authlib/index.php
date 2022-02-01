<?php
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_settings.php';
	include $_SERVER['DOCUMENT_ROOT'].'/authlib/_functions/api_request.php';

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
			// Я просто взял сертификат ely.by. Вообще по барабану че там будет, лаунчер все равно не использует сигнатуру.
			'signaturePublickey' => "-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAxgFJRb0e9fRyVG5+JlCg\nh0hccRIcgO5yxEVkMJajAI12Ev/Pc7lpTt6OtKTEcUNfjYgBnEhIKbdLD0Z+B5Bx\nSg9DQmozgzZcesScpASQb4Kt6P8itowdbgbUm4v+6x1QUKJjjmhHq93m9OIEbxQL\nCq+SrEMZpDrXRgd9DhNPjZv/95ximP8otvh7+bmEl8jwINgfJx0PAeJFYlceQcsh\niYh+LHtaIwzbTTqkDibDm7QiEc+/qGab3mABtVTpqw/refwFoR0M8+xkWF+1/D8k\nH0WFa+rBhdjLyLG+2hdOpKXoH/fMH0tQMPHU78J17JVKWwIWCwEWXp8HiWSbIt3a\ncmBYtyW3tqarFFMMECx2wmJP6FVOvYVThZxq9qc9/f3yeTGz3g7zU1YljHSVRP16\niEbEnHQBKxmrj2cdZgosJej4YppV7f3iZ8o8PF6UY51LSqvaCteXuWeYSJJESGAs\nUoV7ihJfWL8DymHamywB2Cahx7EiDGS3/iBcQUmpk4TTg2FrZPuKGItn1QfIRieO\nknnj9CPKiWdfOtJBr3i1FXLEfExgcJhQ00Y6B08QVvgiCzUF3t+VAG3Ef2YINYyG\nAXcW0TIgMalwwgGzdhQRhItODXptWigy0DNTUAgKQT9PS8N09yPBGxIq64T9A3/z\nFqC/k2bMLWUSVtIlilIItn0CAwEAAQ==\n-----END PUBLIC KEY-----\n"
		)
	);