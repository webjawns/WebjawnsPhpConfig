<?php

namespace WebjawnsPhpConfig;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use WebjawnsPhpConfig\Exception;

class Module implements AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $config = $application->getConfig();

        if (isset($config['webjawns_php_config']) && is_array($config['webjawns_php_config'])) {
            $phpConfig = $config['webjawns_php_config'];

            $throwExceptionsOnFailure = isset($phpConfig['throw_exception_on_failure'])
                ? (bool) $phpConfig['throw_exception_on_failure'] : true;

            foreach ($phpConfig as $key => $value) {
                if ('throw_exception_on_failure' === $key) {
                    continue;
                } elseif (false === ini_set($key, $value)) {
                    throw new Exception\RuntimeException(sprintf('Failed to set PHP "%s" configuration option', $key));
                }
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
