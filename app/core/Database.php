<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

namespace App\Core;

use \PDO;
use \Exception;
use \Throwable;

class Database
{
    /**
	 * Database connection instance variable
     * 
     * @var \PDO $connection
     */
    private static $connection;

    /**
     * Connect to database
     */
    public function __construct()
    {
        self::$connection = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', config('sql.host'), config('sql.port'), config('sql.database')), config('sql.username'), config('sql.password'), [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		]);
		
		self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    /**
	 * Prepare SQL statement and execute it
	 *
	 * @param string $query
	 * @param array $array
	 * @param mixed $result
	 * 
	 * @return object
	 */
	public function prepare($query, $array, &$result)
	{
		$result = self::$connection->prepare($query);
		$result->execute($array);

		return $result;
    }
    
    /**
	 * Get all data from database result
	 *
	 * @param string $query
	 * @param array $array
	 * 
	 * @return array|false
	 */
	public function fetchAll($query, $array = [ ])
	{
		$this->prepare($query, $array, $result);
		return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    
	/**
	 * Get all column data from the first database result
	 *
	 * @param string $query
	 * @param array $array
	 * 
	 * @return array|false
	 */
	public function fetchResult($query, $array = [ ])
	{
		$this->prepare($query, $array, $result);
		return $result->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Get column data from the first database result
	 *
	 * @param string $query
	 * @param array $array
	 * 
	 * @return array|false
	 */
	public function fetchColumn($query, $array = [ ])
	{
		$this->prepare($query, $array, $result);
		return $result->fetchColumn();
	}
	
	/**
	 * Get row count from a query result
	 *
	 * @param string $query
	 * @param array $array
	 * 
	 * @return int
	 */
	public function rowCount($query, $array = [ ])
	{
		$this->prepare($query, $array, $result);
		return $result->rowCount();
	}
	
	/**
	 * Run SQL query
	 *
	 * @param string $query
	 * @param array $array
	 * 
	 * @return void
	 */
	public function run($query, $array = [ ])
	{
		try {
			self::$connection->beginTransaction();
			$this->prepare($query, $array, $result);
			$inserted = self::$connection->lastInsertId();
			self::$connection->commit();

			return $inserted;
		} catch (Exception $e) {
			self::$connection->rollback();
            throw $e;
        } catch (Throwable $e) {
			self::$connection->rollback();
            throw $e;
        }
	}
	
	/**
	 * Destroy database connection
	 */
	public function __destruct()
	{
		self::$connection = null;
	}
}
