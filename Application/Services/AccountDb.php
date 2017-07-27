<?php
namespace Application\Services;

// Сервис доступа к аккаунтам БД 
class AccountDb
{
    private $db;	// Подключение к БД
    
    public function __construct()
	{
        // Создание подключения к БД для сервиса
		$this->db = new \PDO('mysql:host=localhost;dbname=bit_money', 'test', 's78A5oTjhBZyeTQi', array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false
        ));
	}
    
    // Выборка аккаунта по id
    public function getAccountById(int $id)
    {
        $query = $this->db->prepare("SELECT * FROM `account` WHERE id = ?");
        if ($query->execute(array($id))) {
            if ($query->rowCount() > 0) {
                $account = $query->fetch();
                return $account;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Выборка аккаунта по логину
    public function getAccountByLogin(string $login)
    {
        $query = $this->db->prepare("SELECT * FROM `account` WHERE login = ?");
        if ($query->execute(array($login))) {
            if ($query->rowCount() > 0) {
                $account = $query->fetch();
                return $account;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Выборка аккаунта по id и паролю
    public function getAccountByIdAndPass(int $id, string $password)
    {
        $query = $this->db->prepare("SELECT * FROM `account` WHERE password = ? AND id = ?");
        if ($query->execute(array($password, $id))) {
            if ($query->rowCount() > 0) {
                $account = $query->fetch();
                return $account;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Выборка транзакций по id аккаунта
    public function getTransactionsByIdAccount(int $id_account)
    {
        $query = $this->db->prepare("SELECT * FROM `transaction` WHERE id_account = ? ORDER BY time DESC");
        if ($query->execute(array($id_account))) {
            if ($query->rowCount() > 0) {
                $transactions = $query->fetchAll();
                return $transactions;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Вывод средств
    public function withdraw(int $id, $sum)
    {
        // Начало транзакции
        $this->db->beginTransaction();
        
        // Попытка выполнения блока запросов
        try {
            // Обновить баланс аккаунта
            $query = $this->db->prepare("UPDATE `account` SET balance = balance - ? WHERE id = ?");
            $query->execute(array($sum, $id));
            
            // Добавить транзакцию в историю
            $query = $this->db->prepare("INSERT INTO `transaction` SET id_account = ?, sum = ?, action = 1");
            $query->execute(array($id, $sum));
            
            // Если ошибок небыло, то подтвердить сапросы
            $this->db->commit();
            return true;
        } catch(\Exception $ex) {
            // Если возникло исключения
            // Откатить все изменения
            $this->db->rollBack();
            return $ex->getMessage();
        }
    }
}