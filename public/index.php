<?php

require_once '../vendor/autoload.php';

use OLOG\Router;

Router::matchAction(\PHPRouterDemo\DemoMainPageAction::class, 0);
Router::matchAction(\PHPRouterDemo\DemoNodeAction::class, 0);