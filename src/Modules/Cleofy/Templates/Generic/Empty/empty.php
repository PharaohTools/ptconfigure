<?php

Namespace Core ;

class <%tpl.php%>class_name</%tpl.php%> extends AutoPilot {

    public $steps ;
<%tpl.php%>tests_property</%tpl.php%>

    public function __construct($params = null) {
        parent::__construct($params) ;
        $this->setSteps();
    }

    protected function setSteps() {
        $this->steps =
            array(
                array ( "Logging" => array( "log" => array( "log-message" => "Lets output a message in <%tpl.php%>file_name</%tpl.php%>" ),),),
                array ( "Logging" => array( "log" => array( "log-message" => "Lets output some more" ),),),
            );
    }



}