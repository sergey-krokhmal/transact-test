<?php
namespace Application\Core;

// Трейт синглтон 
trait SingletonTrait
{
	protected static $instance;	// Ссылка на объект
	
	public static function getInstance(...$argument)
	{
		static::$instance = static::$instance ?? new static(...$argument);
		return static::$instance;
	}
	
	// Защищенный конструктор
	protected function __construct()
	{
	}
	
	// Запрет копирования объекта
	private function __clone()
	{
	}
	
	// Запрет десериализации объекта
	private function __wakeup()
	{
	}
	
	// Сбросить ссылку на объект
	public static function reset()
	{
		static::$instance = NULL;
	}
}