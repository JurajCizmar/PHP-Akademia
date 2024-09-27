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

function write_to_log(string $fileName, string $nameOfStudent, string $content){

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

function reset_log(string $fileName){

    file_put_contents($fileName, "");
}

require_once "Classes/Student.php";
require_once "Classes/Arrivals.php";

$fileDate = date("l d.m.Y H:i:s");
$timeLogFile = "TimeLog.txt";
$studentsJsonFile = "students.json";
$errors = false;
$arrivals = new Arrivals("arrivals.json");

echo "<hr>" . "Hello :)" . "\n";
echo "Today is " . date('l jS \of F Y H:i:s A') . "<hr>";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST["submit"]) && !empty($_POST["name"])) {

        $nameOfStudent = htmlspecialchars($_POST["name"]);        
        write_to_log($timeLogFile, $nameOfStudent, $fileDate);

        $arrivals->write_arrival_to_json($fileDate);

        Student::write_student_to_json($nameOfStudent, $studentsJsonFile);

    } elseif (isset($_POST["submit"]) && empty($_POST["name"])){
        echo "Fill in the student's name please.";
        echo "<br>";
        $errors = true;

    } elseif (isset($_POST["resetButton"])){
        reset_log($timeLogFile);
        reset_log($studentsJsonFile);
        reset_log($arrivals->getJsonfileName());
    }
} 

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["name"])){

    $nameOfStudent = htmlspecialchars($_GET["name"]);
    write_to_log($timeLogFile, $nameOfStudent, $fileDate);

    $arrivals->write_arrival_to_json($fileDate);

    Student::write_student_to_json($nameOfStudent, $studentsJsonFile);
}

if (file_exists("students.json") && !$errors){

    $json_data = file_get_contents("students.json");
    $decoded_data = json_decode($json_data, true);
    print_r($decoded_data);
}

?>
</pre>

</body>
</html>