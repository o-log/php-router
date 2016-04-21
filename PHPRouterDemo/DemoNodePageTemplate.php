<?php

namespace PHPRouterDemo;

use OLOG\Router;

class DemoNodePageTemplate
{
    static public function render($node_id, $action_obj){
        echo '<h1><a href="' . DemoMainPageAction::getUrl() . '">Main page</a> / ' . $action_obj->getH1() . '</h1>';

        echo '<p>Node ' . $node_id . ' content.</p>';
    }
}