<?php

namespace PHPRouterDemo;

use OLOG\Router;

class DemoMainPageTemplate
{
    static public function render(){
        echo '<h1>MAIN PAGE</h1>';

        echo '<p><a href="' . (new DemoNodeAction(1))->url() . '">Node 1 - class action</a></p>';
        echo '<p><a href="' . (new DemoNodeAction(2))->url() . '">Node 2 - class action</a></p>';
        echo '<p><a href="' . (new DemoTermAction(1))->url() . '">Term 1 - process action</a></p>';
        echo '<p><a href="' . (new DemoMagicAction('demo_magic'))->url() . '">Magic - process action</a></p>';
    }
}