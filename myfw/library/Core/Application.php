<?php
class Core_Application
{
    protected $_environment;
    protected $_options;
    protected $_router;

    public function __construct($environment, $options)
    {
        include_once LIBRARY_PATH . '/' . 'Core/Loader.php';

        // Registry auto loader
        spl_autoload_register(array(new Core_Loader($options), 'autoload'));

        $this->_environment = $environment;
        $this->_setOptions($options);
        $this->_setExceptionHandler();
    }

    protected function _setOptions($options)
    {
        $this->_options = $options;

        foreach ($options as $key => $val) {
            if ($key == 'phpSettings') {
                $this->_setPhpSettings($val);
            } elseif ($key == 'resources') {
                $this->_registryResources($val);
            }
        }
    }

    protected function _setPhpSettings($settings)
    {
        foreach ($settings[$this->_environment] as $key => $value) {
            if ($key == 'error_reporting') {
                error_reporting($value);
                continue;
            }
            ini_set($key, $value);
        }
    }

    protected function _setExceptionHandler()
    {
        $errorHandler = new Base_Controller_ErrorController($this->_options);
        set_exception_handler(array($errorHandler, 'errorAction'));
    }

    protected function _registryResources($resources)
    {
        foreach ($resources as $resource => $options) {
            Core_Resource_Manager::setOption($resource, $options);
        }
    }

    public function run()
    {
        $subFolder = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\','/', BASE_PATH)), '/');
        $basePath = ($subFolder == '/') ? '' : $subFolder;

        // Set Router
        $this->_router = new Core_Router();
        $this->_router->setBasePath($basePath);
        $this->_getRoutes();

        $match = $this->_router->match();

        if ($match) {
            $controllerClass = $match['target']['module'] . '_Controller_' . $match['target']['controller'] . 'Controller';
        } else {
            throw new Exception('Page not found', 404);
        }

        $controller = new $controllerClass($this->_options, isset($match['params']) ? $match['params'] : array());
        $this->_dispatch($controller, $match['target']['action']);
    }

    protected function _dispatch($controller, $action)
    {
        $controller->setRouter($this->_router);
        $controller->execute($action);
    }

    protected function _getRoutes()
    {
        $modules = $this->_options['modules'];

        foreach ($modules as $module) {
            $routeFile = APPLICATION_PATH . '/module/' . $module . '/Route/Route.php';
            if (file_exists($routeFile)) {
                $routes = include_once $routeFile;
                foreach ($routes  as $route) {
                    $this->_router->map($route['method'], $route['route'], $route['target'], $route['name']);
                }
            }
        }
    }

}