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

function writetolog(string $fileName, string $studentsName, string $content){

    $hour = date("H");
    $isLate = ($hour >= 8 && $hour < 20) ? true : false ;

    if ($hour >= 22){
        die("Attendance at this time is not possible :| ");

    } else {
        $content .= $isLate? ", " . $studentsName . " is late." : " " . $studentsName;
        $content .= "\n";

        file_put_contents($fileName, $content, FILE_APPEND);
    }
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
$errors = false;

echo "<hr>" . "Hello :)" . "\n";
echo "Today is " . $displayDate . "<hr>";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST["submit"]) && !empty($_POST["name"])) {
        
        $studentsName = htmlspecialchars($_POST["name"]);        
        writetolog($timeLogFile, $studentsName, $fileDate);



        if (file_exists($studentsJsonFile) && !filesize($studentsJsonFile) == 0){

            $json_data = file_get_contents($studentsJsonFile);
            $decoded_json_data = json_decode($json_data, true);

            $student[$studentsName] = 0;
            $mergedArray = array_merge($decoded_json_data, $student);

            $encoded_data = json_encode($mergedArray, JSON_PRETTY_PRINT);
            file_put_contents($studentsJsonFile, $encoded_data);
        
        } else {
            $student[$studentsName] = 0;
            $encoded_data = json_encode($student, JSON_PRETTY_PRINT);
            file_put_contents($studentsJsonFile, $encoded_data);
        }

    } elseif (isset($_POST["submit"]) && empty($_POST["name"])){
        echo "Fill in the student's name please.";
        echo "<br>";
        $errors = true;

    } elseif (isset($_POST["resetButton"])){
        resetlog($timeLogFile);
        resetlog($studentsJsonFile);
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["name"])){

    $studentsName = htmlspecialchars($_GET["name"]);
    writetolog($timeLogFile, $studentsName, $fileDate);











}

if (file_exists("students.json") && !$errors){
    // $fileContent = getlog($timeLogFile);
    // echo $fileContent;
    $json_data = file_get_contents("students.json");
    $decoded_data = json_decode($json_data, true, 512, JSON_OBJECT_AS_ARRAY);
    print_r($decoded_data);
}

?>
</pre>

</body>
</html>