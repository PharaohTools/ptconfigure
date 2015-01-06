<?php namespace Invoke;

use Invoke\Drivers\BashSsh;
use Invoke\Drivers\Driver;

class Server {

	public $host;
	public $username;
	public $password;
	public $port;

	/**
	 * @var Driver
	 */
	protected $driver;

	function __construct($host, $user, $password, $port = 22)
	{
		$this->host = $host;
		$this->username = $user;
		$this->password = $this->formatPassword($password);
		$this->port = $port;
		$this->setDriver(new BashSsh($this));
	}

	public function formatPassword($password)
	{
		if (substr($password, 0, 1) == '~') {
			$password = str_replace('~', $_SERVER['HOME'], $password) ;
		}

		return $password;
	}

	/**
	 * @param mixed $driver
	 */
	public function setDriver($driver)
	{
		$this->driver = $driver;
	}

	public function connect()
	{
		return $this->driver->connect();
	}

	public function exec($cmd)
	{
		return $this->driver->exec($cmd);
	}

	public function __call($k, $args = [])
	{
		return call_user_func_array([$this->driver, $k], $args);
	}
}