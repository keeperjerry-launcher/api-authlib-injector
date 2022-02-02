# API для Authlib-Injector

Данный API нужен для облегчения привязки сервера и клиента к лаунчеру. Т.к. этот скрипт сделан "из коробки" для DLE, то получайте скрипт на PHP. Я не долбаеб выкладывать исходники нормального API на nodejs, чтобы его потом каждый недо-ютубер выдавал за свой.

## Инструкция по установке

1) Перейти к файлу `api_settings.php` по пути `authlib/_functions/` и настроить его.

2) Закинуть папку `authlib` (без переименовывания) в корень сайта DLE.

3) В настрйоках лаунчера указать настройки:
```java
# Настрйока API authlib-injector под лаунчер

authHandler: "authlib-injector";
authHandlerConfig: {
    urlApiInjector: "https://example.com/authlib";
};

authProvider: "authlib-injector";
authProviderConfig: {
    urlApiInjector: "https://example.com/authlib";
};

textureProvider: "authlib-injector";
textureProviderConfig: {
    urlApiInjector: "https://example.com/authlib";
};
```
ссылка на ваш сайт - `https://example.com`, где:
* `https` - протокол использования сайта (используете ли вы `http` или `https`)
* `api.example.com` - домен вашего сайта для API
* Обратите ваше внимание, что `/` в конце указывать не нужно!

4) Чтобы привязать игровой сервер к API - нужно скачать сам `authlib-injector` [отсюда](https://github.com/yushijinhun/authlib-injector/releases), положить в папку с игровым сервером и вписать следующую команду:
```java
java -javaagent:authlib-injector-1.1.40.jar=https://example.com/authlib -jar minecraft_server.jar
```
где:
* `authlib-injector-1.1.40.jar` - сам файл `authlib-injector.jar`
* `example.com` - домен вашего сайта
* `minecraft_server.jar` - файл игрового сервера

## Личный комментарий

Чтобы переадресовать со своего сайта `example.com` на API Authlib Injector, можно добавить в заголовок вашего DLE параметр `X-Authlib-Injector-API-Location`:
* `X-Authlib-Injector-API-Location: /authlib/` - сервер перейдет на сервер по адресу `example.com/authlib/`.
* `X-Authlib-Injector-API-Location: https://api.example.com/authlib/` - сервер переадресует на указанный домен.

Лаунчер сервер к сожалению пока не умеет определять данный параметр (мб оно и к лучшему).
Может быть напишу простенький кабинет под это дело, чтобы он закидывал скины и плащи по хешу (майновская реализация).
Но я это не обещаю.

Скрипт может быть немного дырявым, но ишью всегда открыт. 
И ДА, ПОВТОРЯЮ, ЧТО Я ПИСАЛ ЭТОТ СКРИПТ НА ОТЬЕБИСЬ, ИБО PHP!