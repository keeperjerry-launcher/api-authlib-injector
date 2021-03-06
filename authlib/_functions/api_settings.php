<?php
    /* 
    =================================================
        Общая конфигурация и настройка API сайта    
    ================================================= 
    */
    $config = array(
        // =========================================
        // Общая настройка сервера
        // =========================================
        'debug'                     => true,        // Поставить `false` когда настройка будет окончена
        'server_name'               => "KeeperJerry Authlib API",                       // Имя сервера
        'server_description'        => "Authlib API for the authlib-injector library",  // Описание сервера
        'server_version'            => "0.1.0-alpha",                                   // Версия сервера (не менять)
        'server_mojang_namespace'   => true,                                            // Включить namespace Mojang
        'server_link_homepage'      => "https://example.com",                           // Домашняя страница сайта
        'server_link_register'      => "https://example.com/index.php?do=register",     // Ссылка на регистрацию
        'server_domain'             => "example.com",                                   // Домен сайта для скинов
        
        // Настройка скинов
        'server_url_skins'          => "https://example.com/textures/",

        'server_default_skins'      => true, // Включить ли скин по умолчанию? ( `true` - да, `false` - нет)
        'server_default_cloak'      => true, // Включить ли плащ по умолчанию? ( `true` - да, `false` - нет)

        // =========================================
        // Настройка SQL соединения 
        // =========================================
        'sql_db_host'               => "127.0.0.1",     // IP или домен базы данных
        'sql_db_database'           => "database",      // Имя базы данных
        'sql_db_user'               => "user",          // Пользователь базы данных
        'sql_db_password'           => "password",      // Пароль базы данных
        // Требуемые права: SELECT и UPDATE

        // Переменные таблицы SQL
        'sql_db_table'              => "dle_users",         // Таблица с пользователями
        
        // Переменные SQL столбцов в таблице DLE
        'sql_id' 				    => "user_id",           // Столбец с ID пользователя
        'sql_username'			    => "name",              // Столбец с никнеймом пользователя
        'sql_email'				    => "email",             // Столбец с почтой пользователя
        'sql_password'			    => "password",          // Столбец с паролем пользователя

        // Переменные SQL столбцов в таблице MC
        'sql_uuid' 				    => "mc_uuid",           // Столбец с UUID пользователя
        'sql_access_token' 		    => "mc_access_token",   // Столбец с токеном доступа пользователя
        'sql_client_token' 		    => "mc_client_token",   // Столбец с токеном лаунчера пользователя
        'sql_server_id' 		    => "mc_server_id",      // Столбец с ID сервера пользователя

        'sql_skin_hash'             => "mc_skin_hash",      // Столбец с хешем скина
        'sql_cloak_hash'            => "mc_cloak_hash",     // Столбец с хешем плаща
		'sql_skin_type'				=> "mc_skin_type"		// Столбец с типом скина alex/steve
    );