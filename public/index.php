<?php

require_once '../vendor/autoload.php';

use OLOG\Router;

Router::matchClass(\PHPRouterDemo\DemoMainPageAction::class, 0);
Router::matchClass(\PHPRouterDemo\DemoNodeAction::class, 0);