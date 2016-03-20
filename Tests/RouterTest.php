<?php

class RouterTest extends PHPUnit_Framework_TestCase
{

    // TODO: test matchClass

    static public function mainPageAction($_mode){
        if ($_mode == \OLOG\Router::GET_URL) return '/';
        if ($_mode == \OLOG\Router::GET_METHOD) return __METHOD__;

        return 'MAIN_ACTION_DONE';
    }

    static public function nodePageAction($_mode, $node_id = '(\d+)'){
        if ($_mode == \OLOG\Router::GET_URL) return '/node/' . $node_id;
        if ($_mode == \OLOG\Router::GET_METHOD) return __METHOD__;

        return 'NODE_ACTION_DONE';
    }

    static public function gamePageAction($_mode, $game_id = '(\d+)'){
        if ($_mode == \OLOG\Router::GET_URL) return '/game/' . $game_id;
        if ($_mode == \OLOG\Router::GET_METHOD) return __METHOD__;

        return 'GAME_ACTION_DONE';
    }

    public function testMatch3()
    {
        $_SERVER['REQUEST_URI'] = '/';

        $action_result = \OLOG\Router::match3(self::mainPageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals('MAIN_ACTION_DONE', $action_result);

        $action_result = \OLOG\Router::match3(self::nodePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

        $action_result = \OLOG\Router::match3(self::gamePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);



        $_SERVER['REQUEST_URI'] = '/node/25';

        $action_result = \OLOG\Router::match3(self::mainPageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

        $action_result = \OLOG\Router::match3(self::nodePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals('NODE_ACTION_DONE', $action_result);

        $action_result = \OLOG\Router::match3(self::gamePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);



        $_SERVER['REQUEST_URI'] = '/node/abc';

        $action_result = \OLOG\Router::match3(self::mainPageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

        $action_result = \OLOG\Router::match3(self::nodePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

        $action_result = \OLOG\Router::match3(self::gamePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);



        $_SERVER['REQUEST_URI'] = '/kbhkdhg';

        $action_result = \OLOG\Router::match3(self::mainPageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

        $action_result = \OLOG\Router::match3(self::nodePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

        $action_result = \OLOG\Router::match3(self::gamePageAction(\OLOG\Router::GET_METHOD), 0, true);
        $this->assertEquals(false, $action_result);

    }
}