<?php

namespace PHPRouterDemo;

class DemoMainPageAction
{
    static public function getUrl(){
        return '/';
    }
    
    public function action(){
        DemoMainPageTemplate::render();
    }
}