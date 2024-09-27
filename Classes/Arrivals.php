<?php

class Arrivals{

    private $jsonFileName;

    public function __construct($jsonFileName){
        $this->jsonFileName = $jsonFileName;
    }

    public function getJsonfileName(){
        return $this->jsonFileName;
    }

    public function write_arrival_to_json(string $date){

        if (file_exists($this->jsonFileName) && filesize($this->jsonFileName) != 0){
    
            $json_data = file_get_contents($this->jsonFileName);
            $arrivalsFromJson = json_decode($json_data, true);
            array_push($arrivalsFromJson, $date);

            $arrivalsFromJson = $this->check_late_arrival($arrivalsFromJson);

            $encoded_data = json_encode($arrivalsFromJson, JSON_PRETTY_PRINT);
        
        } else {
            $encoded_data = json_encode([$date], JSON_PRETTY_PRINT);
        }
        file_put_contents($this->jsonFileName, $encoded_data);
    }

    private function check_late_arrival($arrivalsFromJson){

        foreach ($arrivalsFromJson as $index => $arrival){
            
            if (strtotime($arrival)){
    
                $hour = date('H', strtotime($arrival));
                $isLate = ($hour >= 8 && $hour < 20) ? true : false ;
                $arrivalsFromJson[$index] = $isLate ? $arrival . ", meskanie": $arrival . "";
            }
        }
        return $arrivalsFromJson;
    }
}