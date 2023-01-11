<?php

require_once('config.php');
require_once('functions.php');

$route = $_GET['route'] ?? 'HomeController:home';
list($controller, $method) = explode(':', $route); 

require_once "controllers/$controller.php";
$controller = explode('/', $controller);
$controller = end($controller);

$instance = new $controller;
$instance::$method();

logPageView();