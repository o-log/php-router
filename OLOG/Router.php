<?php

namespace OLOG;

class Router
{
    const CONTINUE_ROUTING = 'CONTINUE_ROUTING';

    const GET_URL = 'GET_URL';
    const GET_METHOD = 'GET_METHOD';
    const EXECUTE_ACTION = 'EXECUTE_ACTION';

    static protected $current_action_obj = null; // текущий (т.е. последний созданный) объект экшена
    //static protected $current_action_method_name = ''; // имя функции текущего (т.е. последнего вызванного) экшена (без класса)
    //static protected $current_controller_class_name = ''; // имя класса текущего (т.е. последнего вызванного) контроллера

    // TODO: describe, add getter+setter
    static $url_prefix = '';

    /**
     * Простой метод проверки, соответствует ли запрошенный урл указанной маске. Может использоваться для группировки роутов.
     * @param $url_regexp
     * @return bool
     */
    static public function matchGroup($url_regexp)
    {
        $current_url = self::uri_no_getform();

        if (!preg_match($url_regexp, $current_url)) {
            return false;
        }

        return true;
    }

    static public function getCurrentActionObj()
    {
        return self::$current_action_obj;
    }

    protected static function getDefaultCacheLifetime()
    {
      return 60;
    }


    /**
     * @deprecated
     * @param $action_class_name
     * @param null $cache_seconds_for_headers
     * @param bool $return_action_result_instead_of_exit
     * @return bool
     */
    static public function matchClass($action_class_name, $cache_seconds_for_headers = null, $return_action_result_instead_of_exit = false)
    {

        // TODO: check action class interfaces

        //
        // получение маски адреса экшена вызовом самого экшена
        //

        // url_perfix позволяет работать в папке
        $url_str = self::$url_prefix . $action_class_name::getUrl();
        $url_regexp = '@^' . $url_str . '$@';

        //
        // проверка соответствия запрошенного адреса маске экшена и извлечение параметров экшена
        //

        $matches_arr = array();
        $current_url = self::uri_no_getform();

        if (!preg_match($url_regexp, $current_url, $matches_arr)) {
            return false;
        }

        if (count($matches_arr)) {
            array_shift($matches_arr); // убираем первый элемент массива - содержит всю сматченую строку
        }

        //
        // установка хидеров кэширования
        //

        if (is_null($cache_seconds_for_headers)) { // кэширование страницы по умолчанию
            $cache_seconds_for_headers = self::getDefaultCacheLifetime();
        }

        self::cacheHeaders($cache_seconds_for_headers);

        //
        // декодирование параметров экшена, полученных из урла
        //

        $decoded_matches_arr = array();
        foreach ($matches_arr as $arg_value) {
            $decoded_matches_arr[] = urldecode($arg_value);
        }

        //
        // TODO: сохранение текущего контроллера и экшена, чтобы другой код мог их использовать для проверки какой экшен работает или для получения контекста с помощью методов контроллера
        //

        //list($controller_class_name, $action_method_name) = explode('::', $method);
        //self::$current_action_method_name = $method;
        //self::$current_controller_class_name = $controller_class_name;

        $action_params_arr = $decoded_matches_arr;
        $action_result = call_user_func_array(array($action_class_name, 'action'), $action_params_arr);

        //
        // TODO: сбрасываем текущий контроллер - он больше не актуален
        //

        //self::$current_controller_obj = null;
        //self::$current_action_method_name = null;
        //self::$current_controller_class_name = null;

        //
        // проверка результата экшена - нужно ли завершать работу
        //

        if ($action_result === self::CONTINUE_ROUTING) {
            return $action_result;

        }

        if ($return_action_result_instead_of_exit) {
            return $action_result;
        }

        exit;
    }

    static public function processAction($action_class_name, $cache_seconds_for_headers = 60, $return_action_result_instead_of_exit = false)
    {
        // TODO: check InterfaceAction

        // создаем объект экшена без контекста, чтобы получить из него маску адреса
        $action_obj = new $action_class_name;

        // url_perfix позволяет работать в папке
        $url_str = self::$url_prefix . $action_obj->url();
        $url_regexp = '@^' . $url_str . '$@';

        //
        // проверка соответствия запрошенного адреса маске экшена и извлечение параметров экшена
        //

        $matches_arr = array();
        $current_url = Url::getCurrentUrlNoGetForm();

        if (!preg_match($url_regexp, $current_url, $matches_arr)) {
            return false;
        }

        if (count($matches_arr)) {
            array_shift($matches_arr); // убираем первый элемент массива - содержит всю сматченую строку
        }

        //
        // установка хидеров кэширования
        //

        self::cacheHeaders($cache_seconds_for_headers);

        //
        // декодирование параметров экшена, полученных из урла
        //

        $decoded_matches_arr = array();
        foreach ($matches_arr as $arg_value) {
            $decoded_matches_arr[] = urldecode($arg_value);
        }

        //
        // создание объекта экшена и вызов метода action
        //

        //$action_obj = new $action_class_name($decoded_matches_arr);
        $reflect  = new \ReflectionClass($action_class_name);
        $action_obj = $reflect->newInstanceArgs($decoded_matches_arr);

        self::$current_action_obj = $action_obj;
        $action_result = $action_obj->action();

        //
        // TODO: сбрасываем текущий экшен - он больше не актуален
        //

        self::$current_action_obj = null;

        //
        // проверка результата экшена - нужно ли завершать работу
        //

        if ($action_result === self::CONTINUE_ROUTING) {
            return $action_result;

        }

        if ($return_action_result_instead_of_exit) {
            return $action_result;
        }

        exit;
    }

    static public function matchAction($action_class_name, $cache_seconds_for_headers = null, $return_action_result_instead_of_exit = false)
    {

        // TODO: check action class interfaces
        // пока не получится - у методов экшена переменное число параметров

        //
        // получение маски адреса экшена вызовом самого экшена
        //

        // url_perfix позволяет работать в папке
        $url_str = self::$url_prefix . $action_class_name::getUrl();
        $url_regexp = '@^' . $url_str . '$@';

        //
        // проверка соответствия запрошенного адреса маске экшена и извлечение параметров экшена
        //

        $matches_arr = array();
        $current_url = self::uri_no_getform();

        if (!preg_match($url_regexp, $current_url, $matches_arr)) {
            return false;
        }

        if (count($matches_arr)) {
            array_shift($matches_arr); // убираем первый элемент массива - содержит всю сматченую строку
        }

        //
        // установка хидеров кэширования
        //

        if (is_null($cache_seconds_for_headers)) { // кэширование страницы по умолчанию
            $cache_seconds_for_headers = self::getDefaultCacheLifetime();
        }

        self::cacheHeaders($cache_seconds_for_headers);

        //
        // декодирование параметров экшена, полученных из урла
        //

        $decoded_matches_arr = array();
        foreach ($matches_arr as $arg_value) {
            $decoded_matches_arr[] = urldecode($arg_value);
        }

        //
        // сохранение текущего экшена, чтобы другой код мог их использовать для проверки какой экшен работает или для получения контекста с помощью методов экшена
        //

        $action_obj = new $action_class_name;

        self::$current_action_obj = $action_obj;

        $action_params_arr = $decoded_matches_arr;
        $action_result = call_user_func_array(array($action_obj, 'action'), $action_params_arr);

        //
        // TODO: сбрасываем текущий экшен - он больше не актуален
        //

        self::$current_action_obj = null;

        //
        // проверка результата экшена - нужно ли завершать работу
        //

        if ($action_result === self::CONTINUE_ROUTING) {
            return $action_result;

        }

        if ($return_action_result_instead_of_exit) {
            return $action_result;
        }

        exit;
    }

    static public function cacheHeaders($seconds = 0)
    {
        if (php_sapi_name() !== "cli") {
            if ($seconds) {
                header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
                header('Cache-Control: max-age=' . $seconds . ', public');
            } else {
                header('Expires: ' . gmdate('D, d M Y H:i:s', date('U') - 86400) . ' GMT');
                header('Cache-Control: no-cache');
            }
        }

    }

    /**
     * @deprecated 
     * @return mixed
     */
    static public function uri_no_getform()
    {
        return Url::getCurrentUrlNoGetForm();
    }
}
