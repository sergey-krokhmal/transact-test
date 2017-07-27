<?php
use Application\App;

// Добавить автозагрузчик
$loader = require (__DIR__ . '/vendor/autoload.php');

// Добавить пространство имен приложения
$loader->addPsr4( 'Application\\', __DIR__ . '/Application/');

try {
    // Создать экземпляр приложения, передав имя контроллера главной страницы, и запустить
	$app = new App('Site');
	$app->run();
} catch (Exception $ex) {
	echo $ex->getMessage();
}