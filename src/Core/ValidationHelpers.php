<?php

Namespace Core ;

class ValidationHelpers {

    public function notBlank($options=array() ) {
        $isItBlank = (strlen($options["fieldValue"])>0) ? array("true"=>"") : array("false"=>"This field cannot be blank. ") ;
        return $isItBlank;
    }

    public function isEmailAddress($options=array()) {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $isItAnEmail = (preg_match($regex, $options["fieldValue"])) ? array("true"=>"") : array("false"=>"This must be a valid email.") ;
        return $isItAnEmail ;
    }

    public function isUniqueEmail($options=array()) {
        $user = new \Model\UserData();
        $userExists = $user->checkUserExists($options["fieldValue"]);
        $isItUnique = ($userExists == false) ? array("true"=>"") : array("false"=>"This email address is already registered.") ;
        return $isItUnique ;
    }

    public function moreThan6Chars($options=array()) {
        $moreThan6 = (strlen($options["fieldValue"])>6) ? true : false ;
        return ($moreThan6) ? array("true"=>"") : array("false"=>"Password must be more than 6 Characters.") ;
    }

    public function lessThan10Chars($options=array()) {
        $lessThan8 = (strlen($options["fieldValue"])<10) ? true : false ;
        return ($lessThan8) ? array("true"=>"") : array("false"=>"Password must be less than 10 Characters.") ;
    }

    public function mustMatch($options=array() ) {
        $doesItMatch = ($options["fieldValue"] == $options["targetValue"])
            ? array("true"=>"") : array("false"=>"This field must match ".$options["targetFieldName"]) ;
        return $doesItMatch;
    }

    public function validUserLogin($options=array() ) {
        $user = new \Model\UserData();
        $validUser = ($user->checkPasswordCorrect($options["email"], $options["userPass"], "unhashed" ))
            ? array("true"=>"") : array("false"=>"The login details are incorrect") ;
        return $validUser;
    }
}