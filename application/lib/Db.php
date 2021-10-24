<?php

namespace application\lib;

use Exception;

/**
 * Класс для работы с базой данных MySQL
 * 
 * @author odoqo
 */
class Db
	{
		public $link;

		public function __construct()
		{
			$config = require 'application/config/db.php';
			$this->link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['name']);
		}

		public function selectRow(string $__table, By $__by, array $__fields=[])
		{	
			$query 	= $this->createSelect($__table, $__by, $__fields);
			$result = $this->makeQuery($query);
			return $result ? $result->fetch_assoc() : [];
		}

		public function selectRows(string $__table, By $__by, array $__fields=[])
		{	
			$query 	= $this->createSelect($__table, $__by, $__fields);
			$result = $this->makeQuery($query);
			return $result ? $result->fetch_all() : [];
		}

		public function insertRow(string $__table, array $__data)
		{
			$query  = $this->createInsertRow($__table, $__data);
			$result = $this->makeQuery($query);
			return $result;
		}

		public function deleteRow(string $__table, By $__by)
		{
			$query  = $this->createDeleteRow($__table, $__by);
			$result = $this->makeQuery($query);
			return $result;
		}

		public function updateFields(string $__table, By $__by,  array $__sets)
		{
			$query  = $this->createUpdate($__table, $__by, $__sets);
			$result = $this->makeQuery($query);
			return $result;
		}

		private function makeQuery(string $__query)
		{
			$result = mysqli_query($this->link, $__query);
			return $result;
		}

		private function createUpdate(string $__table, By $__by, array $__sets)
		{
			$fields='';
			foreach ($__sets as $field => $value) {
				$fields .= $field . " = '$value', ";
			}

			$fields = substr($fields, 0, -2);
			
			switch ($__by->getMechanism()) {
			
				case 'id':
					$id = $__by->getValue()['id'];
					return "UPDATE `$__table` SET $fields WHERE id='{$id}'";
				
				case 'login':
					$login = $__by->getValue()['login'];
					return "UPDATE `$__table` SET $fields WHERE login='{$login}'";

				case 'loginAndCookie':
					$login  = $__by->getValue()['login'];
					$cookie = $__by->getValue()['cookie'];
					return "UPDATE `$__table` SET $fields WHERE login='{$login}' AND cookie='{$cookie}'";

				case 'loginAndPassword':
					$login    = $__by->getValue()['login'];
					$password = $__by->getValue()['password'];
					return "UPDATE `$__table` SET $fields WHERE login='{$login}' AND password='{$password}'";
				
				default:
					return false;
				}
		}

		private function createSelect(string $__table, By $__by,  array $__fields=[])
		{

			$fields='';
			foreach ($__fields as $field) {
				$fields .= $field . ', ';
			}

			$fields = $fields ? substr($fields, 0, -2) : '*';

			switch ($__by->getMechanism()) {
			
				case 'all' :
					return "SELECT $fields FROM `$__table`";

				case 'id':
					$id = $__by->getValue()['id'];
					return "SELECT $fields FROM `$__table` WHERE id='{$id}'";
				
				case 'login':
					$login = $__by->getValue()['login'];
					return "SELECT $fields FROM `$__table` WHERE login='{$login}'";

				case 'loginAndCookie':
					$login  = $__by->getValue()['login'];
					$cookie = $__by->getValue()['cookie'];
					return "SELECT $fields FROM `$__table` WHERE login='{$login}' AND cookie='{$cookie}'";

				case 'loginAndPassword':
					$login    = $__by->getValue()['login'];
					$password = $__by->getValue()['password'];
					return "SELECT $fields FROM `$__table` WHERE login='{$login}' AND password='{$password}'";
			    
				case 'notLogin' :
					$login = $__by->getValue()['login'];
					return "SELECT $fields FROM `$__table` WHERE login!='{$login}'";

				case 'location' :
					$location  = $__by->getValue()['location'];
					return "SELECT $fields FROM `$__table` WHERE location='{$location}'";	

				case 'nameAndLocation' :
					$location  = $__by->getValue()['location'];
					$name	   = $__by->getValue()['name'];
				 	return "SELECT $fields FROM `$__table` WHERE location='{$location}' AND name='{$name}'";		

				case 'virtualPath' :
					$virtualPath = $__by->getValue()['virtualPath'];
					return "SELECT $fields FROM `$__table` WHERE virtual_path='{$virtualPath}'";		

				case 'locationAndOwner' :
					$location = $__by->getValue()['location'];
					$owner = $__by->getValue()['owner'];
					return "SELECT $fields FROM `$__table` WHERE owner='{$owner}' AND location='{$location}'";
					
				case 'idAndOwner' :
					$id    = $__by->getValue()['id'];
					$owner = $__by->getValue()['owner'];
					return "SELECT $fields FROM `$__table` WHERE owner='{$owner}' AND id='{$id}'";

				default :
					return false;
				}

		}

		private function createDeleteRow(string $__table, By $__by)
		{
			switch ($__by->getMechanism()) {
			
				case 'id':
					$id = $__by->getValue()['id'];
					return "DELETE FROM `$__table` WHERE id='{$id}'";
				
				case 'login':
					$login = $__by->getValue()['login'];
					return "DELETE FROM `$__table` WHERE login='{$login}'";

				case 'loginAndCookie':
					$login  = $__by->getValue()['login'];
					$cookie = $__by->getValue()['cookie'];
					return "DELETE FROM `$__table` WHERE login='{$login}' AND cookie='{$cookie}'";

				case 'loginAndPassword':
					$login    = $__by->getValue()['login'];
					$password = $__by->getValue()['password'];
					return "DELETE FROM `$__table` WHERE login='{$login}' AND password='{$password}'";

				default :
					return false;
			}
		}

		private function createInsertRow(string $__table, array $__data)
		{
			$fieldsQuery = $valuesQuery = '';
			foreach($__data as $field => $value) {
				$fieldsQuery .= "`$field`,";
				$valuesQuery .= "'$value',";
			}

			$fieldsQuery = substr($fieldsQuery, 0, -1);
			$valuesQuery = substr($valuesQuery, 0, -1);
			return "INSERT INTO `$__table` ($fieldsQuery) VALUES ($valuesQuery)";
		}
	}