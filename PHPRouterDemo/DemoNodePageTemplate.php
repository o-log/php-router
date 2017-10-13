<?php

namespace PHPRouterDemo;

class DemoNodePageTemplate
{
    static public function render($node_id, $action_obj){
        echo '<h1><a href="' . (new DemoMainPageAction())->url() . '">Main page</a> / ' . $action_obj->getH1() . '</h1>';

        echo '<p>Node ' . $node_id . ' content.</p>';
    }
}