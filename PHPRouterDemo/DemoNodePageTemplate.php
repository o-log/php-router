<?php

namespace PHPRouterDemo;

use OLOG\Router;

class DemoNodePageTemplate
{
    static public function render($node_id){
        echo '<h1><a href="' . DemoMainPageAction::getUrl() . '">Main page</a> / Node ' . $node_id . '</h1>';

        echo '<p>Node content.</p>';
    }
}