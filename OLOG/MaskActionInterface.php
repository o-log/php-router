<?php

namespace OLOG;

/**
 * Экшен не умеет сам проверять его ли это урл и получать контекст из урла,
 * отдает маску урла.
 */
interface MaskActionInterface
{
    public function action();
    public function url();
    static public function mask();
}