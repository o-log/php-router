# Пример использования

Пример класса экшена:

    class NodeAction
    {
        static public function getUrl($node_id = '(\d+)'){
            return '/node/' . $node_id;
        }
    
        static public function action($node_id){
            NodePageTemplate::render($node_id);
        }
    }

Класс экшена может или возвратить адрес страницы для материала, или вывести страницу материала.

Роутинг для этого экшена в точке входа (в index.php):
  
    Router::matchClass(\PHPRouterDemo\NodeAction::class);
    
Роутер вызывает метод getUrl класса экшена и получает маску адресов, которые может обработать этот экшен (регексп).
Если экшен принимает дополнительные параметры - он должен подставить в маску адреса шаблон для этих параметров, в примере выше это сделано через значение параметра по умолчанию.
Если запрошенный адрес соответствует этой маске - роутер вызывает метод action класса экшена. После этого роутер завершает обработку запроса.
 
Экшен может дополнительно проверить запрошенный адрес и если он все-таки не может обработать такой адрес - метод action может вернуть CONTINUE_ROUTING. Тогда роутер не будет останавливать обработку запроса и продолжит проверять другие экшены. 

# Какие задачи решает роутер

## Генерация адресов для экшенов
Для того, чтобы сгенерировать адрес для экшена, нужно метод getUrl класса экшена.

Если экшен поддерживает получение параметров - он может подставить их в адрес, для этого параметры надо просто передать методу getUrl.

Сам роутер получает адреса экшенов для роутинга по этой же технологии.

## Удобная навигация по коду
Для генерации адреса и для вывода результата используется один и тот же класс. Поэтому, если в коде где-то генерируется адрес для экшена - можно перейти в класс экшена, просто кликнув в IDE на вызов метода getUrl.

Можно найти все точки в коде, где используется класс экшена, просто найдя вызовы метода getUrl.

## В коде не должно быть имен классов, имен функций и урлов в виде строковых констант
Адреса для экшенов хранятся в коде только в одном месте: в самом экшене. Во всех остальных местах для получения адреса можно использовать вызов метода getUrl класса экшена, таким образом адреса можно менять централизованно.

Имя класса экшена для роутинга передается роутеру не как строка, а через зарезервированную константу class. Таким образом можно рефакторить классы экшенов, например, переименовывать их, без необходимости находить и менять строки в коде. Также можно находить все использования классов средствами IDE.