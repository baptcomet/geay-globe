<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'blog_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../../Blog/src/Blog/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Blog\Entity' => 'blog_entities',
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Index' => 'Application\Controller\IndexController',
            'Error' => 'Application\Controller\ErrorController'
        ),
    ),
    'router' => array(
        'routes' => array(
            /* backoffice*/
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/blog[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog',
                        'action' => 'index',
                    ),
                ),
            ),
            'tags' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/blog/tags[/:names]',
                    'defaults' => array(
                        'controller' => 'Blog',
                        'action' => 'tags',
                        'names' => '',
                    ),
                ),
            ),
            'error' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/error[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Error',
                        'action' => 'index',
                    ),
                ),
            ),
            'index' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/index[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
            array(
                'type' => 'phparray',
                'base_dir' => 'vendor/zendframework/zendframework/resources/languages/fr/',
                'pattern' => 'Zend_Validate.php',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/front.phtml',
            'layout/sidebar' => __DIR__ . '/../view/layout/partial/sidebar.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ),
    ),
);
