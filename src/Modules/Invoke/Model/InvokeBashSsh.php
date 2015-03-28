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

	protected $connectingDelay = 6;

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
			$this->checkSshpassPresence();
			$launcher = 'sshpass -p '.escapeshellarg($this->server->password).' ssh -o UserKnownHostsFile=/dev/null ' .
                '-o StrictHostKeyChecking=no -o PubkeyAuthentication=no'; }
		$this->commandsPipe = tempnam(null, 'ssh');
		$launcher .= " -T -p {$this->server->port} ";
		$launcher .= escapeshellarg($this->server->username.'@'.$this->server->host);
		$pipe = "tail -f {$this->commandsPipe}";
		if(!pcntl_fork()){
			if (ob_get_level() == 0)
				ob_start();

			$start = time();
			$preserved = '';
			$fp = popen("$pipe | $launcher 2>&1" ,"r");
			while (!feof($fp)) {
				$output = fgets($fp, 4096);

				if($preserved) {
					echo $preserved;
					$preserved = false;
				}
				echo $output;

				if( ! $this->shouldPreserveOutput($start)){
					ob_flush();
					flush();
				}
			}
			pclose($fp);
			exit;
		}
		$this->wait();
	}

	public function shouldPreserveOutput($start)
	{
		return (time()-$start) < $this->connectingDelay;
	}

	public function checkSshpassPresence()
	{
		$output = shell_exec('which sshpass');
		if( ! $output) {
			$sep = str_repeat('-', 40);
			echo "\e[0;31m \n\n$sep\nWe're sorry, but 'sshpass' is a mandatory dependence for the BashSsh driver. \nYou must consider running apt-get install sshpass (ubuntu) before running this command again";
			echo "\n$sep\e[0m\n";
			exit;
		}
	}

	public function wait()
	{
		echo 'Please wait until we\'ll connect to the server.';
		$step = 0.3;
		$times = $this->connectingDelay/$step;
		for($i=0; $i<$times; $i++) {
			echo '.';
			usleep($step*1000000);
		}
	}

	/**
	 * @param $command
	 * @return string
	 */
	public function exec($command) {
		file_put_contents($this->commandsPipe, $command.PHP_EOL, FILE_APPEND);
	}
}