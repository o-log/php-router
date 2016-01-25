<?php

namespace OLOG;

class Router
{
    const CONTINUE_ROUTING = 'CONTINUE_ROUTING';

    static public $current_controller_obj = null; // текущий (т.е. послдений созданный) объект контроллера
    static protected $current_action_method_name = ''; // имя функции текущего (т.е. последнего вызванного) экшена (без класса)
    static protected $current_controller_class_name = ''; // имя класса текущего (т.е. последнего вызванного) контроллера

    static public function matchGroup($url_regexp)
    {
        $current_url = self::uri_no_getform();

        if (!preg_match($url_regexp, $current_url)) {
            return false;
        }

        return true;
    }

    static public function getCurrentControllerObj()
    {
        return self::$current_controller_obj;
    }

    static public function getCurrentActionMethodName()
    {
        return self::$current_action_method_name;
    }

    static public function getCurrentControllerClassName()
    {
        return self::$current_controller_class_name;
    }

    protected static function getDefaultCacheLifetime()
    {
      /*
        $has_admin_cookie = false;

        if ($has_admin_cookie) {
            $cache_seconds_for_headers = 0;
        } else {
            $cache_seconds_for_headers = \OLOG\ConfWrapper::value('cache_life_time', 60);
        }

        return $cache_seconds_for_headers;
      */
      return 60;
    }

    /**
     * - Декодирует все параметры, полученные из урла.
     * - Экшен должен вернуть CONTINUE_ROUTING если не смог обработать запрос (не подошел). Если обработал - может ничего не возвращать.
     * @param $url_regexp
     * @param $callback_arr
     * @param null $cache_seconds_for_headers
     */
    static public function match($url_regexp, callable $callback_arr, $cache_seconds_for_headers = null)
    {
        list($controller_class_name, $action_method_name) = $callback_arr;

        $matches_arr = array();
        $current_url = self::uri_no_getform();

        if (!preg_match($url_regexp, $current_url, $matches_arr)) {
            return;
        }

        if (count($matches_arr)) {
            // убираем первый элемент массива - содержит всю сматченую строку
            array_shift($matches_arr);
        }

        // кэширование страницы по умолчанию
        if (is_null($cache_seconds_for_headers)) {
            $cache_seconds_for_headers = self::getDefaultCacheLifetime();
        }
        self::cacheHeaders($cache_seconds_for_headers);

        $decoded_matches_arr = array();
        foreach ($matches_arr as $arg_value) {
            $decoded_matches_arr[] = urldecode($arg_value);
        }

        self::$current_action_method_name = $action_method_name;
        self::$current_controller_class_name = $controller_class_name;

        self::$current_controller_obj = new $controller_class_name();
        $action_result = call_user_func_array(array(self::$current_controller_obj, $action_method_name), $decoded_matches_arr);

        if ($action_result == null) {
            exit;
        }

        if ($action_result != self::CONTINUE_ROUTING) {
            exit;
        }

        // сбрасываем текущий контроллер - он больше не актуален
        self::$current_controller_obj = null;
        self::$current_action_method_name = null;
        self::$current_controller_class_name = null;
    }

    static public function cacheHeaders($seconds = 0)
    {
      if ($seconds) {
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
	header('Cache-Control: max-age=' . $seconds . ', public');
      } else {
	header('Expires: ' . gmdate('D, d M Y H:i:s', date('U') - 86400) . ' GMT');
	header('Cache-Control: no-cache');
      }

    }

    static public function uri_no_getform()
    {
      $request_uri = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';
      $parts = explode('?', $request_uri);
      $uri_no_getform = $parts[0];
      return $uri_no_getform;
    }

    
}
