<?php namespace Invoke\Drivers;

use Net_SSH2;
use Invoke\Server;

class PhpSecLib implements Driver {

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var Net_SSH2
	 */
	protected $connection;

	public function __construct(Server $server)
	{
		$this->server = $server;
	}

	public function connect()
	{
		$this->connection = new Net_SSH2($this->server->host, $this->server->port);
		if( ! $this->connection->login($this->server->username, $this->server->password) ){
			throw new \Exception("Login failed!");
		}
	}

	public function exec($command)
	{
		return $this->connection->exec($command);
	}

	public function __call($k, $args = [])
	{
		return call_user_func_array([$this->connection, $k], $args);
	}
}