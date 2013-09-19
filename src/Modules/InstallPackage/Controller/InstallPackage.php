<?php

Namespace Controller ;

class InstallPackage extends Base {

    public function execute($pageVars) {

      $isHelp = parent::checkForHelp($pageVars) ;
      if ( is_array($isHelp) ) {
        return $isHelp; }

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];
      $action = $pageVars["route"]["action"];

      $actionsToClasses = array(
        "dev-client" => "DevClient",
        "devclient" => "DevClient",
        "dev-server-slim" => "DevServerSlim",
        "devserverslim" => "DevServerSlim",
        "dev-server" => "DevServer",
        "devserver" => "DevServer",
        "test-server" => "TestServer",
        "testserver" => "TestServer",
        "jenkins-server" => "JenkinsBuildServer",
        "jenkinsserver" => "JenkinsBuildServer",
        "build-server" => "JenkinsBuildServer",
        "buildserver" => "JenkinsBuildServer",
        "git-server" => "GitServer",
        "gitserver" => "GitServer",
        "production" => "ProductionServer",
        "prod" => "ProductionServer" );

      if (array_key_exists($action, $actionsToClasses)) {
        $className = '\\Controller\\'.$actionsToClasses[$action];
        $installPackageController = new $className();
        return $installPackageController->execute($pageVars); }

      $this->content["messages"][] = "Invalid Action - Package does not Exist";
      return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}
