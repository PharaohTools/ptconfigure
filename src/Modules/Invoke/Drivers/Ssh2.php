<?php namespace Invoke\Drivers;

use Invoke\Server;

class Ssh2 implements Driver {

	/**
	 * @var
	 */
	private $connection;

	/**
	 * @var Server
	 */
	private $server;

	/**
	 * @param Server $server
	 */
	public function __construct(Server $server)
	{

		$this->server = $server;
	}

	/**
	 * @throws \Exception
	 */
	public function connect()
	{
		if (!($this->connection = ssh2_connect($this->server->host, $this->server->port))) {
			throw new \Exception('Cannot connect to server');
		}

		ssh2_auth_password($this->connection, $this->server->username, $this->server->password);
	}

	/**
	 * @param $command
	 * @return string
	 * @throws \Exception
	 */
	public function exec($command)
	{
		if (!($stream = ssh2_exec($this->connection, $command))) {
			throw new \Exception('SSH command failed');
		}

		stream_set_blocking($stream, true);
		$data = "";
		while ($buf = fread($stream, 4096)) {
			$data .= $buf;
		}
		fclose($stream);
		return $data;
	}

	/**
	 * @throws \Exception
	 */
	public function disconnect() {
		$this->exec('echo "EXITING" && exit;');
		$this->connection = null;
	}
}