<?php
    /* 
    =================================================
        Общая конфигурация и настройка API сайта    
    =================================================
    */
    $config = array(
        // Подрубить дебаг
        'debug' => true,

        // Общая настройка сервера
        'server_name' => "KeeperJerry Authlib API", // Имя сервера
        'server_description' => "Authlib API for the authlib-injector library", // Описание сервера
        'server_version' => "1.0.0", // Версия сервера
        'server_mojang_namespace' => true, // Включить namespace Mojang
        'server_link_homepage' => "https://example.com", // Домашняя страница сайта
        'server_link_register' => "https://example.com/index.php?do=register",  // Ссылка на регистрацию
        'server_domain' => "example.com", // Домен сайта для скинов
    );