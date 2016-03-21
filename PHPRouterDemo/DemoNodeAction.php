<?php

namespace PHPRouterDemo;

class DemoNodeAction
{
    static public function getUrl($node_id = '(\d+)'){
        return '/node/' . $node_id;
    }
    
    static public function action($node_id){
        DemoNodePageTemplate::render($node_id);
    }
}