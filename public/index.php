<?php

require_once '../vendor/autoload.php';

use OLOG\Router;

//\OLOG\Router::match2(\PHPRouterDemo\DemoController::mainPageAction(\OLOG\Router::GET_METHOD));
//\OLOG\Router::match2(\PHPRouterDemo\DemoController::nodeAction(\OLOG\Router::GET_METHOD, '(\d+)'));

Router::match3(\PHPRouterDemo\DemoController::mainPageAction(Router::GET_METHOD));
Router::match3(\PHPRouterDemo\DemoController::nodeAction(Router::GET_METHOD));
