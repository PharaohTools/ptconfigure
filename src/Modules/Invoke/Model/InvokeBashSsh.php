<?php

namespace Model ;

class InvokeBashSsh {

    // Compatibility
    public $os = array("any");
    public $linuxType = array("any");
    public $distros = array("any");
    public $versions = array("any");
    public $architectures = array("any");

    // Model Group
    public $modelGroup = array("DriverBashSSH");

	/**
	 * @var \Model\InvokeServer
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
	public function setServer($server)
	{
		$this->server = $server;
	}

	public function connect()
	{
		if(file_exists($this->server->password)){
			$launcher = 'ssh -o PubkeyAuthentication=no -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i '.escapeshellarg($this->server->password); }
        else{
			$launcher = 'sshpass -p '.escapeshellarg($this->server->password).' ssh -o UserKnownHostsFile=/dev/null ' .
                '-o StrictHostKeyChecking=no -o PubkeyAuthentication=no'; }
		$this->commandsPipe = tempnam(null, 'ssh');
		$launcher .= " -T -p {$this->server->port} ";
		$launcher .= escapeshellarg($this->server->username.'@'.$this->server->host);
		$pipe = "tail -f {$this->commandsPipe}";
		if(!pcntl_fork()){
			$fp = popen("$pipe | $launcher" ,"r");
			while (!feof($fp)) {
				echo fgets($fp, 4096); }
			pclose($fp);
			exit; }
	}

	/**
	 * @param $command
	 * @return string
	 */
	public function exec($command) {
		file_put_contents($this->commandsPipe, $command.PHP_EOL, FILE_APPEND);
	}
}