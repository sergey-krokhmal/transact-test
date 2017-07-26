<?php
namespace Application;

// Приложение
class App
{
    // Запустить приложение
    public function run()
    {
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $api_spec = '~^/(?<controller>[a-zA-Z0-9_]+)(?:/(?<method>[a-zA-Z0-9_]+))?/?.*$~';
        preg_match($api_spec, $url_path, $matches);
        
        $controller_name = 'Application\\Controllers\\'.$matches['controller'].'Controller';
        
        if (class_exists($controller_name)){
            echo 'Application\\Controllers\\'.$controller_name;
        }
        
        $controller = new $controller_name();
    }
}