<?php

namespace OLOG;

/**
 * интерфейс пока не внедряется, потому что методы action и getUrl принимают переменное количество параметров
 * Interface InterfaceRouter
 * @package OLOG
 */
interface InterfaceAction
{
    static public function action();
    static public function getUrl();
}