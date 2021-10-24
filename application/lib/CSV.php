<?php
 
namespace application\lib;

use Exception;

class CSV 
{
    private $file = null;
    private $tmpFile = null;
    private $separator = null;

    public function __construct($__file) {

        if (!preg_match('/^.+\.csv$/', $__file)) {
            throw new Exception("Файл '$__file' не найден"); 
        }

        $tmpFile = preg_replace('/\.csv$/', '_tpm.csv', $__file);

        touch($__file);
        touch($tmpFile);
        $this->file = $__file;
        $this->tmpFile = $tmpFile;
    }
 
    public function writeRow(array $__data) 
    {    
        $handle = fopen($this->file, "a"); 
        flock($handle, LOCK_EX);
        fputcsv($handle, $__data, ";");
        fclose($handle);
    }

    public function deleteRow(string $__firstField)
    {
        $handle = fopen($this->file, 'r');
        $temp_handle = fopen($this->tmpFile, 'w');
        flock($handle, LOCK_SH);
        flock($temp_handle, LOCK_EX);
    
        while (($lineData = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if (reset($lineData) == $__firstField) {
                continue;
            }
            fputcsv($temp_handle, $lineData, ";");
        }

        fclose($handle);
        fclose($temp_handle);
        rename($this->tmpFile, $this->file);
    
    }

    public function readRow(string $__firstField)
    {
        $handle = fopen($this->file, 'r');
        flock($handle, LOCK_SH);
        
        while (($lineData = fgetcsv($handle, 1000, ";")) !== false) {
            if ($lineData[0] === $__firstField) {
                break;
            }
        }
        fclose($handle);
        return $lineData;
    }
}