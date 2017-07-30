<?php
namespace Application\Services;

/**
 * Класс вспомогательных функций
 * @package Application
 * @subpackage Services
 */
class CommonUtils
{
    /** 
     * Получить дату и время в стандартном формате
     * @param string $timestamp Метка времени в формате UTC
     * @throws Exception если значение параметра $timestamp не указано
     * @return Дата и время в формате дд.мм.гггг Ч:м:с
     */
    public function defaultFormatDate($timestamp)
    {
        if (isset($timestamp)) {
            $time = strtotime($timestamp);
            return date('d.m.Y H:i:s', $time);
        } else {
            throw new \Exception("Значение параметра timestamp не указано");
        }
    }
}