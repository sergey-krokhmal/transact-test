<?php
use Application\App;

// Добавить автозагрузчик
$loader = require (__DIR__ . '/vendor/autoload.php');

try {
    // Создать экземпляр приложения, передав имя контроллера главной страницы, и запустить
    $app = new App('Site');
    $app->run();
} catch (Exception $ex) {
    echo $ex->getMessage();
}