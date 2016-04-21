<?php

namespace PHPRouterDemo;

class DemoNodeAction
{
    protected $node_id;

    static public function getUrl($node_id = '(\d+)'){
        return '/node/' . $node_id;
    }
    
    public function action($node_id){
        $this->node_id = $node_id;
        DemoNodePageTemplate::render($node_id, $this);
    }

    public function getH1(){
        return 'Node ' . $this->node_id;
    }
}