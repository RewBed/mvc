<?php


namespace Routes;


/**
 * Class ModuleRouteMiddleWare
 *
 * @package Route
 */
class RouteMiddleWare extends Route {

    /**
     * @var string
     */
    private string $pattern;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern = '') {
        parent::__construct();
        $this->pattern = $pattern;
    }

    /**
     * @param string $class
     *
     * @return void
     */
    public function middleWare(string $class)
    {
        if(!empty($this->pattern) && !empty($class)) {
            parent::$middleWare[$this->pattern] = ['class' => $class];
        }
    }
}