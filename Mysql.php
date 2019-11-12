<?php

/**
 * Set the environment variables :
 * SLITE_LIB_PHP_MYSQL_NAME
 * SLITE_LIB_PHP_MYSQL_HOST (optional)
 * SLITE_LIB_PHP_MYSQL_USER (optional if you are running in non-secure mode)
 * SLITE_LIB_PHP_MYSQL_PASS (optional if you are running in non-secure mode)
 * SLITE_LIB_PHP_MYSQL_PORT (optional)
 * 
 * Construct this class and exectute a query like so:
 * 
 * <code>
 * $db = new Mysql();
 * $data = $db->get("SELECT * FROM users");
 * print_r($data);
 * </code>
 * 
 * 
 */
class Mysql
{
	public $showErrors = true;
	private $connection;
	
	public function __construct()
	{
		$name = getenv('SLITE_LIB_PHP_MYSQL_NAME');
		$host = getenv('SLITE_LIB_PHP_MYSQL_HOST');
		$user = getenv('SLITE_LIB_PHP_MYSQL_USER');
		$pass = getenv('SLITE_LIB_PHP_MYSQL_PASS');
		$port = getenv('SLITE_LIB_PHP_MYSQL_PORT');
		
		if($host===FALSE) $host = 'localhost';
		if($user===FALSE) $user = 'root';
		if($pass===FALSE) $pass = 'root';
		if($name===FALSE) $name = 'mysql';
		if($port===FALSE) $port = '3306';

		$this->connection = new \mysqli($host, $user, $pass, $name, $port);
		
		if($this->connection===FALSE) throw new \Exception('Could not connect to database!');
	}

	/**
	 * Executes a query and returns the result as a 2 dimensional associative array.
	 * 
	 * @param type $sql
	 */
	public function get($sql)
	{
		$rs = $this->connection->query($sql);
		
		if($this->connection->error)
		{
			if($this->showErrors)
			{
				echo $this->connection->error;
				echo $sql;
			}
		}
		else
		{
			return $rs->fetch_all(MYSQLI_ASSOC);
		}
		
		return null;
	}
	
	public function getRow($sql)
	{
		$data = $this->get($sql);
		if($data!=null) return $data[0];
		return null;
	}
	
	public function getCell($sql)
	{
		$data = $this->get($sql);
		if($data!=null) foreach($data[0] as $value) return $value;
		return null;
	}
	
	public function put($sql)
	{
		$rs = $this->connection->query($sql);
		if($this->connection->error)
		{
			if($this->showErrors)
			{
				echo $this->connection->error;
				echo $sql;
			}
		}
		else
		{
			if($rs===TRUE) return $this->connection->affected_rows();
			else return 0;
		}
	}
}
