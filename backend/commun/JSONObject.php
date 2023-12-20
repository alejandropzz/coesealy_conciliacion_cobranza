<?php

class JSONObject {
   

    public function setObjectFromJSON($jsonData) {
        
        $data=json_decode($jsonData, true);
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                $sub = new JSONObject;
                $sub->set($value);
                $value = $sub;
            }
            $this->{$key} = $value;
        }
    }
}