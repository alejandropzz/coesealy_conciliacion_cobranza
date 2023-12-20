<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author memogarrido
 */
class User {
    private $id;
    private $email;
    private $name;
    private $passwd;
    private $priv;
    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getName() {
        return $this->name;
    }

    function getPasswd() {
        return $this->passwd;
    }

    function getPriv() {
        return $this->priv;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setPasswd($passwd) {
        $this->passwd = $passwd;
    }

    function setPriv($priv) {
        $this->priv = $priv;
    }


}
