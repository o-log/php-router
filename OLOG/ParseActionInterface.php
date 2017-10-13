<?php

namespace OLOG;

interface ParseActionInterface extends ActionInterface
{
    /**
     * Метод должен проверить, может ли экшен обработать запрошенный урл.
     * Если может - метод должен извлечь из урла параметры экшена, создать объект экшена с этими параметрами и вернуть его.
     * Если не может - метод должен вернуть null.
     */
    static public function parse(string $url);
}