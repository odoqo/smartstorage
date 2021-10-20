<?php

namespace application\lib;

class Db
	{
		// database manager
		private $link;

		/**
		 * 
		 */
		public function __construct()
		{
			//connect to database
			$config = require 'application/config/db.php';
			$this->link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['name']);
		}

		/**
		 * 
		 */
		public function selectRow(string $__table, By $__by)
		{	
			$query 	= $this->createSelectRow($__table, $__by);
			$result = $this->makeQuery($query);
			return $result ? $result->fetch_assoc() : false;
		}

		public function selectCols(string $__table, array $__fields)
		{	
			$query 	= $this->createSelectCols($__table, $__fields);
			$result = $this->makeQuery($query);
			return $result ? $result->fetch_all() : false;
		}

		/**
		 * 
		 */
		public function insertRow(string $__table, array $__data)
		{
			$query  = $this->createInsertRow($__table, $__data);
			$result = $this->makeQuery($query);
			return $result;
		}

		/**
		 * 
		 */
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

		private function createUpdate(string $__table, By $__by, array $__sets)
		{
			$fields='';
			foreach ($__sets as $field => $value) {
				$fields .= $field . " = '$value', ";
			}

			$fields = substr($fields, 0, -2);
			
			switch ($__by->getMechanism()) {
			
				// by id
				case 'id':
					$id = $__by->getValue()['id'];
					return "UPDATE `$__table` SET $fields WHERE id='{$id}'";
				
				// by username
				case 'login':
					$login = $__by->getValue()['login'];
					return "UPDATE `$__table` SET $fields WHERE login='{$login}'";

				// by login and cookie
				case 'loginAndCookie':
					$login  = $__by->getValue()['login'];
					$cookie = $__by->getValue()['cookie'];
					return "UPDATE `$__table` SET $fields WHERE login='{$login}' AND cookie='{$cookie}'";

				// by login and password
				case 'loginAndPassword':
					$login    = $__by->getValue()['login'];
					$password = $__by->getValue()['password'];
					return "UPDATE `$__table` SET $fields WHERE login='{$login}' AND cookie='{$password}'";
			}
		}

		/**
		 * 
		 */
		private function createSelectRow(string $__table, By $__by)
		{
			switch ($__by->getMechanism()) {
			
				// by id
				case 'id':
					$id = $__by->getValue()['id'];
					return "SELECT * FROM `$__table` WHERE id='{$id}'";
				
				// by username
				case 'login':
					$login = $__by->getValue()['login'];
					return "SELECT * FROM `$__table` WHERE login='{$login}'";

				// by login and cookie
				case 'loginAndCookie':
					$login  = $__by->getValue()['login'];
					$cookie = $__by->getValue()['cookie'];
					return "SELECT * FROM `$__table` WHERE login='{$login}' AND cookie='{$cookie}'";

				// by login and password
				case 'loginAndPassword':
					$login    = $__by->getValue()['login'];
					$password = $__by->getValue()['password'];
					return "SELECT * FROM `$__table` WHERE login='{$login}' AND cookie='{$password}'";
			}
		}

		private function createSelectCols(string $__table, array $__fields)
		{
			$fields='';
			foreach ($__fields as $field) {
				$fields .= $field . ', ';
			}

			$fields = substr($fields, 0, -2);

			return "SELECT $fields FROM `$__table`";
		}

		/**
		 *
		 */
		private function createDeleteRow(string $__table, By $__by)
		{
			switch ($__by->getMechanism()) {
			
				// by id
				case 'id':
					$id = $__by->getValue()['id'];
					return "DELETE FROM `$__table` WHERE id='{$id}'";
				
				// by username
				case 'login':
					$login = $__by->getValue()['login'];
					return "DELETE FROM `$__table` WHERE login='{$login}'";

				// by login and cookie
				case 'loginAndCookie':
					$login  = $__by->getValue()['login'];
					$cookie = $__by->getValue()['cookie'];
					return "DELETE FROM `$__table` WHERE login='{$login}' AND cookie='{$cookie}'";

				// by login and password
				case 'loginAndPassword':
					$login    = $__by->getValue()['login'];
					$password = $__by->getValue()['password'];
					return "DELETE FROM `$__table` WHERE login='{$login}' AND cookie='{$password}'";
			}
		}

		/**
		 * 
		 */
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

		/**
		 * 
		 */
		private function makeQuery(string $__query)
		{
			$result = mysqli_query($this->link, $__query);
			return $result;
		}
		


	}