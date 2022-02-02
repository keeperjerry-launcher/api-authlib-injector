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

    function request_authserver_profile($uuid, $username, $access_token, $client_token, $requestUser)
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
                            'id'        => str_replace('-', '', $uuid)
                        )
                    ),
                    'selectedProfile'   => array(
                        'id'        => str_replace('-', '', $uuid),
                        'name'      => $username,
                        'legacy'    => false
                    ),
                    'user' => array(
                        'id'            => str_replace('-', '', $uuid),
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
                        'id'        => str_replace('-', '', $uuid)
                    )
                ),
                'selectedProfile'   => array(
                    'id'        => str_replace('-', '', $uuid),
                    'name'      => $username,
                    'legacy'    => false
                )
            )
        );
    }