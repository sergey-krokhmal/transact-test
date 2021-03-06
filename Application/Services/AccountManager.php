<?php
namespace Application\Services;

use Application\Core\SingletonTrait;

/**
 * Сервис управления аккаунтом
 * Использует трейт синглона SingletonTrait
 * @see SingletonTrait
 * @package Application
 * @subpackage Services
 */
class AccountManager
{
    use SingletonTrait;
    
    /** @var string $iv Вектор шифрования */
    private $iv = "dGVzdHZlY3RvcgAt";
    /** @var string $method Метод шифрования */
    private $method = "AES-256-CBC";
    /** @var int $opt Алгоритм шифрования */
    private $opt = OPENSSL_ALGO_SHA512;
    
    /**
     * Авторизация в приложении
     * @param string $login Логин
     * @param string $password Пароль
     * @return string|true Сообщение об ошибке
     */
    public function login(string $login, string $password)
    {
        // Получение сервиса БД для доступа к аккаунтам
        $account_db = AccountDb::getInstance();
        
        // Флаг ошибки неправильного логина и пароля
        $wrong_lp = true;
        
        // Проверить аккаунт по логину
        if ($account = $account_db->getAccountByLogin($login)) {
            // Используем введенные логин и пароль как ключ шифрования
            $key = $login.$password;
            // Шифруем пароль
            $pass_enc = openssl_encrypt($password, $this->method, $key, $this->opt, $this->iv);
            
            // Если пароль подходит
            if ($account = $account_db->getAccountByIdAndPass($account['id'], $pass_enc)) {
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
    
    /**
     * Проверка авторизирован ли пользователь
     * @return bool true - авторизирован, false - не авторизирован
     */
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
    
    /**
     * Выход из аккаунта
     */
    public function logout()
    {
        // Стереть данные аавторизации из сессии
        unset($_SESSION['auth']);
    }
    
    /**
     * Получить аккаунт текущего пользователя
     * @return array|false Массив записи данных аккаунта
     */
    public function getAccount()
    {
        // Получение сервиса БД для доступа к аккаунтам
        $account_db = AccountDb::getInstance();
        
        // Если ключ авторизации присутствует в сессии, то выбрать аккаунт по id из сесиии
        if (isset($_SESSION['auth']['key'])) {
            return $account_db->getAccountById($_SESSION['auth']['key']);
        } else {
            return false;
        }
    }
    
    /**
     * Получение транзакций аккаунта
     * @param mixed $id_account Id аккаунта
     * @return array|false Массив записей данных транзакций
     */
    public function getTransactions($id_account = false)
    {
        // Получение сервиса БД для доступа к аккаунтам
        $account_db = AccountDb::getInstance();
        
        // Если id аккаунта явно не указан, то взять из сессии
        if ($id_account === false && isset($_SESSION['auth']['key'])) {
            $id_account = $_SESSION['auth']['key'];
        }
        // Получить транзакции
        return $account_db->getTransactionsByIdAccount($id_account);
    }
    
    /**
     * Вывод средств
     * @param float $sum Сумма списания
     * @return string|true Сообщение об ошибке
     */
    public function withdraw($sum = 0)
    {
        // Получение сервиса БД для доступа к аккаунтам
        $account_db = AccountDb::getInstance();
        
        // Получить текущий аккаунт
        $account = $this->getAccount();
        
        // Заменить запятую на точку
        $sum = str_replace(',', '.', $sum);
        
        // Выполнить списание
        return $account_db->withdraw($account['id'], $sum);
    }
}