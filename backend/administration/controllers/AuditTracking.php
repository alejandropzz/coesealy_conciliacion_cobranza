<?php
    class AuditTracking{
        private $route = __DIR__."../../../../logs/system_functions";

        function filterByPeriod($fileArray, $matchCriteria){
            //Filter returns only the elements in the array that the callback condition says
            //they are worth
            //Use sends an extra parameter to the callback function
            return array_filter($fileArray, function($current) use ($matchCriteria){
                return (preg_match($matchCriteria,$current) === 1);
            });
        }

        function getAuditTracks($period, $year){
            $auditTrackRecords = [];
            $response = new ResponseAuditTracks();

            //Get the list of all files in the logs directory
            $fileList = scandir($this->route);

            //Get all the files matching in period and year
            //Match the string period_year or the string year_period
            $match = '/'.'('.$period.'_'.$year.')'.'|'.'('.$year.'_'.$period.')'.'/';


            $filesToOpen = $this->filterByPeriod($fileList,$match);

            foreach ($filesToOpen as $file) {
                $current = $this->readFile($file);

                if(!$current){
                    $response->setMessage("Errore on getting the following audit file: ".$file);
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                }

                $auditTrackRecords = array_merge($auditTrackRecords,$current);
            }

            if(!$response->getStatus()){
                $response->setStatus(0);
                $response->setAuditTrackRecords($auditTrackRecords);
            }

            return $response;
        }

        //Returns an array of AuditRecord objects with the content of the files scanned
        function readFile($fname){
            $CFDI_FILE   = FALSE;
            $auditRecords = [];

            //CFDIs have their period and year different
            if(preg_match('/cfdi/',$fname))
                !$CFDI_FILE;

            $fileContent = file($this->route.'/'.$fname);

            //Abort on error
            if(!$fileContent)
                return FALSE;
            
            foreach ($fileContent as $line) {
                $line = trim($line);
                
                $auditRecord = new AuditRecord();
                $data = explode("|",$line);
                
                $auditRecord->setUser($data[0]);
                $auditRecord->setStatus($data[1]);
                $auditRecord->setAction($data[2]);
                $auditRecord->setTransactionData($data[3]);
                $auditRecord->setDetail($data[4]);
                $auditRecord->setDate($data[5]);

                if(array_key_exists(6,$data) && array_key_exists(7,$data)){
                    if($CFDI_FILE){
                        $auditRecord->setPeriod($data[7]);
                        $auditRecord->setYear($data[6]);
                    }else{
                        $auditRecord->setPeriod($data[6]);
                        $auditRecord->setYear($data[7]);
                    }   
                }

                array_push($auditRecords,$auditRecord);                
            }
            
            return $auditRecords;
        }
    }

    class ResponseAuditTracks extends ResponseStatus{
        public $auditTrackRecords;

        function getAuditTrackRecords(){
            return $this->auditTrackRecords;
        }

        function setAuditTrackRecords($audit){
            $this->auditTrackRecords = $audit;
        }
    }
?>