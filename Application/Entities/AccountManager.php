<?php
namespace Application\Entities;

// Сущность управления аккаунтом
class AccountManager
{
	private $db;	// Подключение к БД
	
	private $iv = "dGVzdHZlY3RvcgAt";   // Вектор шифрования
    private $method = "AES-256-CBC";    // Метод шифрования
    private $opt = OPENSSL_ALGO_SHA512; // Алгоритм
	
	public function __construct()
	{
		$this->db = new \PDO('mysql:host=localhost;dbname=bit_money', 'test', 's78A5oTjhBZyeTQi');
	}
	
	// Авторизация
	public function login(string $login, string $password)
	{
		$query = $this->db->prepare("SELECT * FROM `account` WHERE login = ?");
		if ($query->execute(array($login))) {
			if ($query->rowCount() > 0) {
				$account = $query->fetch();
				
				// Используем введенные логин и пароль как ключ шифрования
				$key = $login.$password;
				// Шифруем пароль
				$pass_enc = openssl_encrypt($password, $this->method, $key, $this->opt, $this->iv);
				
				// 
				$query = $this->db->prepare("SELECT * FROM `account` WHERE password = ? AND id = ?");
				if ($query->execute(array($pass_enc, $account['id']))) {
					if ($query->rowCount() > 0) {
						$session_id = session_id();
						$token = openssl_encrypt($session_id, $this->method, $account['id'], $this->opt, $this->iv);
						$_SESSION['auth']['token'] = $token;
						$_SESSION['auth']['id'] = $account['id'];
					}
				}
				
			} else {
				echo "nolog";
			}
		}
		
		
	}
	
	// Авторизирован ли пользователь
	public function isLogged()
	{
		if (isset($_SESSION['auth']['token'])) {
			$session_id = openssl_encrypt($_SESSION['auth']['token'], $this->method, $this->session_hash_key, $this->opt, $this->iv);
		}
		return true;
	}
	
	// Выход
	public function logout()
	{
		unset($_SESSION['auth']['token']);
	}
}