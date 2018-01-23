<?php

/**
 * @param \Slim\Container $container
 * @return mixed
 */
$container['mongodb'] = function (\Slim\Container $container) {
    $config = \App\Base\AppContainer::config('mongodb');

    $connection = new \App\Base\Db\MongoDBClient($container);
    $connection->addConnection([
        'uri' => $config['uri'],
        'database' => $config['database'],
        'uriOptions' => $config['uriOptions'],
        'driverOptions' => $config['driverOptions'],
    ]);

    return $connection->getConnection();
};

// Add Event manager to dependency.
$container['event_manager'] = function (\Slim\Container $container) {

    $emitter = new \App\Base\EventManager();

    // require events
    $eventFiles = glob(__DIR__ . '/../app/*/*_events.php');

    foreach ($eventFiles as $file) {
        $eventArr = require $file;

        if (is_array($eventArr)) {
            foreach ($eventArr as $eventKey => $listener) {
                $emitter->add($eventKey, $listener);
            }
        }
    }

    return $emitter;
};

