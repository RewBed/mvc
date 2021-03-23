<?php


namespace Routes;

use Routes\RouteMiddleWare;

class Route
{
    /** @var string */
    public static string $url;

    /** @var array */
    private static array $routes = [];

    private static $defaultClass;

    private static $defaultMethod = 'index';

    protected static array $middleWare = [];

    /**
     * ModuleRoutes constructor.
     */
    protected function __construct() { }

    private function __clone() { }

    /**
     * Принимает паттерн ссылки
     * и связывает его с переданным классом и методом
     *
     * @param string      $pattern
     * @param string|null $class
     * @param string|null $method
     *
     * @return RouteMiddleWare
     */
    public static function route(string $pattern, string $class = null, string $method = null): RouteMiddleWare
    {
        $pattern = '#^' . $pattern . '(|/)$#';
        self::$routes[$pattern] = [$class, $method];

        return self::returnMiddleWare($pattern);
    }

    /**
     * @param string|null $class
     * @param string|null $method
     *
     * @return array
     */
    private static function getCallback(?string $class, ?string $method): array
    {
        $obj = !empty($class) && class_exists($class) ? new $class() : new self::$defaultClass;
        return !empty($method) && method_exists($obj, $method) ? [$obj, $method] : [$obj, self::$defaultMethod];
    }

    /**
     * Проверка переданного урла по паттернам и вызов метода
     *
     * @param string|null $url
     *
     * @return void
     */
    public static function execute(string $url = null): void
    {
        $urlData = parse_url(!empty($url) ? $url : $_SERVER['REQUEST_URI']);
        self::$url = $urlData['path'];

        foreach (self::$routes as $pattern => $callback) {
            if (preg_match($pattern, self::$url, $params)) {
                array_shift($params);
                array_pop($params);

                $entity = self::getCallback($callback[0], $callback[1]);
                $viewPage = self::callMiddleWare($pattern, $entity, $callback);

                if ($viewPage) {
                    $page = self::callMethod($entity[0], $entity[1], $params);
                    if (!empty($page)) {
                        if(is_array($page) or is_object($page)) {
                            // header('Content-type: application/json');
                            print json_encode($page);
                        }
                        else {
                            print $page;
                        }
                    }
                }
                exit;
            }
        }
        if(!empty(self::$defaultClass)){
            echo call_user_func_array(self::getCallback(null, null), []);
        }
    }

    /**
     * @param mixed $defaultClass
     */
    public static function setDefaultClass($defaultClass): void
    {
        self::$defaultClass = $defaultClass;
    }

    /**
     * @param string $pattern
     *
     * @return RouteMiddleWare
     */
    private static function returnMiddleWare(string $pattern): RouteMiddleWare
    {
        return new RouteMiddleWare($pattern);
    }

    /**
     * @param string $pattern
     * @param        $entity
     * @param        $callback
     *
     * @return bool|void
     */
    private static function callMiddleWare(string $pattern, $entity, $callback){
        $viewPage = true;
        if (isset(self::$middleWare[$pattern])) {
            if ($callback[0] == self::$middleWare[$pattern]['class']) {
                $viewPage = self::getMiddleWare($entity[0], self::$middleWare[$pattern]['method'], self::$middleWare[$pattern]['return']);
            } else {
                $entityMiddleWare = self::getCallback(self::$middleWare[$pattern]['class'], self::$middleWare[$pattern]['method']);
                $viewPage = self::getMiddleWare($entityMiddleWare[0], self::$middleWare[$pattern]['method'], self::$middleWare[$pattern]['return']);
            }
        }

        return $viewPage;
    }

    /**
     * @param mixed  $object
     * @param string $method
     * @param bool   $return
     *
     * @return void|bool
     */
    private static function getMiddleWare($object, $method, $return)
    {
        $result = true;
        if (method_exists($object, $method)) {
            $result = self::callMethod($object, $method);
        }

        if ($return && !$result) {
            if (method_exists($object, 'break')) {

                return self::callMethod($object, 'break');
            }

            return false;
        }

        if ($return) {
            return $result;
        } else {
            return true;
        }
    }

    /**
     * @param mixed  $object
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    private static function callMethod($object, string $method, array $params = [])
    {
        return call_user_func_array([
            $object,
            $method
        ], array_values($params));
    }
}