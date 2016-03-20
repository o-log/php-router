<?php

namespace PHPRouterDemo;

class NodeAction
{
    static public function getUrl($node_id = '(\d+)'){
        return '/class_actions/node/' . $node_id;
    }
    
    static public function action($node_id){
        NodePageTemplate::render($node_id);
    }
}