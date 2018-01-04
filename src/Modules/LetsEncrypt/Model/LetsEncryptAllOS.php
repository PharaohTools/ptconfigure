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

	public function performEncryptionInstall() {

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $wu_time = (isset($this->params["wait"]) && $this->params["wait"]==true) ? $this->params["wait"] : 3 ;
        $logging->log("Waiting for Web Server warm up of {$wu_time} seconds", $this->getModuleName()) ;
        for ($i=1; $i<=$wu_time; $i++)  {
            sleep(1) ;
            echo "." ; }

        if (!class_exists('LetsEncryptWrap')) {
            require dirname(__DIR__).DS.'Libraries'.DS.'LetsEncrypt'.DS.'LetsEncryptWrap.php'; }

        if(!defined("PHP_VERSION_ID") || PHP_VERSION_ID < 50300 || !extension_loaded('openssl') || !extension_loaded('curl')) {
            $logging->log("You need at least PHP 5.3.0 with OpenSSL and curl extensions", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }

        // Configuration:
        $domain = $this->params["domain"];
        $webroot = $this->params["webroot"];
        $certlocation = $this->params["cert-path"];

        if ($domain=="" || $webroot=="" || $certlocation=="") {
            $logging->log("Domain, Webroot and Certificate location are required", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
            return false ; }

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
                $le = new \LetsEncryptWrap($certlocation, $webroot, null);
                # or without logger:
                # $le = new Analogic\ACME\Lescript($certlocation, $webroot);
                $le->initAccount();
                $le->signDomains(array($domain));

            } catch (\Exception $e) {
//                $logger->error($e->getMessage());
//                $logger->error($e->getTraceAsString());
                // Exit with an error code, something went wrong.
                $logging->log("Unable to generate this certificate :{$e->getMessage()}", $this->getModuleName()) ;
                return true ;
            }
        }

        // Create a complete .pem file for use with haproxy or apache 2.4,
        // and save it as domain.name.pem for easy reference. It doesn't
        // matter that this is updated each time, as it'll be exactly
        // the same.
        $pem = file_get_contents("$certlocation/$domain/fullchain.pem")."\n".file_get_contents("$certlocation/$domain/private.pem");
        $res = file_put_contents("$certlocation/$domain.pem", $pem);

        if ($res === false) {
            $logging->log("Unable to store certificate", $this->getModuleName()) ;
            return false;}
        else {
            $logging->log("Certificate successfully generated", $this->getModuleName()) ;
            return true;}
	}

}