<?php
 
namespace application\lib;

use Exception;

/**
 * Класс для работы с csv-файлами 
 * @author дизайн студия ox2.ru  
 */



class CSV 
{
 
    private $_csv_file = null;
 
    /**
     * @param string $csv_file  - путь до csv-файла
     */
    public function __construct($csv_file) {

        if (!preg_match('/^.+\.csv$/', $csv_file)) {
            throw new Exception("Файл '$csv_file' не найден"); 
        } 

        touch($csv_file);
        $this->_csv_file = $csv_file;
    }
 
    public function setCSV(Array $__data) {
        //Открываем csv для до-записи, 
        //если указать w, то  ифнормация которая была в csv будет затерта
        $handle = fopen($this->_csv_file, "a"); 
 
        foreach ($__data as $log) { //Проходим массив
            //Записываем, 3-ий параметр - разделитель поля
            fputcsv($handle, $log, ";"); 
        }
        fclose($handle); //Закрываем
    }
 
    /**
     * Метод для чтения из csv-файла. Возвращает массив с данными из csv
     * @return array;
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения
 
        $array_line_full = array(); //Массив будет хранить данные из csv
        //Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) { 
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle); //Закрываем файл
        return $array_line_full; //Возвращаем прочтенные данные
    }
}