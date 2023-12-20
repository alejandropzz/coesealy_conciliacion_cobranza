<?php


/**
 * Description of Company
 *
 * @author memogarrido
 */
class Company {
    public $id;
    public $name;
    public $taxid;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function getTaxid() {
        return $this->taxid;
    }

    function setTaxid($taxid) {
        $this->taxid = $taxid;
    }



}
