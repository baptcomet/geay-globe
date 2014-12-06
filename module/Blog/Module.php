<?php

namespace Blog;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ),
            'invokables' => array(
                'daoManager' => 'Application\Models\DaoManager',
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'formlabel' => 'Blog\Form\View\Helper\RequiredMarkInFormLabel',
                'formelementerrors' => 'Blog\Form\View\Helper\FormElementErrors',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {

    }
}
