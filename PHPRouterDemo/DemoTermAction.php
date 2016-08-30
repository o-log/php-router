<?php

namespace PHPRouterDemo;

use OLOG\InterfaceAction;

class DemoTermAction implements
    InterfaceAction
{
    protected $term_id;

    public function __construct($term_id = '(\d+)')
    {
        $this->term_id = $term_id;
    }

    public function url(){
        $term_id = $this->term_id;
        return '/term/' . $term_id;
    }

    public function action(){
        echo '<div>TERM ' . $this->term_id . '</div>';
    }
}