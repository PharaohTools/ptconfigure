<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public $steps ;
    public $provider ;

    public function __construct($params = array()) {
        parent::__construct($params) ;
        $this->setProvider();
        $this->setSteps();
    }

    /* Steps */
    private function setSteps() {

        include ("settings.php") ;

        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets begin Destroying of a medium set of environments"),),),

                // Bastion
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Bastion DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-bastion",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-bastion"
                ),),),

                // Git
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the GitBucket DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-git",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-git"
                ),),),

                // Jenkins
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Jenkins DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-jenkins",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-jenkins"
                ),),),

                // Staging DB Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging DB Node DNSes" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-db-nodes",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-staging-db-nodes"
                ),),),

                // Staging DB Primary
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging Primary DB DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-db-balancer",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-staging-db-balancer",
                ),),),

                // Staging Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging Web Node DNSes" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-web-nodes",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-staging-web-nodes"
                ),),),

                // Staging Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Staging Load Balancer DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-staging-load-balancer",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-staging-load-balancer"
                ),),),

                // Production DB Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production DB Node DNSes" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-db-nodes",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-prod-db-nodes"
                ),),),

                // Production DB Primary
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production Primary DB DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-db-balancer",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-prod-db-balancer"
                ),),),

                // Production Web Nodes
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production Web Node DNSes" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-web-nodes",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-prod-web-nodes"
                ),),),

                // Production Load Balancer
                array ( "Logging" => array( "log" => array( "log-message" => "Lets delete the Production Load Balancer DNS" ),),),
                array ( "DNSify" => array("box-destroy" => array(
                    "guess" => true,
                    "environment-name" => "medium-prod-load-balancer",
                    "provider-name" => $this->params["provider"],
                    "destroy-all-boxes" => true,
                ),),),
                array ( "EnvironmentConfig" => array("delete" => array(
                    "environment-name" => "medium-prod-load-balancer"
                ),),),

                array ( "Logging" => array( "log" => array( "log-message" => "Destroying a medium set of environments complete"),),),

            );

    }

    protected function setProvider() {
        if (isset($this->params["provider"])) {
            return $this->params["provider"] ; }
        $question = "Enter name of your Cloud Provider" ;
        $this->params["provider"] = $this->askForInput($question) ;
        return $this->params["provider"] ;
    }

}
