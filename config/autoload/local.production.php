<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => '178.62.97.50',
                    'port' => '3306',
                    'user' => 'geaysglobe',
                    'password' => 'flowerandguns',
                    'dbname' => 'blog',
                    'charset'  => 'utf8',
                )
            )
        )
    ),
    'mail' => array(
        'transport' => array(
            'options' => array(
                'name' => 'smtp.gmail.com',
                'host' => 'smtp.gmail.com',
                'connection_class' => 'login',
                'port' => 465,
                'connection_config' => array(
                    'ssl' => 'ssl',
                    'username' => 'contact@geays-globe.fr',
                    'password' => 'flowerandguns',
                ),
            ),
        ),
    ),
);
