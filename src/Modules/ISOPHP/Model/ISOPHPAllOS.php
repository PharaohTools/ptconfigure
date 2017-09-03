<?php

Namespace Model;

class ISOPHPAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    public $params_to_load ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
    }

    public function askWhetherToCreateISOPHPApplication(){
        $res = $this->createISOPHPApplication();
        if ($res === false) {
            return false;
        }
        $this->loadParams() ;
        $res2 = $this->performCreateISOPHPApplication() ;
        return $res2 ;
    }

    protected function createISOPHPApplication(){
        if (isset($this->params["yes"]) && $this->params["yes"]==true) { return true ; }
        $question = 'Do you want to create an ISOPHP Application?' ;
        return self::askYesOrNo($question);
    }

    protected function loadParams(){

        $this->params_to_load = array(
            array('type' => 'string', 'slug' => 'email', 'question' => 'Enter an author email address (email)'),
            array('type' => 'string', 'slug' => 'web_link', 'question' => 'Enter a Website Address (web_link)'),
            array('type' => 'string', 'slug' => 'project_name', 'question' => 'Enter an Full Name for the project (project_name)'),
            array('type' => 'string', 'slug' => 'author_name', 'question' => 'Enter a Full Name for the author (author_name)'),
            array('type' => 'string', 'slug' => 'description', 'question' => 'Enter a Project Description (description)'),
            array('type' => 'string', 'slug' => 'domainid', 'question' => 'Cordova widget id eg: com.project.subdomain (domainid)'),
        );

        foreach ($this->params_to_load as $param_to_load) {
            if (isset($this->params[$param_to_load['slug']])) {
                continue ; }
            $this->params[$param_to_load['slug']] = self::askForInput($param_to_load['question']);
        }

    }

    protected function performCreateISOPHPApplication() {
        $tmp_dir = '/tmp/isophp_tmp/'.time() ;
        mkdir($tmp_dir, 0777, true) ;

        $isophp_git_home = "https://anon:any@source.internal.pharaohtools.com/git/public/isophp" ;
        $comm = "cd {$tmp_dir} && git clone {$isophp_git_home} {$this->params["slug"]}" ;

        self::executeAndOutput($comm);
        $prefix = $tmp_dir.DIRECTORY_SEPARATOR.$this->params["slug"].DIRECTORY_SEPARATOR ;
        $templates_array = array(
            array('source' => "Templates/Config/clients/desktop/package.json", 'target' => "clients/desktop/package.json"),
            array('source' => "Templates/Config/clients/desktop/composer.json", 'target' => "clients/desktop/composer.json"),
            array('source' => "Templates/Config/clients/web/package.json", 'target' => "clients/desktop/web/package.json"),
            array('source' => "Templates/Config/clients/web/composer.json", 'target' => "clients/web/composer.json"),
            array('source' => "Templates/Config/clients/mobile/package.json", 'target' => "clients/mobile/package.json"),
            array('source' => "Templates/Config/clients/mobile/composer.json", 'target' => "clients/mobile/composer.json"),
            array('source' => "Templates/Config/clients/mobile/config.xml", 'target' => "clients/mobile/config.xml"),
        );

        $templating_factory = new \Model\Templating();
        $templating = $templating_factory->getModel($this->params) ;
        $replacements = $this->getReplacements() ;

        foreach ($templates_array as $one_template) {
            $source = dirname(__DIR__).DIRECTORY_SEPARATOR.$one_template['source'] ;
            $target = $prefix.$one_template['target'] ;
//            echo "source: {$source} \n" ;
//            echo "target: {$target} \n\n" ;
            $templating->template($source, $replacements, $target, null, null, null) ;
        }
        return true ;
//        "cd < slug >" ;
//        "git clone < iso php fw git home> < slug >" ;

    }


    protected function getReplacements() {
        $replacements = array() ;
        foreach ($this->params_to_load as $param_to_load) {
            $replacements[$param_to_load['slug']] = $this->params[$param_to_load['slug']];
        }

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        $replacements['project_email'] = $this->params['email'] ;
        $replacements['project_website'] = $this->params['web_link'] ;
        $replacements['project_description'] = $this->params['description'] ;
        $replacements['project_name'] = $this->params['project_name'] ;
        $replacements['project_widget_id'] = $this->params['domainid'] ;
        $pnt = str_replace(' ', '', $replacements['project_name']) ;
        $pnt = str_replace('-', '', $pnt) ;
        $pnt = str_replace('_', '', $pnt) ;
        $replacements['project_name_trimmed'] = $pnt ;
        $psl = str_replace(' ', '', $replacements['project_name']) ;
        $psl = str_replace('-', '', $psl) ;
        $psl = str_replace('_', '', $psl) ;
        $psl = strtolower($psl) ;
        $replacements['project_slug'] = $psl ;

        $message = "Created Project Slug of {$replacements['project_slug']}" ;
        $logging->log($message, $this->getModuleName()) ;

        $replacements['author_name'] = $this->params['author_name'] ;
        $asl = str_replace(' ', '', $replacements['author_name']) ;
        $asl = str_replace('-', '', $asl) ;
        $asl = str_replace('_', '', $asl) ;
        $asl = strtolower($asl) ;
        $replacements['author_slug'] = $asl ;

        $message = "Created Author Slug of {$replacements['author_slug']}" ;
        $logging->log($message, $this->getModuleName()) ;

        if(isset($this->params['project_description_mobile'])) {
            $replacements['project_description_mobile'] = $this->params['project_description_mobile'] ;
        } else {
            $message = "Copying Default Project Description to Mobile Project Description" ;
            $logging->log($message, $this->getModuleName()) ;
            $replacements['project_description_mobile'] = $this->params['description'] ;
        }

        if(isset($this->params['project_description_desktop'])) {
            $replacements['project_description_desktop'] = $this->params['project_description_desktop'] ;
        } else {
            $message = "Copying Default Project Description to Desktop Project Description" ;
            $logging->log($message, $this->getModuleName()) ;
            $replacements['project_description_desktop'] = $this->params['description'] ;
        }

        if(isset($this->params['project_description_web'])) {
            $replacements['project_description_web'] = $this->params['project_description_web'] ;
        } else {
            $message = "Copying Default Project Description to Web Project Description" ;
            $logging->log($message, $this->getModuleName()) ;
            $replacements['project_description_web'] = $this->params['description'] ;
        }

        return $replacements ;
    }

}
