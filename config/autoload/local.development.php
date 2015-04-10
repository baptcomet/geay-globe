<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => '',
                    'dbname' => 'blog',
                    'charset'  => 'utf8',
                )
            )
        )
    ),
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host' => '178.62.66.77',
            ),
        ),
    ),
);
