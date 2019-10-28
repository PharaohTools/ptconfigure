<?php

error_reporting(0) ;

$tail = new PHPTail($argv[1]);

echo $tail->getNewLines($_GET['lastsize'], $_GET['grep'], $_GET['invert']);


class PHPTail
{
    /**
     * Location of the log file we're tailing
     * @var string
     */
    private $log = "";
    /**
     * The time between AJAX requests to the server.
     *
     * Setting this value too high with an extremly fast-filling log will cause your PHP application to hang.
     * @var integer
     */
    private $updateTime;
    /**
     * This variable holds the maximum amount of bytes this application can load into memory (in bytes).
     * @var string
     */
    private $maxSizeToLoad;

    /**
     *
     * PHPTail constructor
     * @param string $log the location of the log file
     * @param integer $defaultUpdateTime The time between AJAX requests to the server.
     * @param integer $maxSizeToLoad This variable holds the maximum amount of bytes this application can load into memory (in bytes). Default is 2 Megabyte = 2097152 byte
     */
    public function __construct($log, $defaultUpdateTime = 2000, $maxSizeToLoad = 134217728)
    {
        $this->log = $log;
        $this->updateTime = $defaultUpdateTime;
        $this->maxSizeToLoad = $maxSizeToLoad;
    }

    /**
     * This function is in charge of retrieving the latest lines from the log file
     * @param string $lastFetchedSize The size of the file when we lasted tailed it.
     * @param string $grepKeyword The grep keyword. This will only return rows that contain this word
     * @return string Returns the JSON representation of the latest file size and appended lines.
     */
    public function getNewLines($lastFetchedSize, $grepKeyword, $invert)
    {
        /**
         * Clear the stat cache to get the latest results
         */
        clearstatcache();
        /**
         * Define how much we should load from the log file
         * @var
         */
        $fsize = filesize($this->log);
        $maxLength = ($fsize - $lastFetchedSize);
        /**
         * Verify that we don't load more data then allowed.
         */
        $loaded_size = round(($maxLength / 1048576), 2) ;
        if ($maxLength > $this->maxSizeToLoad) {
            $msg = "ERROR: PHPTail attempted to load more (" . $loaded_size . "MB) then " .
                "the maximum size (" . $loaded_size . "MB) of bytes into memory. You should ".
                "lower the defaultUpdateTime to prevent this from happening. " ;
            return json_encode(array("size" => $fsize, "data" => array($msg)));
        }
        /**
         * Actually load the data
         */
        $data = array();
        if ($maxLength > 0) {
            $fp = fopen($this->log, 'r') ;
            fseek($fp, -$maxLength, SEEK_END) ;
            $data = explode("\n", fread($fp, $maxLength))  ;
        }
        /**
         * Run the grep function to return only the lines we're interested in.
         */
        if ($invert == 0) {
            $data = preg_grep("/$grepKeyword/", $data);
        } else {
            $data = preg_grep("/$grepKeyword/", $data, PREG_GREP_INVERT);
        }
        /**
         * If the last entry in the array is an empty string lets remove it.
         */
        if (end($data) == "") {
            array_pop($data);
        }

        $data = array_slice($data, -100, 100); ;
        $data_string = implode("\n", $data) ;

        return $data_string ;
    }
}