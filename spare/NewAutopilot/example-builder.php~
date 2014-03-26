<?php

$auto = new AutopilotBuilder();

$auto->addStep("Module", "User")
     ->addParam("username", "dave")
     ->addParam("password", "dave");;

$auto->addStep("Module", "User")->addParam("username", "ubuntu")->addParam("password", "ubuntu");

$auto->addStep("Module", "SshHarden")
     ->addParam("password", "dave")
     ->addParam("username", "dave")

$autopilotExecutor = new AutopilotExecutor();
$autopilotExecutor->setBuilder($this);
$autopilotExecutor->go();

?>


