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
    function request_get_textures($skins_url, $skin_hash, $cloak_hash)
    {
        if (empty($skin_hash))
        {
            $result_skin = array(
                'url' => $skins_url."skins/defaultSkin_other.png"
            );
        }
        else
        {
            $result_skin = array (
                'url' => $skins_url.'skins/'.$skin_hash.".png"
            );
        }

        if (empty($cloak_hash))
        {
            return array (
                'SKIN'  => $result_skin
            );
        }
        else
        {
            return array (
                'SKIN'  => $result_skin,
                'CAPE'  => array (
                    'url' => $skins_url.'cloaks/'.$cloak_hash.".png"
                )
            );
        }
    }
    
    // Base64 request
    function request_get_base64($username, $uuid, $skins_url, $skin_hash, $cloak_hash)
    {
        $base64 = json_encode(
            array(
                'timestamp'     => time(),
                'profileId'     => $uuid,
                'profileName'   => $username,
                'textures'      => request_get_textures($skins_url, $skin_hash, $cloak_hash)
            ), JSON_UNESCAPED_SLASHES
        );
        return base64_encode($base64);
    }

    // Session request
    function request_get_session_profile($username, $uuid, $skins_url, $skin_hash, $cloak_hash)
    {
        request_die_json(
            array (
                'id' => $uuid,
                'name' => $username,
                'properties' => array(
                    array(
                        'name'      => "textures",
                        'value'     => request_get_base64($username, $uuid, $skins_url, $skin_hash, $cloak_hash)
                    )
                )
            )
        );
    }