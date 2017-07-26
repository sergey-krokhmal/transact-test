<?php
use Application\App;

// Добавить автозагрузчик
$loader = require (__DIR__ . '/vendor/autoload.php');

$loader->addPsr4( 'Application\\', __DIR__ . '/Application/');

try {
	$app = new App('Site');
	$app->run();
} catch (Exception $ex) {
	echo $ex->getMessage();
}