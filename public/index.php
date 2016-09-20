<?php

date_default_timezone_set('Europe/Moscow');

require_once '../vendor/autoload.php';

use OLOG\Router;

Router::matchAction(\PHPRouterDemo\DemoMainPageAction::class, 0);
Router::matchAction(\PHPRouterDemo\DemoNodeAction::class, 0);

Router::processAction(\PHPRouterDemo\DemoTermAction::class);
Router::processAction(\PHPRouterDemo\DemoMagicAction::class);