<?php
namespace Application\Core;

// Базовый синглтон 
class BaseSingleton
{
	protected static $instance;	// Ссылка на объект
	
	public static function getInstance()
	{
		static::$instance = static::$instance ?? new static();
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
}