<?php

require_once '../vendor/autoload.php';

\OLOG\Router::match2(\PHPRouterDemo\DemoController::mainPageAction(\OLOG\Router::GET_METHOD));
\OLOG\Router::match2(\PHPRouterDemo\DemoController::nodeAction(\OLOG\Router::GET_METHOD, '(\d+)'));