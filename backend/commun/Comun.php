<?php

require __DIR__ . '/JSONObject.php';

class Comun extends DatabaseEntity {
    private $response;

    function __construct($year) {
        parent::__construct();
        if($year && $year!="false")
            parent::getLocalDBD_year($year);
    }

    function getYears() {
        $response = new ResponseComun();
        $sql =  "SELECT * FROM years";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            if ($stmt->execute()) {
                $lista = [];
                while ($row = $stmt->fetch())
                    array_push($lista, $row);
                if (sizeof($lista) > 0) {
                    $response->setStatus(true);
                    $response->setObject($lista,"years");
                } else {
                    $response->setStatus(false);
                    $response->setMessage('sin resultados');
                }
            } else {
                $response->setStatus(false);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(false);
            $response->setMessage($e->getMessage());
        }
        return $response;
    }


}

class ResponseComun {

    /**
     * Later on could mean type of error constant with different options for now is just -1 on error or 0 on success
     * @var int
     */
    public $status;
    /**
     * Errore message
     * @var string
     */
    public $message;


    function setStatus($status) {
        $this->status = $status;
    }
    function getStatus(){
        return $this->status;
    }
    function setMessage($message) {
        $this->message = $message;
    }
    function getMessage() {
        return $this->message;
    }

    public function setObject($data, $key) {
        $this->{$key} = $data;
    }

    public function setObjectFromJSON($jsonData) {
        $data=$jsonData;
        foreach ($data AS $key => $value) {
            /*
            if (is_array($value)) {
                $sub = new JSONObject;
                $sub->set($value);
                $value = $sub;
            }
            */
            $this->{$key} = $value;
        }
    }
    function getObject($key)
    {
        return true;
    }


}
