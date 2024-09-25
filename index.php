<?php
declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
    <label 
        for="name">
        Student's name:
    </label><br>
    <input 
        type="text" 
        id="name" 
        name="name">
    <br>
    <input 
        type="submit" 
        name="submit" 
        value="Submit">
    <input 
        type="submit" 
        name="resetButton" 
        value="Reset">
</form>

<pre>
<?php

function writetolog(string $fileName, string $nameOfStudent, string $content){

    $hour = date("H");
    $isLate = ($hour >= 8 && $hour < 20) ? true : false ;

    if ($hour >= 22){
        die("Attendance at this time is not possible :| ");

    } else {
        $content .= $isLate? ", " . $nameOfStudent . " is late." : " " . $nameOfStudent;
        $content .= "\n";

        file_put_contents($fileName, $content, FILE_APPEND);
    }
}

function write_student_to_json(string $nameOfStudent, string $jsonFileName){

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

function write_arrival_to_json(string $date, string $jsonFileName){

    if (file_exists($jsonFileName) && filesize($jsonFileName) != 0){

        $json_data = file_get_contents($jsonFileName);
        $arrivalsFromJson = json_decode($json_data, true);

        array_push($arrivalsFromJson, $date);
        $encoded_data = json_encode($arrivalsFromJson, JSON_PRETTY_PRINT);
    
    } else {
        $encoded_data = json_encode([$date], JSON_PRETTY_PRINT);
    }
    file_put_contents($jsonFileName, $encoded_data);

}

function getlog(string $fileName){

    return file_get_contents($fileName);
}

function resetlog(string $fileName){

    file_put_contents($fileName, "");
}

$fileDate = date("l d.m.Y H:i:s");
$displayDate = date('l jS \of F Y H:i:s A');
$timeLogFile = "TimeLog.txt";
$studentsJsonFile = "students.json";
$arrivalsJsonFile = "arrivals.json"; 
$errors = false;

echo "<hr>" . "Hello :)" . "\n";
echo "Today is " . $displayDate . "<hr>";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST["submit"]) && !empty($_POST["name"])) {

        $nameOfStudent = htmlspecialchars($_POST["name"]);        
        writetolog($timeLogFile, $nameOfStudent, $fileDate);

        write_arrival_to_json($fileDate, $arrivalsJsonFile);

        write_student_to_json($nameOfStudent, $studentsJsonFile);

    } elseif (isset($_POST["submit"]) && empty($_POST["name"])){
        echo "Fill in the student's name please.";
        echo "<br>";
        $errors = true;

    } elseif (isset($_POST["resetButton"])){
        resetlog($timeLogFile);
        resetlog($studentsJsonFile);
        resetlog($arrivalsJsonFile);
    }

} 

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["name"])){

    $nameOfStudent = htmlspecialchars($_GET["name"]);
    writetolog($timeLogFile, $nameOfStudent, $fileDate);

    write_arrival_to_json($arrivalsJsonFile, $fileDate);

    write_student_to_json($nameOfStudent, $studentsJsonFile);
}


if (file_exists($arrivalsJsonFile) && filesize($arrivalsJsonFile) != 0){

    $json_data = file_get_contents($arrivalsJsonFile);
    $arrivalsFromJson = json_decode($json_data, true);
    //          $fruits as $index => $fruit
    foreach ($arrivalsFromJson as $index => $arrival){
        
        if (strtotime($arrival)){

            $hour = date('H', strtotime($arrival));
            $isLate = ($hour >= 8 && $hour < 20) ? true : false ;
            $arrivalsFromJson[$index] = $isLate ? $arrival . ", meskanie": $arrival . "";
        }
    }
    $encoded_data = json_encode($arrivalsFromJson, JSON_PRETTY_PRINT);
    file_put_contents($arrivalsJsonFile, $encoded_data);
}

if (file_exists("students.json") && !$errors){
    // $fileContent = getlog($timeLogFile);
    // echo $fileContent;
    $json_data = file_get_contents("students.json");
    $decoded_data = json_decode($json_data, true);
    print_r($decoded_data);
}

?>
</pre>

</body>
</html>