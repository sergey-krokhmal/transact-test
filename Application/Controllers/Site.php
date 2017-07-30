<?php
namespace Application\Controllers;

use Application\Core\BaseController;
use Application\Services\AccountManager;
use Application\Services\CommonUtils;

/**
 * Класс главного контроллера приложения, расширяет класс BaseController
 * @see BaseController
 * @package Application
 * @subpackage Controllers
 */
class Site extends BaseController
{
    /**
     * Метод отображения главной страницы с балансом и историей вывода средств
     * При пост запросе осуществляет вывод средств
     * @param array $params Параметры запроса
     * @param string $params['withdraw'] Подтверждение списания средств
     * @param int $params['sum'] Сумма списания
     */
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
            if ($transactions) {
                foreach($transactions as $i => $transaction) {
                    $transactions[$i]['f_sum'] = number_format($transaction['sum'], 2, ',', ' ');
                }
            } else {
                $transactions = array();
            }
            
            $data['transactions'] = $transactions;
            // Передать в шаблон класс вспомогательных функций
            $data['common_utils'] = new CommonUtils();
            // Загрузить шаблон с данными
            $this->loadTemplate('Site', 'index', $data);
        } else {
            // Иначе перейти на страницу авторизации
            header("Location: /Site/login");
        }
    }
    
    /**
     * Метод отображения страницы авторизации в приложении
     * При пост запросе осуществляет проверку и авторизацию с переходом на глвную страницу
     * @param array $params параметры запроса
     * @param string $params['submit'] Подтверждение авторизации
     * @param string $params['login'] Логин
     * @param string $params['pass'] Пароль
     */
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
    
    /**
     * Метод выхода из аккаунта с переходом на страницу авторизации
     */
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