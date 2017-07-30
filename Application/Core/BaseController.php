<?php
namespace Application\Core;

/**
 * Базовый контроллер приложения
 * @package Application
 * @subpackage Core
 */
class BaseController
{
    /**
     * Метод страницы index по умолчанию
     * @param array $params Параметры запроса
    */
    public function index($params)
    {
    }
    
    /** Подключение шаблона с данными страницы
     * @param string $dir_name Каталог с файлами шоблонов php
     * @param string $template_name Имя файла шаблона php
     * @param array $data Массив переменных, передаваемых в шаблон
     */
    public function loadTemplate(string $dir_name = 'Home', string $template_name = 'index', $data = [])
    {
        // Корень сайта
        $doc_root = $_SERVER['DOCUMENT_ROOT'];
        // Полный путь к файлу шаблона
        $template_path_name = "$doc_root/Application/Templates/$dir_name/$template_name.php";
        
        // Преобразовать массив переданых данных в переменные
        foreach ($data ?? [] as $key => $val) {
            $$key = $val;
        }
        
        // Если шаблон существует 
        if (file_exists($template_path_name)) {
            // Подключаем его
            require_once($template_path_name);
        } else {
            // Иначе выводим ошибку
            echo "Шаблон '/Application/Templates/$dir_name/$template_name.php' не найден";
        }
    }
}