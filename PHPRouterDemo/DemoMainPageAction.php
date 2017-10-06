<?php

namespace PHPRouterDemo;

class DemoMainPageAction implements \OLOG\InterfaceAction
{
    public function url(){
        return '/';
    }
    
    public function action(){
        DemoMainPageTemplate::render();
    }
}