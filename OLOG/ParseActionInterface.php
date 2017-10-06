<?php

namespace OLOG;

interface ParseActionInterface
{
    public function action();
    public function url();

    /**
     * Метод должен проверить, может ли экшен обработать запрошенный урл.
     * Если может - метод должен извлечь из урла параметры экшена, создать объект экшена с этими параметрами и вернуть его.
     * Если не может - метод должен вернуть null.
     * @param $requested_url
     * @return mixed
     */
    static public function parse($url);
}