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
                $viewPage = self::callMiddleWare($pattern);

                if ($viewPage) {
                    $page = self::callMethod($entity[0], $entity[1], $params);
                    self::res($page);
                }
                exit;
            }
        }
        if(!empty(self::$defaultClass)){
            echo call_user_func_array(self::getCallback(null, null), []);
        }
    }

    /**
     * @param mixed $data
     */
    public static function res(mixed $data) : void {
        if (!empty($data)) {
            if(is_array($data) or is_object($data)) {
                print json_encode($data);
            }
            else {
                print $data;
            }
        }
    }

    /**
     * @param mixed $defaultClass
     */
    public static function setDefaultClass(mixed $defaultClass): void
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
     *
     * @return bool
     */
    private static function callMiddleWare(string $pattern): bool
    {
        $viewPage = true;

        if (isset(self::$middleWare[$pattern])) {
            $viewPage = self::getMiddleWare(self::$middleWare[$pattern]['class']);
        }

        return $viewPage;
    }

    /**
     * Вызывает мидлвейр
     * @param mixed $object
     *
     * @return bool
     */
    private static function getMiddleWare(string $object): bool
    {

        $m = new $object;

        if(!$m->init()) {
            self::res($m->break());
            return false;
        }

        return true;
    }

    /**
     * @param string $object
     * @param string $method
     * @param array $params
     *
     * @return mixed
     */
    private static function callMethod(mixed $object, string $method, array $params = []): mixed
    {
        return call_user_func_array([
            $object,
            $method
        ], array_values($params));
    }
}