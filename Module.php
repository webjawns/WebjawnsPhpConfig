<?php

namespace WebjawnsPhpConfig;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use WebjawnsPhpConfig\Exception;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $config = $application->getConfig();

        $events = $application->getEventManager();
        $events->attach('route', array($this, 'checkIniConfigByRoute'));

        if (!isset($config['webjawns_php_config']) || !is_array($config['webjawns_php_config'])) {
            continue;
        }
        $config = $config['webjawns_php_config'];

        // Global INI directives
        $this->processIniConfig($config);
    }

    public function checkIniConfigByRoute(MvcEvent $e)
    {
        $application = $e->getApplication();
        $config = $application->getConfig();

        $matches = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        $route = $matches->getMatchedRouteName();

        if (!isset($config['webjawns_php_config']) || !is_array($config['webjawns_php_config'])) {
            continue;
        }
        $config = $config['webjawns_php_config'];

        // Controller-specific INI directives
        if (isset($config['controllers']) && is_array($config['controllers'])) {
            $controllerConfig = $config['controllers'];

            if (isset($controllerConfig[$controller]) && is_array($controllerConfig[$controller])) {
                $this->processIniConfig($controllerConfig[$controller]);
            }
        }

        // Route-specific INI directives
        if (isset($config['routes']) && is_array($config['routes'])) {
            $routeConfig = $config['routes'];

            if (isset($routeConfig[$route]) && is_array($routeConfig[$route])) {
                $this->processIniConfig($routeConfig[$route]);
            }
        }
    }

    public function processIniConfig(array $config)
    {
        $throwExceptionOnFailure = isset($config['throw_exception_on_failure'])
            ? (bool) $config['throw_exception_on_failure'] : true;

        foreach ($config as $key => $value) {
            if (in_array($key, array('controllers', 'routes', 'throw_exception_on_failure'))) {
                continue;
            }

            $result = ini_set($key, $value);
            if (false === $result && $throwExceptionOnFailure) {
                throw new Exception\RuntimeException(sprintf('Failed to set PHP "%s" configuration option', $key));
            }
        }
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
