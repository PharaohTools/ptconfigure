<?php

/* Namespace EbayCodePractice ; */

/**
 * EBAY - CODE PRACTICE
 * 20/11/2012
 * ------
 * DAVID AMANSHIA
 */

class EbayCodePracticeConfigureController extends EbayCodePracticeBaseController {

    public function execute() {
        //@todo this class, after doing the model probablys
        $content = array();
        $uservar = array();
        for ($iuv=0;$iuv<count($userconfvar);$iuv++) {
            $uservar[$iuv] = $userconfvar[$iuv];
        }

        // update config object if the page is on a save function
        if ( isset($_REQUEST["run"]) && $_REQUEST["run"] == "1" ) {
            foreach ($uservar as $uvar) $this->configs->setUserVar( $uvar["idString"], $_REQUEST["conf_".$uvar["idString"]]) ;
        }

        // repopulate display array with values from object
        for ($iuv=0;$iuv<count($uservar);$iuv++) {
            $uservar[$iuv]["curValue"] = $this->configs->give($uservar[$iuv]["idString"]);
        }

        $content["uservar"] = $uservar;
        $this->view->executeView("configure", $content);
    }

}