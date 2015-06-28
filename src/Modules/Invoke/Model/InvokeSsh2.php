<?php

namespace Model;

class InvokeSsh2 {

    // Compatibility
    public $os = array("any");
    public $linuxType = array("any");
    public $distros = array("any");
    public $versions = array("any");
    public $architectures = array("any");

    // Model Group
    public $modelGroup = array("DriverNativeSSH");

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
    public function setServer($server)
    {
        $this->server = $server;
    }

	/**
	 * @throws \Exception
	 */
    public function connect() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params) ;
        if (!function_exists("ssh2_connect")) {
            $logging->log('Native PHP SSH2 Functions are not installed. Cannot use the PHP Native SSH Driver', "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ;
            return false; }
        if (!($this->connection = ssh2_connect($this->server->host, $this->server->port))) {
            $logging->log('Cannot connect to server', "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ;
            return false; }
        if (substr($this->server->password, 0, 4) == 'KS::') {
            $ksf = new SshKeyStore();
            $ks = $ksf->getModel(array("key" => $this->server->password, "guess" => "true")) ;
            $this->server->password = $ks->findKey() ; }
        ssh2_auth_password($this->connection, $this->server->username, $this->server->password);
    }

	/**
	 * @param $command
	 * @return string
	 * @throws \Exception
	 */
	public function exec($command) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params) ;
        if (!function_exists("ssh2_exec")) {
            $logging->log('Native PHP SSH2 Functions are not installed. Cannot use the PHP Native SSH Driver', "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ;
            return false; }
		if (!($stream = ssh2_exec($this->connection, $command))) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params) ;
            $logging->log("SSH command failed", "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ;
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