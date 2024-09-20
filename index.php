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
    <button
        name = "attendanceButton">
        Attendance
    </button>
    <button 
        name="resetButton">
        Reset
    </button>
</form>

<pre>
<?php

function writetolog(string $fileName, string $content, bool $isLate){

    if (!$isLate){
        file_put_contents($fileName, $content . "\n", FILE_APPEND);

    } else {
        file_put_contents($fileName, $content . ", student is late.\n", FILE_APPEND);
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
$file = "TimeLog.txt";

echo "<hr>" . "Hello :)" . "\n";
echo "Today is " . $displayDate . "<hr>";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attendanceButton'])){

    $hour = date("H");

    if ($hour >= 8 && $hour < 20){
        $isLate = true;
    } else {
        $isLate = false;
    }

    if ($hour >= 20){

        die("Attendance at this time is not possible :| ");

    } else {
        writetolog($file, $fileDate, $isLate);
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetButton'])){
    resetlog($file);
}

$fileContent = getlog($file);
echo $fileContent; 

?>
</pre>

</body>
</html>