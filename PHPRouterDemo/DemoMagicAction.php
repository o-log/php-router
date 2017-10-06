<?php

namespace PHPRouterDemo;

use OLOG\ParseActionInterface;

/**
 * Class DemoMagicAction
 *
 * Этот экшен обрабатывает все урлы вида /magic/MAGIC_NAME
 * Где MAGIC_NAME - любая строка
 *
 * @package PHPRouterDemo
 */
class DemoMagicAction implements ParseActionInterface
{
    protected $magic_name;

    /**
     * @return mixed
     */
    public function getMagicName()
    {
        return $this->magic_name;
    }

    /**
     * @param mixed $magic_name
     */
    public function setMagicName($magic_name)
    {
        $this->magic_name = $magic_name;
    }

    /**
     * DemoMagicAction constructor.
     * @param $magic_name
     */
    public function __construct($magic_name){
        $this->magic_name = $magic_name;
    }

    public function url() : string {
        return '/magic/' . $this->magic_name;
    }

    public function action()
    {
        echo '<h1><a href="' . (new DemoMainPageAction())->url() . '">Main page</a> / Magic</h1>';
        echo '<div>MAGIC NAME: ' . $this->magic_name . '</div>';
        echo '<div>Add something to url to see how the action extracts parameters.</div>';
    }

    /**
     * @param $requested_url
     * @return null|DemoMagicAction
     */
    static public function parse($requested_url) {
        //
        // проверка соответствия запрошенного адреса маске экшена и извлечение параметров экшена
        //

        $matches_arr = array();
        if (!preg_match('@^/magic/(.+)$@', $requested_url, $matches_arr)) {
            return null;
        }

        $magic_name = $matches_arr[1];

        return new DemoMagicAction($magic_name);
    }

}