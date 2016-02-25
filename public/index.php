<?php

require_once '../vendor/autoload.php';

use OLOG\Router;

Router::match3(\PHPRouterDemo\DemoController::mainPageAction(Router::GET_METHOD));
Router::match3(\PHPRouterDemo\DemoController::nodeAction(Router::GET_METHOD));
