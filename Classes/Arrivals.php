<?php

class Arrivals{

    private $jsonFileName;

    public function __construct($jsonFileName){
        $this->jsonFileName = $jsonFileName;
    }

    // REVIEW - Jedna taka vec, tu máš názov funkcie v camelCase, ale na iných miestach máš názvy funkcií v snake_case, pre funkcie sa používa hlavne camelCase. To len aby si vedel do buducna
    public function getJsonfileName(){
        return $this->jsonFileName;
    }

    /* REVIEW - Vidím že si použil "string" ako typ pre parameter, definovať typy môže byť fajn, ale aj nemusí. Celkom to záleží od okolností, ktoré zatiaľ nejdeme riešiť
    len odporúčam si zvyknúť buď na dávanie alebo nedávanie typov pre parametre, aby si bol konzistentný :D */
    public function write_arrival_to_json(string $date){

        if (file_exists($this->jsonFileName) && filesize($this->jsonFileName) != 0){
    
            /* REVIEW - nasledujúce 2 riadky máš taktiež v index.php na konci, bolo by fajn urobiť si na získavanie json decoded dát funkciu
            môžeš porozmýšlať či by bolo lepšie to robiť v tejto class-e, alebo v nejakej globalnej helper class-e (podobne to máš aj v Student.php) */
            $json_data = file_get_contents($this->jsonFileName);
            $arrivalsFromJson = json_decode($json_data, true);
            array_push($arrivalsFromJson, $date);

            /* REVIEW - Táto logika ti chýba pre prvé lognutie príchodu, teda ak lognem prvý príchod o 17:00 napr tak v prichody.json nie je "meškanie"
            asi by bolo dobré prispôsobiť check_late_arrival a tento if statement */
            $arrivalsFromJson = $this->check_late_arrival($arrivalsFromJson);

            // REVIEW - Taktiež tu sa ti opakuje 2 krát json_encode, dalo by sa to urobiť aj lepšie (podobne to máš aj v Student.php)
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