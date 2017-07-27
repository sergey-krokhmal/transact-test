<?php
namespace Application\Core;

// Базовый контроллер приложения
class BaseController
{
    // Метод страницы по умолчанию
	public function index()
	{
	}
	
    // Подключение шаблона с данными страницы
	public function loadTemplate(string $template_name = 'index', $data = array())
	{
		$reflection = new \ReflectionClass($this);  // Объект для определения инфы о классе контроллера
		$dir_name = $reflection->getShortName();    // Определение каталога шаблонов
		$doc_root = $_SERVER['DOCUMENT_ROOT'];      // Корень сайта
        // Полный путь к файлу шаблона
		$template_path_name = "$doc_root/Application/Templates/$dir_name/$template_name.php";
		
        // Преобразовать массив переданых данных в переменные
		foreach ($data as $key => $val) {
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