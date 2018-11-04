<?php

namespace OLOG;

class Router {
    // текущий (т.е. последний созданный) объект экшена
    /** @var ActionInterface */
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
    static public function group(string $url_regexp): bool {
        $current_url = URL::path();

        if (!preg_match($url_regexp, $current_url)) {
            return false;
        }

        return true;
    }

    /**
     * Можно получить текущий экшен в любом месте кода и прочитать из него
     * контекст, проверить что за экшен сейчас работает, получить из экшена
     * дополнительные данные и т.п.
     */
    static public function currentAction(): ActionInterface {
        return self::$current_action;
    }

    protected static function defaultCacheSeconds(): int {
        return 60;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - выполняет его и делает exit.
     * Иначе - возвращает null
     */
    static public function action(string $action_class_name, int $cache_seconds_for_headers = 60) {
        $action_obj = self::actionObj($action_class_name);

        if (!$action_obj) {
            return null;
        }

        self::execute($action_obj, $cache_seconds_for_headers);
        exit;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - выполняет его и делает exit.
     * Иначе - возвращает null
     */
    static public function post(string $action_class_name, int $cache_seconds_for_headers = 60) {
        $action_obj = self::actionObj($action_class_name);

        if (!$action_obj) {
            return null;
        }

        // проверяем разрешенный метод после того, как убедимся что экшен обрабатывает этот запрос
        // чтобы вернуть правильный код ошибки
        if ($_SERVER['REQUEST_METHOD'] != 'POST'){
            Exits::exit405();
        }

        self::execute($action_obj, $cache_seconds_for_headers);
        exit;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - создает и возвращает объект экшена, иначе возвращает null.
     */
    static public function actionObj(string $action_class_name): ?ActionInterface {
        if (!is_a($action_class_name, ActionInterface::class, true)) {
            throw new \Exception('Action class ' . $action_class_name . ' does not implement action interfaces.');
        }

        /** @var $action_obj ActionInterface */
        $action_obj = null;
        $current_url = URL::path();

        if (is_a($action_class_name, ParseActionInterface::class, true)) {
            $action_obj = $action_class_name::parse($current_url);
        } elseif (is_a($action_class_name, MaskActionInterface::class, true)) {
            $url_regexp = '@^' . self::$url_prefix . $action_class_name::mask() . '$@';
            $action_obj = self::match($action_class_name, $current_url, $url_regexp);
        } else {
            /** @var $dummy_action_obj ActionInterface */
            $dummy_action_obj = new $action_class_name;
            $url_regexp = '@^' . self::$url_prefix . $dummy_action_obj->url() . '$@';
            $action_obj = self::match($action_class_name, $current_url, $url_regexp);
        }

        return $action_obj;
    }

    /**
     * матчим маску адреса с запрошенным урлом
     * если матчится - возвращает объект экшена, иначе - null
     */
    static protected function match(string $action_class_name, string $current_url, string $url_regexp) {

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
     */
    static protected function execute(ActionInterface $action_obj, int $cache_seconds_for_headers) {
        self::cacheHeaders($cache_seconds_for_headers);

        self::$current_action = $action_obj;
        $action_result = $action_obj->action();

        // сбрасываем текущий экшен - он больше не актуален
        self::$current_action = null;

        return $action_result;
    }

    static public function cacheHeaders(int $seconds = 0) {
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
