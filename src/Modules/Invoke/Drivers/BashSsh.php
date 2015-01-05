<?php namespace Invoke\Drivers;

use Invoke\Server;

class BashSsh implements Driver {

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var string
	 */
	protected $connection;

	/**
	 * @param Server $server
	 */
	public function __construct(Server $server)
	{
		$this->server = $server;
	}

	public function connect()
	{
		if(file_exists($this->server->password)){
			$this->connection = 'ssh -i '.escapeshellarg($this->server->password);
		} else{
			$this->connection = 'sshpass -p '.escapeshellarg($this->server->password).' ssh';
		}

		$this->connection .= " -p {$this->server->port}";
		$this->connection .= ' -o ControlPath='.tempnam(null, 'ssh').' -o ControlMaster=auto -o ControlPersist=3600 '.escapeshellarg($this->server->username.'@'.$this->server->host);
	}

	/**
	 * @param $command
	 * @return string
	 */
	public function exec($command)
	{
		$command = "{$this->connection} /bin/bash << EOF\n{$command}\nEOF";
		return shell_exec($command);
	}
}