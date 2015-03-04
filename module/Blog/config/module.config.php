<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Blog' => 'Blog\Controller\BlogController',
            'Article' => 'Blog\Controller\ArticleController',
            'Writer' => 'Blog\Controller\WriterController',
            'Manage' => 'Blog\Controller\ManageController',
            'Picture' => 'Blog\Controller\PictureController',
        ),
    ),
    'router' => array(
        'routes' => array(
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
            'manage' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/manage[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Manage',
                        'action' => 'index',
                    ),
                ),
            ),
            'article' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/article[/:action][/:id][/:picture]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                        'picture' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Article',
                        'action' => 'index',
                    ),
                ),
            ),
            'picture' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/picture/[:action]/[:id]/[:name]',
                    'contraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Picture',
                    )
                )
            ),
            'writer' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/writer[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Writer',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'blog' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Blog\Entity\User',
                'identity_property' => 'nickname',
                'credential_property' => 'password',
            ),
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
