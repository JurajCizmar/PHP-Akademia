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

function writetolog(string $fileName, string $studentsName, string $content, bool $isLate){



    $content .= $isLate? ", " . $studentsName . " is late." : " " . $studentsName;
    $content .= "\n";
    
    file_put_contents($fileName, $content, FILE_APPEND);
}

function getlog(string $fileName){

    return file_get_contents($fileName);
}

function resetlog(string $fileName){

    file_put_contents($fileName, "");
}

$fileDate = date("l d.m.Y H:i:s");
$displayDate = date('l jS \of F Y H:i:s A');
$fileName = "TimeLog.txt";
$errors = false;

echo "<hr>" . "Hello :)" . "\n";
echo "Today is " . $displayDate . "<hr>";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST["submit"]) && !empty($_POST["name"])) {
        
        $studentsName = htmlspecialchars($_POST["name"]);
        $hour = date("H");
        $isLate = ($hour >= 8 && $hour < 20) ? true : false ;

        if ($hour >= 20){
            die("Attendance at this time is not possible :| ");

        } else {
            writetolog($fileName, $studentsName, $fileDate, $isLate);
        }

    } elseif (isset($_POST["submit"]) && empty($_POST["name"])){
        echo "Fill in the student's name please.";
        echo "<br>";
        $errors = true;

    } elseif (isset($_POST["resetButton"])){
        resetlog($fileName);
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["name"])){

    $studentsName = htmlspecialchars($_GET["name"]);
    
    $hour = date("H");
    $isLate = ($hour >= 8 && $hour < 20) ? true : false ;

    if ($hour >= 20){
        die("Attendance at this time is not possible :| ");

    } else {
        writetolog($fileName, $studentsName, $fileDate, $isLate);
    }
    
}

if (file_exists($fileName) && !$errors){
    $fileContent = getlog($fileName);
    echo $fileContent;
}

?>
</pre>

</body>
</html>