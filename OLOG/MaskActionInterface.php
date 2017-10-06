<?php

namespace OLOG;

/**
 * Экшен не умеет сам проверять его ли это урл и получать контекст из урла,
 * отдвет маску урла.
 */
interface MaskActionInterface
{
    public function action();
    public function url();
    static public function mask();
}