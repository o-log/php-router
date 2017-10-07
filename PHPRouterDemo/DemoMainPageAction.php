<?php

namespace PHPRouterDemo;

use OLOG\SimpleActionInterface;

class DemoMainPageAction implements SimpleActionInterface
{
    public function url(){
        return '/';
    }
    
    public function action(){
        require 'main_page.php';
    }
}