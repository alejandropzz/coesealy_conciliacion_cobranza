<?php
/**
 * Parent class to inherit DB models being able to query the database.
 */
class DatabaseEntity {
    private $connection;

    function __construct() {
        //$this->getServerDB();
        //if(!$year)
        $this->getLocalDBD();
        //else
            //$this->getLocalDBD_year($year);

    }

    function getLocalDBD_year($year){
        try
        {
            //$this->connection = new PDO('mysql:host=127.0.0.1:3306;dbname=coedb_conciliacion_cobranza;charset=utf8', 'root', 'wh1t3H0rS3sH4veC4nc3R');
            $this->connection = new PDO('mysql:host=127.0.0.1:3306;dbname=coedb_'.$year.';charset=utf8', 'root', '');
        }
        catch(Exception $e)
        {
            throw new Exception("An error ocurred when connecting to db" . $e->getMessage());
        }
    }
    function getLocalDBD(){
        try
        {
            //$this->connection = new PDO('mysql:host=127.0.0.1:3306;dbname=coedb_conciliacion_cobranza;charset=utf8', 'root', 'wh1t3H0rS3sH4veC4nc3R');
            $this->connection = new PDO('mysql:host=127.0.0.1:3306;dbname=coedb_conciliacion_cobranza;charset=utf8', 'root', '');
        }
        catch(Exception $e)
        {
            throw new Exception("An error ocurred when connecting to db" . $e->getMessage()); 
        }
    }
    function getLocalDB(){
        try{
            $this->connection = new PDO('mysql:host=localhost:3306;dbname=coedb_conciliacion_cobranza;charset=utf8', 'coeusr', 'wh1t3H0rS3sH4veC4nc3R');
        }catch(Exception $e){
            throw new Exception("An error ocurred when connecting to db" . $e->getMessage()); 
        }
    }
     function getServerDB(){
         try {
            $this->connection = new PDO('mysql:host=50.63.25.109:3306;dbname=coedb_conciliacion_cobranza    ;charset=utf8', 'coeusr', 'wh1t3H0rS3sH4veC4nc3R');
        } catch (Exception $e) {
            throw new Exception("An error ocurred when connecting to db" . $e->getMessage());
        }
    }


    function getConnection() {
        return $this->connection;
    }

    function setConnection($connection) {
        $this->connection = $connection;
    }
}
