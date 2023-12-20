<?php

class Logger{
    //Function to write to a log file the content of a transaction
    function logEntry($function,$message,$period){
        if(!isset($period))
            $period = "";

        $currentPeriod = $period;
        
        $fileName = __DIR__."../../../logs/system_functions";

        //Check that period doesn't contain weird characters
        if(preg_match('/|/',$currentPeriod) === 1)
            $currentPeriod = str_replace("|","_",$currentPeriod);
        
        $fileName = $fileName."/".$_SESSION["name"]."_".$function."_".$currentPeriod.".txt";
        
        //Get date
        $msgdate = date("d-m-Y G:i:s");

        //Format: status|debrief|content|detail|date
        $message = $_SESSION["name"]."|".$message."|".$msgdate."|".$period.PHP_EOL;

        //Log in file
        file_put_contents($fileName,$message,FILE_APPEND);
    }

    function logAccess($message){
        $fileName = __DIR__."../../../logs/user_access";
        $fileName = $fileName."/user_access.txt";

        $msgdate = date("d-m-Y G:i:s");

        $message = $message."|".$msgdate.PHP_EOL;

        file_put_contents($fileName,$message,FILE_APPEND);
    }

}