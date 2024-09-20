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

function writetolog(string $fileName, string $content){

    file_put_contents($fileName, $content, FILE_APPEND);
}

function getlog(string $fileName){

    return file_get_contents($fileName);
}

function resetlog(string $fileName){

    file_put_contents($fileName, "");
}


$fileDate = date("d-m-Y H:i:s") . "\n";
$displayDate = date('l jS \of F Y H:i:s A');
$file = "TimeLog.txt";

echo "<hr>" . "Hello :)" . "\n";
echo "Today is " . $displayDate . "<hr>";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attendanceButton'])){
    writetolog($file, $fileDate, true);

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetButton'])){
    resetlog($file);
}

$fileContent = getlog($file);
echo $fileContent; 

$hour = date("H");



?>
</pre>

</body>
</html>