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
            $logging->log('Native PHP SSH2 Functions are not installed.', "Invoke - PHP SSH") ;
            if (isset($this->params["guess"]) && $this->params["guess"]==true) {
                $logging->log('Guessing that we should try to install Native PHP SSH2 Functions.', "Invoke - PHP SSH") ;
                $phpSSHFactory = new \Model\PHPSSH();
                $phpSSH = $phpSSHFactory->getModel($this->params) ;
                $res = $phpSSH->ensureInstalled();
                if ($res == false) {
                    $logging->log('Cannot use the PHP Native SSH Driver.', "Invoke - PHP SSH") ;
                    return false; }
                else if ($res == true && !function_exists("ssh2_connect")) {
                    $logging->log('Unable to access PHP SSH Functions. Possible restart required.', "Invoke - PHP SSH") ;
                    return false; }
                else {
                    // this will go fine to the connection bit
                } }
            else {
                $logging->log('Cannot use the PHP Native SSH Driver.', "Invoke - PHP SSH") ;
                return false; } }
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
//            if (isset($this->params["guess"])) {
            else {
                $pubkey = $this->server->password.".pub" ;
            }
//            }
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
            $logging->log('Native PHP SSH2 Functions are not installed.', "Invoke - PHP SSH") ;
            if (isset($this->params["guess"]) && $this->params["guess"]==true) {
                $logging->log('Guessing that we should try to install Native PHP SSH2 Functions.', "Invoke - PHP SSH") ;
                $phpSSHFactory = new \Model\PHPSSH();
                $phpSSH = $phpSSHFactory->getModel($this->params) ;
                $res = $phpSSH->ensureInstalled();
                if ($res == false) {
                    $logging->log('Cannot use the PHP Native SSH Driver.', "Invoke - PHP SSH") ;
                    \Core\BootStrap::setExitCode(1) ;
                    return false; } }
            else {
                $logging->log('Cannot use the PHP Native SSH Driver.', "Invoke - PHP SSH") ;
                \Core\BootStrap::setExitCode(1) ;
                return false; } }
        $rc = $this->improvedExec($command) ;
//        var_dump("cur rc: ", $rc) ;
        return $rc;
    }

    protected function improvedExec( $command ) {
        $result = $this->rawExec( '('.$command.' && echo -en "\n$?" ;)' );
        $pres = preg_match( "/^(.*)\n(0|-?[1-9][0-9]*)$/s", $result[0], $matches ) ;
        if( $pres === false ) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params) ;
            $logging->log("No return status found from command", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;  }
        $res = array() ;
        $res["rc"] = (isset($matches[2])) ? (int) $matches[2] : 0 ;
        $res["data"] = (isset($matches[1])) ? $matches[1] : $result[0] ;
        return $res;
    }

    protected function rawExec( $command ) {
        if (!($this->stream = ssh2_exec($this->connection, $command, "vanilla"))) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params) ;
            $logging->log("SSH command failed", "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ; }

        $error_stream = ssh2_fetch_stream( $this->stream, SSH2_STREAM_STDERR );
        stream_set_blocking( $this->stream, TRUE );
        stream_set_blocking( $error_stream, TRUE );
        $data = "" ;
        $error_output = "" ;
        while ($buf = fread($this->stream, 16)) {
            $data .= $buf;
            echo $buf ;
        }
        $eo = stream_get_contents( $error_stream ) ;
        if (strlen($eo)>0) { $error_output .= $eo ; }
        fclose( $this->stream );
        fclose( $error_stream );
        return array( '', $error_output );
    }

    protected function findRC() {
        if (!($this->stream = ssh2_exec($this->connection, "echo $?", "vanilla"))) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params) ;
            $logging->log("SSH command failed", "Invoke - PHP SSH") ;
            \Core\BootStrap::setExitCode(1) ; }
        stream_set_blocking( $this->stream, TRUE );
        $data = "" ;
        $error_output = "" ;
        while ($buf = fread($this->stream, 1048576)) { $data .= $buf; }
        fclose( $this->stream );
        return $data ;
    }

    /**
     * @throws \Exception
     */
    public function disconnect() {
        $this->exec('echo "EXITING" && exit;');
        $this->connection = null;
    }
}