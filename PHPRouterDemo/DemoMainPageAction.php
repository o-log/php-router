<?php

namespace PHPRouterDemo;

use OLOG\SimpleActionInterface;

class DemoMainPageAction implements SimpleActionInterface
{
    public function url(){
        return '/';
    }
    
    public function action(){
        echo \OLOG\Render::callLocaltemplate('main_page.php');
    }
}