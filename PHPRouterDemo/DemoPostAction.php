<?php

namespace PHPRouterDemo;

use OLOG\ActionInterface;

class DemoPostAction implements ActionInterface
{
    public function url(){
        return '/post_action';
    }

    public function action(){
        echo 'post action success';
    }
}
