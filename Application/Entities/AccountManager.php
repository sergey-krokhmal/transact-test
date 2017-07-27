<?php
namespace Application\Entities;

use Application\Services\AccountDb;

// Сущность управления аккаунтом
class AccountManager
{
	private $account_db;	// Сервис доступа к аккаунтам БД
	
	private $iv = "dGVzdHZlY3RvcgAt";	// Вектор шифрования
	private $method = "AES-256-CBC";	// Метод шифрования
	private $opt = OPENSSL_ALGO_SHA512;	// Алгоритм
	
	public function __construct()
	{
		// Создание сервиса БД для доступа к аккаунтам
		$this->account_db = AccountDb::getInstance();
	}
	
	// Авторизация
	public function login(string $login, string $password)
	{
		// Флаг ошибки неправильного логина и пароля
		$wrong_lp = true;
		
		// Проверить аккаунт по логину
		if ($account = $this->account_db->getAccountByLogin($login)) {
			// Используем введенные логин и пароль как ключ шифрования
			$key = $login.$password;
			// Шифруем пароль
			$pass_enc = openssl_encrypt($password, $this->method, $key, $this->opt, $this->iv);
			
			// Если пароль подходит
			if ($account = $this->account_db->getAccountByIdAndPass($account['id'], $pass_enc)) {
				// Сформировать токен из id сессии и id аккаунта
				$token = openssl_encrypt(session_id(), $this->method, $account['id'], $this->opt, $this->iv);
				// Записать в сессии токен и id 
				$_SESSION['auth']['token'] = $token;
				$_SESSION['auth']['key'] = $account['id'];
				// Логин и пароль прошли
				$wrong_lp = false;
			}
		}
		
		if ($wrong_lp) {
			return "Неверный логин или пароль";
		}
		
		return true;
	}
	
	// Авторизирован ли пользователь
	public function isLogged()
	{
		$logged = false;
		if (isset($_SESSION['auth']['token'], $_SESSION['auth']['key'])) {
			$session_id = openssl_decrypt($_SESSION['auth']['token'], $this->method, $_SESSION['auth']['key'], $this->opt, $this->iv);
			if ($session_id === session_id()){
				$logged = true;
			}
		}
		return $logged;
	}
	
	// Выход
	public function logout()
	{
		// Стереть данные аавторизации из сессии
		unset($_SESSION['auth']);
	}
	
	// Получить аккаунт текущего пользователя
	public function getAccount()
	{
		// Если ключ авторизации присутствует в сессии, то выбрать аккаунт по id из сесиии
		if (isset($_SESSION['auth']['key'])) {
			return $this->account_db->getAccountById($_SESSION['auth']['key']);
		} else {
			return false;
		}
	}
	
	// Получение транзакций аккаунта
	public function getTransactions($id_account = false)
	{
		// Если id аккаунта явно не указан, то взять из сессии
		if ($id_account === false && isset($_SESSION['auth']['key'])) {
			$id_account = $_SESSION['auth']['key'];
		}
		// Получить транзакции
		if ($transactions = $this->account_db->getTransactionsByIdAccount($id_account)) {
			return $transactions;
		} else {
			return array();
		}
	}
	
	// Вывод средств
	public function withdraw($sum = 0)
	{
		// Получить текущий аккаунт
		$account = $this->getAccount();
		
		// Заменить запятую на точку
		$sum = str_replace(',', '.', $sum);
		
		// Выполнить списание
		return $this->account_db->withdraw($account['id'], $sum);
	}
}