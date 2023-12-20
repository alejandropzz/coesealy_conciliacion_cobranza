<?php
    class AuditRecord extends JSONObject{
        public $user;
        public $status;
        public $action;
        public $transactionData;
        public $detail;
        public $date;
        public $period;
        public $year;

        function setUser($user){
            $this->user = $user;
        }
        
        function setStatus($status){
            $this->status = $status;
        }

        function setAction($action){
            $this->action = $action;
        }

        function setTransactionData($transactionData){
            $this->transactionData = $transactionData;
        }

        function setDetail($detail){
            $this->detail = $detail;
        }

        function setDate($date){
            $this->date = $date;
        }

        function setPeriod($period){
            $this->period = $period;
        }

        function setYear($year){
            $this->year = $year;
        }

        function getUser(){
            return $this->user;
        }
        
        function getStatus(){
            return $this->status;
        }

        function getAction(){
            return $this->action;
        }

        function getTransactionData(){
            return $this->transactionData;
        }

        function getDetail(){
            return $this->detail;
        }

        function getDate(){
            return $this->date;
        }

        function getPeriod(){
            return $this->period;
        }

        function getYear(){
            return $this->year;
        }
    }
?>