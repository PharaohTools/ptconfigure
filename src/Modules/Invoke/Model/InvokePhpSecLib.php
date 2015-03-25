<?php

namespace Model;

class InvokePhpSecLib extends BaseLinuxApp
{

    // Compatibility
    public $os = array("any");
    public $linuxType = array("any");
    public $distros = array("any");
    public $versions = array("any");
    public $architectures = array("any");

    // Model Group
    public $modelGroup = array("DriverSecLib");

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var \Net_SSH2
     */
    protected $connection;

    /**
     * @param Server $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    public function connect()
    {
        if (!class_exists('Net_SSH2')) {
            // Always load SSH2 class from here as SFTP class tries to load it wrongly
            $srcFolder = str_replace(DS . "Model", DS . "Libraries", dirname(__FILE__));
            $ssh2File = $srcFolder . DS . "seclib" . DS . "Net" . DS . "SSH2.php";
            require_once($ssh2File);
        }
        $this->connection = new \Net_SSH2($this->server->host, $this->server->port);

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        if (!is_object($this->connection)) {
            $logging->log("Connection failed");
            \Core\BootStrap::setExitCode(1);

            return false;
        }

        $this->server->password = $this->getKeyIfAvailable($this->server->password);

        if (!$this->connection->login($this->server->username, $this->server->password)) {
            $logging->log("Login failed");
            \Core\BootStrap::setExitCode(1);

            return false;
        } else {
            $logging->log("Login successful");

            return true;
        }
    }

    public function exec($command)
    {
        $command = "$command\n";
        $stream = $this->connection->exec($command);

        return $stream;
    }

    public function __call($k, $args = [])
    {
        return call_user_func_array([$this->connection, $k], $args);
    }

    private function getKeyIfAvailable($pword)
    {
        if (substr($pword, 0, 1) == '~') {
            $home = $_SERVER['HOME'];
            $pword = str_replace('~', $home, $pword);
        }
        if (file_exists($pword)) {
            if (!class_exists('Crypt_RSA')) {
                $srcFolder = str_replace("/Model", "/Libraries", dirname(__FILE__));
                $rsaFile = $srcFolder . DS . "seclib" . DS . "Crypt" . DS . "RSA.php";
                require_once($rsaFile);
            }
            $key = new \Crypt_RSA();
            $key->loadKey(file_get_contents($pword));

            return $key;
        }

        return $pword;
    }


}