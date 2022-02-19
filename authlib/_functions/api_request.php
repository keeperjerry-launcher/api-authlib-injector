<?php
    function request_die_json($array_data)
    {
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($array_data));
    }

    function request_deny($errorcode = "Unauthorized", $errormessege = "The request requires user authentication.", $errorCause = NULL)
    {
        if ($errorCause != NULL)
        {
            request_die_json(
                array(
                    'error'             => $errorcode,
                    'errorMessage'      => $errormessege,
                    'cause'             => $errorCause
                )
            );
        }

        request_die_json(
            array(
                'error'             => $errorcode,
                'errorMessage'      => $errormessege
            )
        );
    }

    // =========================================
    // AuthServer Request JSON
    // =========================================
    function request_authserver_profile_auth($uuid, $username, $access_token, $client_token, $requestUser)
    {
        if ($requestUser == true)
        {
            request_die_json(
                array(
                    'accessToken'       => $access_token,
                    'clientToken'       => $client_token,
                    'availableProfiles' => array(
                        array(
                            'name'      => $username,
                            'id'        => $uuid
                        )
                    ),
                    'selectedProfile'   => array(
                        'id'        => $uuid,
                        'name'      => $username
                    ),
                    'user' => array(
                        'id'            => $uuid,
                        'properties'    => array(
                            array(
                                'name'  => "preferredLanguage",
                                'value' => "ru-ru"
                            ),
                            array(
                                'name'  => "registrationCountry",
                                'value' => "RU"
                            )
                        )
                    )
                )
            );
        }

        request_die_json(
            array(
                'accessToken'       => $access_token,
                'clientToken'       => $client_token,
                'availableProfiles' => array(
                    array(
                        'name'      => $username,
                        'id'        => $uuid
                    )
                ),
                'selectedProfile'   => array(
                    'id'        => $uuid,
                    'name'      => $username
                )
            )
        );
    }

    function request_authserver_profile_refresh($uuid, $username, $access_token, $client_token, $requestUser)
    {
        if ($requestUser == true)
        {
            request_die_json(
                array(
                    'accessToken'       => $access_token,
                    'clientToken'       => $client_token,
                    'selectedProfile'   => array(
                        'id'        => $uuid,
                        'name'      => $username
                    ),
                    'user' => array(
                        'id'            => $uuid,
                        'properties'    => array(
                            array(
                                'name'  => "preferredLanguage",
                                'value' => "ru-ru"
                            ),
                            array(
                                'name'  => "registrationCountry",
                                'value' => "RU"
                            )
                        )
                    )
                )
            );
        }

        request_die_json(
            array(
                'accessToken'       => $access_token,
                'clientToken'       => $client_token,
                'selectedProfile'   => array(
                    'id'        => $uuid,
                    'name'      => $username
                )
            )
        );
    }

    // =========================================
    // SessionServer Request JSON
    // =========================================
    // Texture request
    function request_get_textures($skins_url, $skin_hash, $cloak_hash, $skin_type)
    {
        $results = array();

        if (!empty($skin_hash))
        {
            if ($skin_type == "1")
			{
				$results['SKIN'] = array (
					'url' => $skins_url.'skins/'.$skin_hash.".png",
					"metadata" => array (
                        "model" => "slim"
                    )
				);
			}
			else
			{
				$results['SKIN'] = array (
					'url' => $skins_url.'skins/'.$skin_hash.".png"
				);
			}
        }
        else 
        {
            if ($skin_type == "1")
            {
                $results['SKIN'] = array (
                    'url' => $skins_url.'default/51e80787e58b24fb2785895f0834543e.png',
                    "metadata" => array (
                        "model" => "slim"
                    )
                );
            }
            else
            {
                $results['SKIN'] = array (
                    'url' => $skins_url.'default/f4987d535f6f36d9165989eb87fc9e7b.png'
                );
            }
        }

        if (!empty($cloak_hash))
        {
            $results['CAPE'] = array (
                'url' => $skins_url.'cloaks/'.$cloak_hash.".png"
            );
        }
        else 
        {
            $results['CAPE'] = array (
				'url' => $skins_url.'default/e5bfc51833b5d38370761c29da7f2e61.png'
			);
        }

        return (Object)$results;
    }
    
    // Base64 request
    function request_get_base64($username, $uuid, $skins_url, $skin_hash, $cloak_hash, $skin_type)
    {
        if (!$unsigned) 
        {
            $base64 = json_encode(
                array(
                    'timestamp'         => time(),
                    'profileId'         => $uuid,
                    'profileName'       => $username,
                    'signatureRequired' => true,
                    'textures'          => request_get_textures($skins_url, $skin_hash, $cloak_hash, $skin_type)
                ), JSON_UNESCAPED_SLASHES
            );
        }
        else 
        {
            $base64 = json_encode(
                array(
                    'timestamp'     => time(),
                    'profileId'     => $uuid,
                    'profileName'   => $username,
                    'textures'      => request_get_textures($skins_url, $skin_hash, $cloak_hash, $skin_type)
                ), JSON_UNESCAPED_SLASHES
            );
        }
        return base64_encode($base64);
    }

    // Session request
    function request_get_session_profile($username, $uuid, $skins_url, $skin_hash, $cloak_hash, $skin_type, $unsigned)
    {
		$request = request_get_base64($username, $uuid, $skins_url, $skin_hash, $cloak_hash, $skin_type);
		
        if (!$unsigned) {
            request_die_json(
                array (
                    'id' => $uuid,
                    'name' => $username,
                    'properties' => array(
                        array(
                            'name'      => "textures",
                            'value'     => $request,
							'signature' => getSignature($request)
                        )
                    )
                )
            );
        }

        request_die_json(
            array (
                'id' => $uuid,
                'name' => $username,
                'properties' => array(
                    array(
                        'name'      => "textures",
                        'value'     => $request
                    )
                )
            )
        );
    }