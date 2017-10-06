<?php

namespace PHPRouterDemo;

use OLOG\MaskActionInterface;

class DemoNodeAction implements MaskActionInterface
{
    protected $node_id;

    public function __construct($node_id)
    {
        $this->node_id = $node_id;
    }

    static public function mask(){
        return '/node/(\d+)';
    }

    public function url(){
        return '/node/' . $this->node_id;
    }

    public function action(){
        DemoNodePageTemplate::render($this->node_id, $this);
    }

    public function getH1(){
        return 'Node ' . $this->node_id;
    }
}