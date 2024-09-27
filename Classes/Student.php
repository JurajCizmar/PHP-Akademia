<?php
class Student{
    
    public static function write_student_to_json(string $nameOfStudent, string $jsonFileName){

        if (file_exists($jsonFileName) && filesize($jsonFileName) != 0){
    
            $json_data = file_get_contents($jsonFileName);
            $studentsFromJson = json_decode($json_data, true);
    
            $student[$nameOfStudent] = array_key_exists($nameOfStudent, $studentsFromJson) ? 
            ++$studentsFromJson[$nameOfStudent] : 1 ;
    
            $mergedArray = array_merge($studentsFromJson, $student);
            $encoded_data = json_encode($mergedArray, JSON_PRETTY_PRINT);
        
        } else {
            $student[$nameOfStudent] = 1;
            $encoded_data = json_encode($student, JSON_PRETTY_PRINT);
        }
        file_put_contents($jsonFileName, $encoded_data);
    }
}