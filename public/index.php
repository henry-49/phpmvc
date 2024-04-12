<?php

declare(strict_types=1);

define("ROOT_PATH", dirname(__DIR__));

spl_autoload_register(function (string $class_name) {
    // var_dump("src/" . str_replace("\\", "/", $class_name) . ".php");
    // require "src/$class_name.php";

    require ROOT_PATH . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});

$donenv = new Framework\Dotenv;
$donenv->load(ROOT_PATH . "/.env");


// Add an error handler to convert errors to exceptions
set_error_handler("Framework\ErrorHandler::handleError");


// define an exception handler to handle all exceptions
set_exception_handler("Framework\ErrorHandler::handleException");


// test url rewriting
//exit("Hello from Index.php");


// require "src/router.php";

$router = require ROOT_PATH . "/config/routes.php";

// object of container
// binding the database class to service container
$container = require ROOT_PATH . "/config/services.php";


$dispatcher = new Framework\Dispatcher($router, $container);

$request = Framework\Request::createFromGlobals();

$dispatcher->handle($request);