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
    public function index($params = array())
	{
		// Если авторизированы - загрузить шаблон главной страницы
		if ($this->account_manager->isLogged()) {
            // Если была нажата кнопка "Вывести"
            if (isset($params['withdraw'])) {
                // Выполнить вывод средств
                $res = $this->account_manager->withdraw($params['sum'] ?? 0);
                // Если ошибок нет
                if ($res === true) {
                    // Перезагружаем страницу
                    header("Location: /Site/index");
                } else {
                    // Если была ошибка, то передать сумму и текст ошибки в форму 
                    $data['sum'] = htmlspecialchars($params['sum']);
                    $data['error_message'] = $res;
                }
            }
            
            // Получить авторизированный аккаунт
            $account = $this->account_manager->getAccount();
            
            // Данные для страницы управления счеттом
            $data['fio'] = $account['fio'];
            $data['balance'] = number_format($account['balance'], 2, ',', ' ');
            
            // Получить все транзакции текущего аккаунта
            $transactions = $this->account_manager->getTransactions();
            // Для каждой транзакции отформатировать время и сумму
            foreach($transactions as $i => $transaction) {
                $transactions[$i]['datetime'] = date('d.m.Y H:i:s', strtotime($transaction['time']));
                $transactions[$i]['f_sum'] = number_format($transaction['sum'], 2, ',', ' ');
            }
            $data['transactions'] = $transactions;
            // Загрузить шаблон с данными
			$this->loadTemplate('index', $data);
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
			
            // Выполнить авторизацию
			$log_res = $this->account_manager->login(
				$params['login'],
				$params['password']
			);
			
            // Если ошибок нет
			if ($log_res === true) {
                /// Перейти на главную страницу
                header("Location: /Site/index");
            } else {
                // Если была ошибка, то передать логин и текст ошибки в форму 
                $data['login'] = htmlspecialchars($params['login']);
                $data['error_message'] = $log_res;
            }
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
        // Перейти на страницу авторизации
		header("Location: /Site/login");
	}
	
	
}