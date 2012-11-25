<?php

/* Namespace EbayCodePractice ; */

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class EbayCodePracticeConfigClass {

    private $dbc;
    private $configModel;

	public function __construct() {
        $this->dbc = mysql_connect("localhost", "root", "ebayebay") ;
        mysql_select_db("ebayCodePractice") ; //@TODO do this as an object
	}

	public function give($wanted) {
        $this->configModel = EbayCodePracticeModelFactoryClass::getModel("Config");
        $confVars = $this->configModel->getAppConfigVars();
		if (isset($confVars[$wanted]) ) {
            return $confVars[$wanted] ;
        } else {
			$dbConfVars = $this->getDbConfVar($wanted) ;
			$dbConfResult = (count($dbConfVars)>0 ) ? $dbConfVars[0]["xonfig_val"] : "novalue" ;
            return $dbConfResult;
		}
	}

	public function setUserVar($var, $val){
		$this->setDbConfVar($var, $val);
	}

	private function getDbConfVar($wanted){ //@TODO FIX DB WRITING/READING
		$query = 'SELECT xonfig_val FROM app_conf WHERE xonfig_var="'.$wanted.'" ; ';
		$result = mysql_query($query, $this->dbc);
		$rows = mysql_fetch_assoc($result);
		return $rows;
	}

	private function setDbConfVar($var, $val){
		$query = 'SELECT * FROM `appconf` WHERE xonfig_var="'.$var.'" ; ';
        $result = mysql_query($query, $this->dbc);
        $rows = mysql_fetch_assoc($result);
		if (count($rows)>0) { // if exists, update it
			$query .= 'UPDATE app_conf SET xonfig_val=\''.$val.'\' WHERE xonfig_var="'.$var.'" ; ';
		} else { // if not insert a new row
			$query .= 'INSERT INTO app_conf (xonfig_var, xonfig_val) VALUES ( \''.$var.'\', \''.$val.'\' ) ; ' ;
		}
        mysql_query($query, $this->dbc);
	}

}