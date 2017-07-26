<?php

// Добавить автозагрузчик
$loader = require (__DIR__ . '/vendor/autoload.php');

$loader->addPsr4( 'Application\\', __DIR__ . '/Application/');

$app = new App();
$app->run();