<?php

return array(
    'navigation' => array(
        'default' => array(
            array(
                'id' => 'login',
                'label' => 'Connexion',
                'route' => 'home',
            ),
            array(
                'id' => 'blog',
                'label' => 'Accueil',
                'route' => 'blog',
            ),
            array(
                'id' => 'tags',
                'label' => '#TAGS',
                'route' => 'tags',
            ),
            array(
                'id' => 'contact',
                'label' => 'Contact',
                'route' => 'contact',
            ),
            array(
                'id' => 'histo',
                'label' => 'Historique',
                'route' => 'histo',
            ),
            array(
                'id' => 'manage',
                'label' => 'Gestion',
                'route' => 'manage',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
);