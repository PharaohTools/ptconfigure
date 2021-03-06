<?php

Namespace Model;

use stonemax\acme2\Client;
use stonemax\acme2\constants\CommonConstant;

class LetsEncryptAllOS extends Base {

    // Compatibility
    public $os = array("any");
    public $linuxType = array("any");
    public $distros = array("any");
    public $versions = array("any");
    public $architectures = array("any");

    // Model Group
    public $modelGroup = array("Default");

    public function itrEncryptionInstall() {

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
        if (substr($webroot, -1, 1) === DS) {
            $webroot = substr($webroot, 0, strlen($webroot)-1) ;
        }
        $certlocation = (isset($this->params["cert-path"])) ? $this->params["cert-path"] : "" ;
        if ($certlocation === '') {
            $certlocation = (isset($this->params["certificate-path"])) ? $this->params["certificate-path"] : "" ;
        }
        $email = (isset($this->params["email"])) ? $this->params["email"] : "" ;
        $country = (isset($this->params["country"])) ? $this->params["country"] : "" ;
        $state = (isset($this->params["state"])) ? $this->params["state"] : "" ;
        $locality = (isset($this->params["locality"])) ? $this->params["locality"] : "" ;
        $organization = (isset($this->params["organization"])) ? $this->params["organization"] : "" ;
        $organizational_unit = (isset($this->params["organizational_unit"])) ? $this->params["organizational_unit"] : "" ;
        $street = (isset($this->params["street"])) ? $this->params["street"] : "" ;

        $logging->log("Certificate Domain: {$domain}", $this->getModuleName()) ;
        $logging->log("Certificate Webroot: {$webroot}", $this->getModuleName()) ;
        $logging->log("Certificate Location: {$certlocation}", $this->getModuleName()) ;
        $logging->log("Certificate Email: {$email}", $this->getModuleName()) ;
        $logging->log("Certificate Country Code: {$country}", $this->getModuleName()) ;
        $logging->log("Certificate State: {$state}", $this->getModuleName()) ;
        $logging->log("Certificate Locality: {$locality}", $this->getModuleName()) ;
        $logging->log("Certificate Organization {$organization}", $this->getModuleName()) ;
        $logging->log("Certificate Organizational Unit: {$organizational_unit}", $this->getModuleName()) ;
        $logging->log("Certificate Street: {$street}", $this->getModuleName()) ;

        $expected = ['domain', 'webroot', 'certlocation', 'email', 'country', 'state', 'locality', 'organization', 'organizational_unit', 'street'] ;

        $falsy = false ;
        foreach ($expected as $one_expected) {
            if (!isset($$one_expected) || $$one_expected=="") {
                $logging->log("$one_expected is required", $this->getModuleName(), LOG_FAILURE_EXIT_CODE) ;
                $falsy = true ; }
        }
        if ($falsy === true) {
            return false ;
        }

        // Make sure our cert location exists
        if (!is_dir($certlocation)) {
            // Make sure nothing is already there.
            if (file_exists($certlocation)) { unlink($certlocation); }
            mkdir ($certlocation); }

        require_once (dirname(__DIR__).DS.'Libraries'.DS.'itr-acme-client'.DS.'src'.DS.'itr-acme-client.php') ;
        require_once (dirname(__DIR__).DS.'Libraries'.DS.'itr-acme-client'.DS.'examples'.DS.'simplelogger.php') ;

        try {


            // Create the itrAcmeClient object
            $iac = new \itrAcmeClient();

            // Activate debug mode, we automatically use staging endpoint in testing mode
            // $iac->testing = true;

            // The root directory of the certificate store
            $iac->certDir = $certlocation;
            // The root directory of the account store
            $iac->certAccountDir = 'accounts';
            // This token will be attached to the $certAccountDir
            $iac->certAccountToken = $email;

            // The certificate contact information
            $iac->certAccountContact = [
                $email
            ];

            $iac->certDistinguishedName = [
                /** @var string The certificate ISO 3166 country code */
                'countryName'            => $country,
                'stateOrProvinceName'    => $state,
                'localityName'           => $locality,
                'organizationName'       => $organization,
                'organizationalUnitName' => $organizational_unit,
                'street'                 => $street
            ];

            $iac->webRootDir          = $webroot;
            $iac->appendDomain        = false;
            $iac->appendWellKnownPath = true;

            // A \Psr\Log\LoggerInterface or null The logger to use
            // At the end of this file we have as simplePsrLogger implemntation
            $iac->logger = new \simplePsrLogger;

            // Initialise the object
            $iac->init();
            $logging->log("IAC Object Initialized", $this->getModuleName()) ;

            // Create an account if it doesn't exists
            $iac->createAccount();
            $logging->log("Account Created", $this->getModuleName()) ;

            // The Domains we want to sign
            $domains_exploded = explode(',', $domain ) ;
            $domains = $domains_exploded ;

            $logging->log("Domains are : ".var_export($domains, true), $this->getModuleName()) ;
            $logging->log("Attempting Sign", $this->getModuleName()) ;

            // Sign the Domains and get the certificates
            $pem = $iac->signDomains($domains);
            $logging->log("Signed", $this->getModuleName()) ;

            // Output the certificate informatione
            // print_r($pem);

            $res[] = file_put_contents("$certlocation".DS."$domain.cert.crt", $pem['RSA']['cert']);
            $res[] = file_put_contents("$certlocation".DS."$domain.chain.pem", $pem['RSA']['chain']);
            $res[] = file_put_contents("$certlocation".DS."$domain.cert.pem", $pem['RSA']['pem']);

        } catch (\Throwable $e) {
            print_r($e->getMessage());
            print_r($e->getTraceAsString());
            $logging->log("Unable to store certificate", $this->getModuleName()) ;
            return false;
        }

        $logging->log("Certificate successfully generated", $this->getModuleName()) ;
        return true;

    }

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
