<?php
/**
 * Parent class that describe a general response from the service.
 */
class ResponseStatus {

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
    public $obj;
    public $index;


    function getStatus() {
        return $this->status;
    }
    function getIndex() {
        return $this->index;
    }

    function getMessage() {
        return $this->message;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    function setIndex($index) {
        $this->index = $index;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setObject($O){
        $this->obj=$O;
    }

    function getObject()
    {
        return $this->obj;
    }


}
