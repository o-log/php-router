<?php

namespace PHPRouterDemo;

use OLOG\Router;

class MainPageTemplate
{
    static public function render(){
        echo '<h1>MAIN PAGE</h1>';

        echo '<p><a href="' . DemoController::nodeAction(Router::GET_URL, 1). '">Node 1</a> <a href="' . NodeAction::getUrl(1). '">Node 1 - class action</a></p>';
        echo '<p><a href="' . DemoController::nodeAction(Router::GET_URL, 2). '">Node 2</a> <a href="' . NodeAction::getUrl(2). '">Node 2 - class action</a></p>';
    }
}