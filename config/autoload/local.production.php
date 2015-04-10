<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => '5cOsbYTTtJ',
                    'dbname' => 'blog',
                    'charset'  => 'utf8',
                )
            )
        )
    ),
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host' => '',
            ),
        ),
    ),
);
