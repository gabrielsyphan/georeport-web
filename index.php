<?php

session_start();

header('Access-Control-Allow-Origin: *');

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(ROOT);

/*
 * Contorllers
 */

$router->namespace("Source\App");

/*
 * Web
 */
$router->group(null);
$router->get("/", "Web:home", 'web.home');

$router->get("/login", "Web:login", 'web.login');
$router->post("/validateLogin", "Web:validateLogin", 'web.validateLogin');
$router->get("/logout", "Web:logout", 'web.logout');

$router->get("/listReports", "Web:listReports", 'web.listReports');
$router->get("/exportData", "Web:exportData", "web.exportData");
$router->get("/reports", "Web:reports", 'web.reports');
$router->post("/validateReport", "Web:validateReport", 'web.validateReport');

$router->get("/openFile/{fileName}", "Web:openFile", "web.openFile");
$router->post("/deleteAttach", "Web:deleteAttach", 'web.deleteAttach');
$router->post("/validateUpload", "Web:validateUpload", "web.validateUpload");
$router->get("/reportInfo/{id}", "Web:reportInfo", 'web.reportInfo');
$router->post("/deleteReport", "Web:deleteReport", 'web.deleteReport');
$router->post("/validateNotification", "Web:validateNotification", 'web.validateNotification');

$router->get("/profile", "Web:profile", 'web.profile');

/*
 * ERROS
 */
$router->group("ooops");
$router->get("/{errcode}", "Web:error");

/**
 * PROCESS
 */
$router->dispatch();

if ($router->error()) {
	$router->redirect("/ooops/{$router->error()}");
}
