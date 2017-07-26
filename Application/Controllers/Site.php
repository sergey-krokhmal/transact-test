<?php
namespace Application\Controllers;

use Application\Core\BaseController;
use Application\Entities\AccountManager;

// Главный контроллер
class Site extends BaseController
{
	// Менеджер аккаунтов
	private $account_manager;
	
	public function __construct()
	{
		// Создать экземпляр менеджера аккаунтов
		$this->account_manager = new AccountManager();
	}
	
	// Стартовая страница
    public function index()
	{
		// Если авторизированы - загрузить шаблон главной страницы
		if ($this->account_manager->isLogged()) {
			$this->loadTemplate('index');
		} else {
			// Иначе перейти на страницу авторизации
			header("Location: /Site/login");
		}
	}
	
	// Страница авторизации
	public function login($params = array())
	{
		// Данные для шаблона
		$data = array();
		
		// Разлогиниться
		$this->account_manager->logout();
		
		// Если форма была отправлена
		if (isset($params['submit'])) {
			
			$log_res = $this->account_manager->login(
				$params['login'],
				$params['password']
			);
			
			//if ($log_res === 0)
			// Передать логин в форму
			$data['login'] = htmlspecialchars($params['login']);
			$data['error_message'] = $log_res;
			
			//header("Location: /Site/index");
		}

		
		// Загрузить форму авторизации
		$this->loadTemplate('login', $data);
	}
	
	public function logout()
	{
		// Создать экземпляр менеджера аккаунтов
		$this->account_manager = new AccountManager();
		// Если были авторизированы до этого
		if ($this->account_manager->isLogged()) {
			// Разлогиниться
			$this->account_manager->logout();
		}
		header("Location: /Site/login");
	}
	
	
}