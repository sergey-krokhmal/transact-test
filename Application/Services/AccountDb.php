<?php
namespace Application\Services;

use Application\Core\SingletonTrait;

/**
 * Сервис доступа к аккаунтам БД
 * Использует трейт синглона SingletonTrait
 * @see SingletonTrait
 * @package Application
 * @subpackage Services
 */
class AccountDb
{
    use SingletonTrait;
    
    /** @var object $db Подключение к БД */
    private $db;
    
    /** Конструктор создающий подключение к БД*/
    protected function __construct()
    {
        // Создание подключения к БД для сервиса
        $this->db = new \PDO('mysql:host=localhost;dbname=bit_money', 'test', 's78A5oTjhBZyeTQi', array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_TIMEOUT => 1200,
        ));
    }
    
    /**
     * Выборка аккаунта по id из БД
     * @param int $id Id аккаунта
     * @return array|false Массив данных записи аккаунта
     */
    public function getAccountById(int $id)
    {
        $query = $this->db->prepare("SELECT * FROM `account` WHERE id = ?");
        if ($query->execute(array($id))) {
            if ($query->rowCount() > 0) {
                $account = $query->fetch();
                $query->closeCursor();
                return $account;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Выборка аккаунта по логину из БД
     * @param string $login Логин
     * @return array|false Массив данных записи аккаунта
     */
    public function getAccountByLogin(string $login)
    {
        $query = $this->db->prepare("SELECT * FROM `account` WHERE login = ?");
        if ($query->execute(array($login))) {
            if ($query->rowCount() > 0) {
                $account = $query->fetch();
                $query->closeCursor();
                return $account;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Выборка аккаунта по id и паролю
    /**
     * Выборка аккаунта по id и паролю из БД
     * @param int $id Id аккаунта
     * @param string $password Палоль
     * @return array|false Массив данных записи аккаунта
     */
    public function getAccountByIdAndPass(int $id, string $password)
    {
        $query = $this->db->prepare("SELECT * FROM `account` WHERE password = ? AND id = ?");
        if ($query->execute(array($password, $id))) {
            if ($query->rowCount() > 0) {
                $account = $query->fetch();
                $query->closeCursor();
                return $account;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Выборка транзакций по id аккаунта
    /**
     * Выборка транзакций по id аккаунта из БД
     * @param int $id_account Id аккаунта
     * @return array|false Массив строк данных транзаций аккаунта
     */
    public function getTransactionsByIdAccount(int $id_account)
    {
        $query = $this->db->prepare("SELECT * FROM `transaction` WHERE id_account = ? ORDER BY time DESC");
        if ($query->execute(array($id_account))) {
            if ($query->rowCount() > 0) {
                $transactions = $query->fetchAll();
                $query->closeCursor();
                return $transactions;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Вывод средств
     * Уменьшение баланса на сумму списания и вставка соответствующей транзакции
     * @param int $id Id аккаунта
     * @param int $sum Сумма списания
     * @return string|true Сообщение об ошибке
     */
    public function withdraw(int $id, $sum)
    {
        // Начало транзакции
        $t = $this->db->beginTransaction();
        
        // Попытка выполнения блока запросов
        try {
            if ($account = $this->getAccountById($id)) {
                // Проверка суммы списания на ошибки
                if (!is_numeric($sum)) {
                    throw new \Exception("Для вывода средств нужно указать число");
                } elseif ($sum < 0.01) {
                    throw new \Exception("Сумма вывода должна быть больше или равной 0.01");
                } elseif ($sum > $this->getAccountById($id)['balance']) {
                    throw new \Exception("Не достаточно средств для вывода ".number_format($sum, 2, ',', ' '));
                }
            } else {
                throw new \Exception("Аккаунта с id = $id нет");
            }
            // Обновить баланс аккаунта
            $query = $this->db->prepare("UPDATE `account` SET balance = balance - ? WHERE id = ?");
            $query->execute(array($sum, $id));
            
            if ($this->getAccountById($id)['balance'] < 0) {
                throw new \Exception("Не достаточно средств для вывода ".number_format($sum, 2, ',', ' '));
            }
            
            // Добавить транзакцию в историю
            $query = $this->db->prepare("INSERT INTO `transaction` SET id_account = ?, sum = ?, action = 1");
            $query->execute(array($id, $sum));
            
            // Если ошибок небыло, то подтвердить сапросы
            $this->db->commit();
            $query->closeCursor();
            return true;
        } catch(\Exception $ex) {
            // Если возникло исключения
            // Откатить все изменения
            $this->db->rollBack();
            return $ex->getMessage();
        }
    }
}