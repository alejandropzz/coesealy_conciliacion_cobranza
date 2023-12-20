<?php
class Entity extends JSONObject{
    public $company;
    public $entityPrefix;

    function getCompany(){
        return $this->company;
    }

    function getEntityPrefix(){
        return $this->entityPrefix;
    }

    function setCompany($var){
        $this->company = $var;
    }

    function setEntityPrefix($var){
        $this->entityPrefix = $var;
    }
}
?>