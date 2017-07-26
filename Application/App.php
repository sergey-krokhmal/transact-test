<?php
namespace Application;

// Приложение
class App
{
	private $home_controller_name;
	
	public function __construct(string $home_controller_name = 'Home')
	{
		$controller_name = 'Application\\Controllers\\'.$home_controller_name;
		if (class_exists($controller_name)) {
			$this->home_controller_name = $controller_name;
		} else {
			throw new \Exception("Контроллер главной страницы с именем '$home_controller_name' не найден");
		}
	}
	
    // Запуск приложения
    public function run()
    {
		// Если сессия не создана - создать ее
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		
		// Получаем Url без параметров запроса
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
		// Определяем для запросов шаблон вида /Имя_контроллера/Имя_метода
		// Имя метода может отсутствовать (подразумевается index)
        $api_spec = '~^/(?<controller>[a-zA-Z0-9_]+)(?:/(?<method>[a-zA-Z0-9_]+))?/?.*$~';
		// Получить массив совпадений запроса с шаблоном
        preg_match($api_spec, $url_path, $matches);
        
		// Если имя контроллера указано в запросе
		if (isset($matches['controller'])) {
			// Взять его как имя исполнимого контроллера
			$controller_name = 'Application\\Controllers\\'.$matches['controller'];
		} else {
			// Иначе контроллер главной страницы
			$controller_name = $this->home_controller_name;
		}
		
		// Если класс контроллера определен
		if (class_exists($controller_name)) {
			// Создаем экземпляр выбранного контроллера
			$controller = new $controller_name();
			
			// Если в запросе указаноимя метода
			if (isset($matches['method'])) {
				// Взять его в качестве имени исполнимого метода
				$method_name = $matches['method'];
			} else {
				// Иначе взять имя метода index
				$method_name = 'index';
			}
			
			// Если выбранный метод определен у контроллера
			if (method_exists($controller, $method_name)) {
				// Выполняем его
				$controller->$method_name();
			} else {
				// Иначе выводим сообщение о не найденном методе
				echo "Метод $method_name контроллера $controller_name не определен";
			}
				
		} else {
			// Иначе выводим сообщение о не найденном классе
			echo "Класс контроллера $controller_name не найден";
		}
		
		// Закрыть сессию не ожидая завершения скрипта
		session_write_close();
    }
}