<?php

define("ROOT", "http://localhost/georeport");
define("THEMES", __DIR__."/../themes");
define("SITE", "#GEO-REPORT");

/**
 * Database config
 */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "georeports",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

/**
 * Email config
 */
define("MAIL", [
    "host" => "contato@moremedia.com.br",
    "port" => "465",
    "user" => "contato@moremedia.com.br",
    "passwd" => "PIzJ(KW#+j-2",
    "from_name" => "More Media",
    "from_email" => "contato@moremedia.com.br"
]);

//define("MAIL", [
//    "host" => "smtp.gmail.com",
//    "port" => "587",
//    "user" => "contatomoremedia@gmail.com",
//    "passwd" => "moremediacapture123",
//    "from_name" => "More Media",
//    "from_email" => "contatomoremedia@gmail.com"
//]);

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri) {
        return ROOT . "/{$uri}";
    }

    return ROOT;
}
