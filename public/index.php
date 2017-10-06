<?php

// no separate config, so place it here
date_default_timezone_set('Europe/Moscow');

require_once '../vendor/autoload.php';

use OLOG\Router;

Router::action(\PHPRouterDemo\DemoMainPageAction::class, 0);
Router::action(\PHPRouterDemo\DemoNodeAction::class, 0);

Router::action(\PHPRouterDemo\DemoTermAction::class);
Router::action(\PHPRouterDemo\DemoMagicAction::class);