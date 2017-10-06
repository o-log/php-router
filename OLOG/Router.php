<?php

namespace OLOG;

class Router
{
    const CONTINUE_ROUTING = 'CONTINUE_ROUTING';

    /** @var InterfaceAction */
    static protected $current_action = null; // текущий (т.е. последний созданный) объект экшена

    // TODO: describe, add getter+setter
    static $url_prefix = '';

    /**
     * Простой метод проверки соответствует ли запрошенный урл указанной маске. Может использоваться для группировки роутов.
     * @param $url_regexp
     * @return bool
     */
    static public function group($url_regexp) : bool
    {
        $current_url = Url::getCurrentUrlNoGetForm();

        if (!preg_match($url_regexp, $current_url)) {
            return false;
        }

        return true;
    }

    static public function currentAction() : InterfaceAction
    {
        return self::$current_action;
    }

    protected static function getDefaultCacheLifetime() : int
    {
      return 60;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - выполняет его и делает exit.
     * @param type $action_class_name
     * @param type $cache_seconds_for_headers
     * @return type
     */
    static public function action($action_class_name, $cache_seconds_for_headers = 60)
    {
        $action_result = self::run($action_class_name, $cache_seconds_for_headers);
        
        // проверка результата экшена - нужно ли завершать работу
        if ($action_result === self::CONTINUE_ROUTING) {
            return $action_result;

        }

        exit;
    }

    /**
     * Если указанный класс экшена умеет обрабатывать текущий запрос - выполняет его и возвращает результат.
     * @param type $action_class_name
     * @param type $cache_seconds_for_headers
     * @return boolean
     */
    static public function run($action_class_name, $cache_seconds_for_headers = 60){
        CheckClassInterfaces::exceptionIfClassNotImplementsInterface($action_class_name, InterfaceAction::class);

        $action_obj = null;
        $current_url = Url::getCurrentUrlNoGetForm();

        if (CheckClassInterfaces::classImplementsInterface($action_class_name, InterfaceGetActionObjForUrl::class)){

            //
            // экшен умеет сам проверять его ли это урл и получать контекст из урла
            //

            $action_obj = $action_class_name::getActionObjForUrl($current_url);

            if (is_null($action_obj)){
                // экшен не умеет обрабатывать этот урл
                return null;
            }
        } else {

            //
            // экшен не умеет сам проверять его ли это урл и получать контекст из урла
            // поэтому получаем из экшена маску адреса и матчим с запрошенным урлом
            //

            $url_regexp = '';

            if (method_exists($action_class_name, 'urlMask')){
                $url_regexp = '@^' . $action_class_name::urlMask() . '$@';
            } else {
                // создаем объект экшена без контекста, чтобы получить из него маску адреса через метод url()
                /** @var InterfaceAction $dummy_action_obj */
                $dummy_action_obj = new $action_class_name;

                // url_perfix позволяет работать в папке
                $url_str = self::$url_prefix . $dummy_action_obj->url();
                $url_regexp = '@^' . $url_str . '$@';
            }

            //
            // проверка соответствия запрошенного адреса маске экшена и извлечение параметров экшена
            //

            $matches_arr = array();
            if (!preg_match($url_regexp, $current_url, $matches_arr)) {
                return false;
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

            $reflect  = new \ReflectionClass($action_class_name);
            $action_obj = $reflect->newInstanceArgs($decoded_matches_arr);
        }

        Assert::assert($action_obj);

        self::cacheHeaders($cache_seconds_for_headers);

        //
        // вызов экшена
        //

        self::$current_action = $action_obj;
        $action_result = $action_obj->action();

        //
        // сбрасываем текущий экшен - он больше не актуален
        //

        self::$current_action = null;
        
        return $action_result;
    }

    static public function cacheHeaders($seconds = 0)
    {
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
