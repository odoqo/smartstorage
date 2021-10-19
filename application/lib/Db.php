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
		public function selectFromTable(string $__table, By $__by)
		{	
			$query 	= $this->createSelect($__table, $__by);
			$result = $this->makeQuery($query);
			return $result ? $result->fetch_assoc() : false;
		}

		public function getAllField(string $__table, $__field)
		{
			$this->link-
		}


		/**
		 * 
		 */
		public function insertIntoTable(string $__table, array $__data)
		{
			$query  = $this->createInsert($__table, $__data);
			$result = $this->makeQuery($query);
			return $result;
		}

		/**
		 * 
		 */
		public function deleteFromTable(string $__table, By $__by)
		{
			$query  = $this->createDelete($__table, $__by);
			$result = $this->makeQuery($query);
			return $result;
		}

		/**
		 * 
		 */
		private function createSelect(string $__table, By $__by)
		{
			switch ($__by->getMechanism()) {
			
				// by id
				case 'id':
					return "SELECT * FROM `$__table` WHERE id='{$__by->getValue()}'";
				
				// by username
				case 'login':
					return "SELECT * FROM `$__table` WHERE login='{$__by->getValue()}'";
			}
		}

		/**
		 *
		 */
		private function createDelete(string $__table, By $__by)
		{
			switch ($__by->getMechanism()) {
			
				// by id
				case 'id':
					return "DELETE FROM `$__table` WHERE id='{$__by->getValue()}'";
				
				// by username
				case 'login':
					return "DELETE FROM `$__table` WHERE login='{$__by->getValue()}'";
			}
		}

		/**
		 * 
		 */
		private function createInsert(string $__table, array $__data)
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