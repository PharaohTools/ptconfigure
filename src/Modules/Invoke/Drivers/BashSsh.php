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

	protected $commandsPipe;

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
			$launcher = 'ssh -i '.escapeshellarg($this->server->password);
		} else{
			$launcher = 'sshpass -p '.escapeshellarg($this->server->password).' ssh';
		}


		$this->commandsPipe = tempnam(null, 'ssh');

		$launcher .= " -T -p {$this->server->port} ";
		$launcher .= escapeshellarg($this->server->username.'@'.$this->server->host);

		$pipe = "tail -f {$this->commandsPipe}";
		if(!pcntl_fork()){
			$fp = popen("$pipe | $launcher" ,"r");
			while (!feof($fp)) {
				echo fgets($fp, 4096);
			}
			pclose($fp);
			exit;
		}
	}

	/**
	 * @param $command
	 * @return string
	 */
	public function exec($command)
	{
		file_put_contents($this->commandsPipe, $command.PHP_EOL, FILE_APPEND);
	}
}