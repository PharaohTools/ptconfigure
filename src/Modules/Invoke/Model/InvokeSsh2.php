<?php

namespace Model;

class InvokeSsh2 extends Base {

    // Compatibility
    public $os = array("any");
    public $linuxType = array("any");
    public $distros = array("any");
    public $versions = array("any");
    public $architectures = array("any");

    // Model Group
    public $modelGroup = array("DriverNativeSSH");

    public function __construct($params) {
        parent::__construct($params);
    }

    /**
     * @var
     */
    private $connection;

    /**
     * @var
     */
    private $stream;

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
        if(file_exists($this->server->password)){
            if (isset($this->params["public-key"])) {
                $pubkey = $this->params["public-key"] ; }
            if (isset($this->params["guess"])) {
                $pubkey = $this->server->password.".pub" ; }
            $rt = ssh2_auth_pubkey_file ($this->connection, $this->server->username, $pubkey, $this->server->password) ; }
        else{
            $rt = ssh2_auth_password($this->connection, $this->server->username, $this->server->password); }
        return $rt ;
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
        if (!($this->stream = ssh2_exec($this->connection, $command, "vanilla"))) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params) ;
            $logging->log("SSH command failed", "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ; }
        stream_set_blocking($this->stream, true);
        $data = "";
        while ($buf = fread($this->stream, 4096)) {
            $data .= $buf;
            echo $buf ; }
        fclose($this->stream);
        return "";
    }

    /**
     * @throws \Exception
     */
    public function disconnect() {
        $this->exec('echo "EXITING" && exit;');
        $this->connection = null;
    }
}