<?php

Namespace Model;

class LetsEncryptAllOS extends Base {

	// Compatibility
	public $os = array("any");
	public $linuxType = array("any");
	public $distros = array("any");
	public $versions = array("any");
	public $architectures = array("any");

	// Model Group
	public $modelGroup = array("Default");

	protected $servers = array();
	protected $sshCommands;
	protected $isNativeSSH;
    protected $hopScript ;
    protected $hopEndEnvironment ;

    public function __construct($params) {
        require dirname(__DIR__).DS.'Libraries'.DS.'LetsEncrypt'.DS.'Lescript.php';
    }

	public function askWhetherToLetsEncryptSSHShell() {
		return $this->performLetsEncryptSSHShellWithHops();
	}

	public function askWhetherToLetsEncryptSSHScript() {
        if (isset($this->params["hops"])) {
            return $this->performLetsEncryptSSHScriptWithHops() ; }
        else {
            return $this->performLetsEncryptSSHScript() ; }
	}

	public function askWhetherToLetsEncryptSSHData() {
        if (isset($this->params["hops"])) {
            return $this->performLetsEncryptSSHDataWithHops() ; }
        else {
            return $this->performLetsEncryptSSHData() ; }
	}


	public function performEncryptionInstall() {


        if(!defined("PHP_VERSION_ID") || PHP_VERSION_ID < 50300 || !extension_loaded('openssl') || !extension_loaded('curl')) {
            die("You need at least PHP 5.3.0 with OpenSSL and curl extension\n");
        }

        // Configuration:
        $domain = $this->params["domain"];
        $webroot = $this->params["webroot"];
        $certlocation = $this->params["cert-path"];

        if ($domain=="" || $webroot=="" || $certlocation=="") {

            echo "Fuckit im dead\n" ;
            return false ;
        }

        // Always use UTC
        //date_default_timezone_set("UTC");

        // Make sure our cert location exists
        if (!is_dir($certlocation)) {
            // Make sure nothing is already there.
            if (file_exists($certlocation)) { unlink($certlocation); }
            mkdir ($certlocation); }

        // Do we need to create or upgrade our cert? Assume no to start with.
        $needsgen = false;

        // Do we HAVE a certificate for all our domains?
        $certfile = "$certlocation/$domain/cert.pem";

        if (!file_exists($certfile)) {
            // We don't have a cert, so we need to request one.
            $needsgen = true;
        } else {
            // We DO have a certificate.
            $certdata = openssl_x509_parse(file_get_contents($certfile));

            // If it expires in less than a month, we want to renew it.
            $renewafter = $certdata['validTo_time_t']-(86400*30);
            if (time() > $renewafter) {
                // Less than a month left, we need to renew.
                $needsgen = true;
            }
        }

        // Do we need to generate a certificate?
        if ($needsgen) {
            try {
                $le = new \Analogic\ACME\Lescript($certlocation, $webroot, null);
                # or without logger:
                # $le = new Analogic\ACME\Lescript($certlocation, $webroot);
                $le->initAccount();
                $le->signDomains(array($domain));

            } catch (\Exception $e) {
//                $logger->error($e->getMessage());
//                $logger->error($e->getTraceAsString());
                // Exit with an error code, something went wrong.
                echo "Fuckit im dead\n" ;
                exit(1);
            }
        }

        // Create a complete .pem file for use with haproxy or apache 2.4,
        // and save it as domain.name.pem for easy reference. It doesn't
        // matter that this is updated each time, as it'll be exactly
        // the same.
        $pem = file_get_contents("$certlocation/$domain/fullchain.pem")."\n".file_get_contents("$certlocation/$domain/private.pem");
        file_put_contents("$certlocation/$domain.pem", $pem);

        return true;
	}

}