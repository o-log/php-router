<?php

namespace PHPRouterDemo;

class DemoController
{
    static public function mainPageAction($mode){
        $self_url = '/';
        if ($mode == \OLOG\Router::GET_URL) return $self_url;
        if ($mode == \OLOG\Router::GET_METHOD) return array(__METHOD__, $self_url);

        MainPageTemplate::render();
    }

    static public function nodeAction($mode, $node_id){
        $self_url = '/node/' . $node_id;
        if ($mode == \OLOG\Router::GET_URL) return $self_url;
        if ($mode == \OLOG\Router::GET_METHOD) return array(__METHOD__, $self_url);

        NodePageTemplate::render($node_id);
    }
}