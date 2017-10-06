<?php

namespace OLOG;

class Router {
    const NO_MATCH = 'NO_MATCH';

    // текущий (т.е. последний созданный) объект экшена
    static protected $current_action = null;

    // TODO: describe, add getter+setter
    // url_perfix позволяет работать в папке
    static $url_prefix = '';

    /**
     * Простой метод проверки соответствует ли запрошенный урл указанной маске.
     * Может использоваться для группировки роутов.
     * @param $url_regexp
     * @return bool
     */
    static public function group($url_regexp): bool {
        $current_url = Url::getCurrentUrlNoGetForm();

        if (!preg_match($url_regexp, $current_url)) {
            return false;
        }

        return true;
    }

    /**
     * Можно получить текущий экшен в любом месте кода и прочитать из него
     * контекст, проверить что за экшен сейчас работает, получить из экшена
     * дополнительные данные и т.п.
     * @return type
     */
    static public function currentAction() {
        return self::$current_action;
    }

    protected static function defaultCacheSeconds(): int {
        return 60;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - выполняет его и делает exit.
     * @param type $action_class_name
     * @param type $cache_seconds_for_headers
     * @return type
     */
    static public function action($action_class_name, $cache_seconds_for_headers = 60) {
        $action_result = self::matchAndExecute($action_class_name, $cache_seconds_for_headers);

        if ($action_result === self::NO_MATCH) {
            return null;
        }

        exit;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - выполняет его и возвращает результат.
     * @param type $action_class_name
     * @param type $cache_seconds_for_headers
     * @return boolean
     */
    static public function matchAndExecute($action_class_name, $cache_seconds_for_headers = 60) {
        $action_obj = null;
        $current_url = Url::getCurrentUrlNoGetForm();

        if (CheckClassInterfaces::classImplementsInterface($action_class_name, ParseActionInterface::class)) {
            $action_obj = $action_class_name::parse($current_url);
        } elseif (CheckClassInterfaces::classImplementsInterface($action_class_name, MaskActionInterface::class)) {
            $url_regexp = '@^' . self::$url_prefix . $action_class_name::mask() . '$@';
            $action_obj = self::match($action_class_name, $current_url, $url_regexp);
        } elseif (CheckClassInterfaces::classImplementsInterface($action_class_name, SimpleActionInterface::class)) {
            /** @var SimpleActionInterface $dummy_action_obj */
            $dummy_action_obj = new $action_class_name;
            $url_regexp = '@^' . self::$url_prefix . $dummy_action_obj->url() . '$@';
            $action_obj = self::match($action_class_name, $current_url, $url_regexp);
        } else {
            throw new \Exception('Action class ' . $action_class_name . ' does not implement action interfaces.');
        }

        if (is_null($action_obj)) {
            // экшен не умеет обрабатывать этот урл
            return self::NO_MATCH;
        }

        return self::execute($action_obj, $cache_seconds_for_headers);
    }

    /**
     * матчим маску адреса с запрошенным урлом
     * если матчится - возвращает объект экшена, иначе - null
     * @param type $action_class_name
     * @param type $current_url
     * @return 
     */
    static protected function match($action_class_name, $current_url, $url_regexp) {

        //
        // проверка соответствия запрошенного адреса маске экшена и извлечение параметров экшена
        //

        $matches_arr = array();
        if (!preg_match($url_regexp, $current_url, $matches_arr)) {
            return null;
        }

        if (count($matches_arr)) {
            array_shift($matches_arr); // убираем первый элемент массива - содержит всю сматченую строку
        }

        //
        // декодирование параметров экшена, полученных из урла
        //

        $decoded_matches_arr = array();
        foreach ($matches_arr as $arg_value) {
            $decoded_matches_arr[] = urldecode($arg_value);
        }

        //
        // создание объекта экшена
        //

        $reflect = new \ReflectionClass($action_class_name);
        return $reflect->newInstanceArgs($decoded_matches_arr);
    }

    /**
     * устанавливаем хидеры кэширования и выподняем экшен, возвращаем его результат
     * @param type $action_obj
     * @param type $cache_seconds_for_headers
     * @return type
     */
    static protected function execute($action_obj, $cache_seconds_for_headers) {
        Assert::assert($action_obj);
        
        self::cacheHeaders($cache_seconds_for_headers);

        self::$current_action = $action_obj;
        $action_result = $action_obj->action();

        // сбрасываем текущий экшен - он больше не актуален
        self::$current_action = null;

        return $action_result;
    }

    static public function cacheHeaders($seconds = 0) {
        if (php_sapi_name() == "cli") {
            return;
        }

        if ($seconds) {
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
            header('Cache-Control: max-age=' . $seconds . ', public');
        } else {
            header('Expires: ' . gmdate('D, d M Y H:i:s', date('U') - 86400) . ' GMT');
            header('Cache-Control: no-cache');
        }
    }
}
