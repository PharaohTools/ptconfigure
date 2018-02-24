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
	public function setServer($server) {
		$this->server = $server;
	}

    public function connect() {
        if (substr($this->server->password, 0, 4) == 'KS::') {
            $ksf = new SshKeyStore();
            $ks = $ksf->getModel(array("key" => $this->server->password, "guess" => "true")) ;
            $this->server->password = $ks->findKey() ; }
        $launcher = $this->getLauncher() ;
        $this->commandsPipe = tempnam(null, 'ssh');
        if (!function_exists("pcntl_fork")) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Unable to use pcntl_fork, ending", 'Invoke') ;
            return false ; }
        $pcomm = "$launcher 'echo Pharaoh Tools'" ;
        passthru($pcomm, $res) ;
        return true ;
    }

    public function exec($command) {
        $launcher = $this->getLauncher() ;
        $pcomm = "$launcher $command" ;
        $data = system($pcomm, $res) ;
        return array("rc" => $res, "data" => $data) ;
    }

    public function getLauncher() {
        if(file_exists($this->server->password)){
            $launcher = 'ssh -t -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i '.escapeshellarg($this->server->password); }
        else{
            $launcher = 'sshpass -p '.escapeshellarg($this->server->password).' ssh -t -o UserKnownHostsFile=/dev/null ' .
                '-o StrictHostKeyChecking=no -o PubkeyAuthentication=no'; }
        $launcher .= " -T -p {$this->server->port} ";
        $launcher .= escapeshellarg($this->server->username.'@'.$this->server->host);
        return $launcher ;
    }

}