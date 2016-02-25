<?php

namespace PHPRouterDemo;

class DemoController
{
    static public function mainPageAction($_mode){
        if ($_mode == \OLOG\Router::GET_URL) return '/';
        if ($_mode == \OLOG\Router::GET_METHOD) return __METHOD__;

        MainPageTemplate::render();
    }

    static public function nodeAction($_mode, $node_id = '(\d+)'){
        if ($_mode == \OLOG\Router::GET_URL) return '/node/' . $node_id;
        if ($_mode == \OLOG\Router::GET_METHOD) return __METHOD__;

        NodePageTemplate::render($node_id);
    }
}