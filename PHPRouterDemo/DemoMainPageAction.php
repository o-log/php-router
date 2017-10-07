<?php

namespace PHPRouterDemo;

use OLOG\ActionInterface;

class DemoMainPageAction implements ActionInterface
{
    public function url(){
        return '/';
    }
    
    public function action(){
        require 'main_page.php';
    }
}