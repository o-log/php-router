<?php

namespace PHPRouterDemo;

class DemoMainPageAction
{
    static public function getUrl(){
        return '/';
    }
    
    static public function action(){
        DemoMainPageTemplate::render();
    }
}