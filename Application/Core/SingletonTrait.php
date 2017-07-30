<?php
namespace Application\Core;

/**
 * Трейт синглтон
 * @package Application
 * @subpackage Core
 */
trait SingletonTrait
{
    /** @var string $instance Ссылка на объект класса, использующего синглтон */
    protected static $instance;
    /** @var array $argument Параметры конструктора */
    protected static $argument;
    
    /** 
     * Получить ссылку на объект класса
     * @param mixed $argument Параметры конструктора класса, использующего синглтон
     * @throws Exception если совершается попытка получить объект с другими параметрами
     * @return object Ссылка на объект, использующий синглтон
     */
    public static function getInstance(...$argument)
    {
        // Если ссылка существует
        if (isset(static::$instance)) {
            // Если переданы аргументы конструктора
            if (isset($argument)) {
                // И если аргументы не совпадают с аргументами существующего объекта
                if (static::$argument !== $argument) {
                    // Генерировать исключение с сообщением о попытке получить объект с другими параметрами
                    throw new \Exception("Объект класса ".(__CLASS__)." уже создан с параметрами ".print_r(static::$argument, true));
                }
            }
        } else {
            // Если объект не существует, создать его и сохранить ссылку
            static::$argument = $argument;
            static::$instance = new static(...$argument);
        }
        // Вернуть ссылку на объект
        return static::$instance;
    }
    
    /** Защищенный конструктор */
    protected function __construct()
    {
    }
    
    /** Запрет копирования объекта */
    private function __clone()
    {
    }
    
    /** Запрет десериализации объекта */
    private function __wakeup()
    {
    }
    
    /** Сбросить ссылку на объект */
    public static function reset()
    {
        static::$instance = NULL;
    }
}