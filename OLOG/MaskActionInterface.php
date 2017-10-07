<?php

namespace OLOG;

/**
 * Экшен не умеет сам проверять его ли это урл и получать контекст из урла,
 * отдает маску урла.
 */
interface MaskActionInterface extends ActionInterface
{
    static public function mask();
}