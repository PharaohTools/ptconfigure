<?php

Namespace Model;

class GitCheckout extends Base {

    public function checkoutProject($projectTarget){
        $output = $this->changeToProjectDirectory();
        $output .= $this->doGitCommand($projectTarget);
        return $output;
    }

    private function changeToProjectDirectory(){
        $command = 'cd /var/www/devprojects/';
        // @todo move this to a configurable
        return self::executeAndOutput($command);
    }

    private function doGitCommand($projectTarget){
        $command = 'git clone '.escapeshellarg($projectTarget);
        return self::executeAndOutput($command);
    }

}