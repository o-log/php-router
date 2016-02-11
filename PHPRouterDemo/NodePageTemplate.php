<?php

namespace PHPRouterDemo;

use OLOG\Router;

class NodePageTemplate
{
    static public function render($node_id){
        echo '<h1><a href="' . DemoController::mainPageAction(Router::GET_URL) . '">Main page</a> / Node ' . $node_id . '</h1>';

        echo '<p>Node content.</p>';
    }
}