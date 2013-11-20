<?php

/*
 *    This file is a POST interface to the application
 *    it will take all of HTTP POST variables, set them as environment variables,
 *    then it will perform a normal Bootstrap of the application
 *
 */

$_REQUEST['control'] = "PapyrusEditor" ;
$_REQUEST['action'] = (isset($_REQUEST['control'])) ? $_REQUEST['control'] : "start" ;

if ( isset($_REQUEST['control']) && isset($_REQUEST['action']) ) {
  $cleo_vars = array();
  $cleo_vars[0] = __FILE__;
  $cleo_vars[1] = $_REQUEST['control'];
  $cleo_vars[2] = $_REQUEST['action'];
  foreach($_REQUEST as $post_key => $post_var) {
    if (!in_array($post_key, array('control', 'action'))) {
      $cleo_vars[] = "--$post_key=$_REQUEST[$post_key]" ; } }
  $_ENV['cleo_bootstrap'] = serialize($cleo_vars);
  include_once("../../Bootstrap.php");
}

else {
  echo 'Control or Action is missing';
  die();
}