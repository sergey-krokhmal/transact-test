<?php
namespace Application\Controllers;

use Application\Core\BaseController;
use Application\Services\AccountManager;

// Главный контроллер
class Site extends BaseController
{
	// Стартовая страница
	public function index($params)
	{
		// Получим объект менеджера аккаунтов
		$account_manager = AccountManager::getInstance();
		
		// Если авторизированы - загрузить шаблон главной страницы
		if ($account_manager->isLogged()) {
			// Если была нажата кнопка "Вывести"
			if (isset($params['withdraw'])) {
				// Выполнить вывод средств
				$res = $account_manager->withdraw($params['sum'] ?? 0);
				// Если ошибок нет
				if ($res === true) {
					// Перезагружаем страницу
					header("Location: /");
				} else {
					// Если была ошибка, то передать сумму и текст ошибки в форму 
					$data['sum'] = htmlspecialchars($params['sum'] ?? '');
					$data['error_message'] = $res;
				}
			}
			
			// Получить авторизированный аккаунт
			$account = $account_manager->getAccount();
			
			// Данные для страницы управления счеттом
			$data['fio'] = $account['fio'];
			$data['balance'] = number_format($account['balance'], 2, ',', ' ');
			
			// Получить все транзакции текущего аккаунта
			$transactions = $account_manager->getTransactions();
			// Для каждой транзакции отформатировать сумму
			foreach($transactions as $i => $transaction) {
				$transactions[$i]['f_sum'] = number_format($transaction['sum'], 2, ',', ' ');
			}
			$data['transactions'] = $transactions;
			// Загрузить шаблон с данными
			$this->loadTemplate('Site', 'index', $data);
		} else {
			// Иначе перейти на страницу авторизации
			header("Location: /Site/login");
		}
	}
	
	// Страница авторизации
	public function login($params)
	{		
		// Получим объект менеджера аккаунтов
		$account_manager = AccountManager::getInstance();
		
		// Разлогиниться
		$account_manager->logout();
		
		// Если форма была отправлена
		if (isset($params['submit'])) {
			
			// Выполнить авторизацию
			$log_res = $account_manager->login(
				$params['login'],
				$params['password']
			);
			
			// Если ошибок нет
			if ($log_res === true) {
				/// Перейти на главную страницу
				header("Location: /");
			} else {
				// Если была ошибка, то передать логин и текст ошибки в форму 
				$data['login'] = htmlspecialchars($params['login']);
				$data['error_message'] = $log_res;
			}
		}
		
		// Загрузить форму авторизации
		$this->loadTemplate('Site', 'login', $data);
	}
	
	public function logout()
	{
		// Получим объект менеджера аккаунтов
		$account_manager = AccountManager::getInstance();
		
		// Если были авторизированы до этого
		if ($account_manager->isLogged()) {
			// Разлогиниться
			$account_manager->logout();
		}
		// Перейти на страницу авторизации
		header("Location: /Site/login");
	}
}