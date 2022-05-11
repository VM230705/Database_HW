<?php
$dbservername='localhost';
$dbname='db_hw';
$dbusername='root';
$dbpassword='DBHW2';

try {
    if (!isset($_REQUEST['uname']) || empty($_REQUEST['uname']))
    {
        echo 'FAILED';
        exit();
    }

    $uname=$_REQUEST['uname'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", 
        $dbusername, $dbpassword);

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // query
    $stmt=$conn->prepare("select name from user where name=:username");
    $stmt->execute(array('username' => $uname));

    if ($stmt->rowCount()==0){
        echo 'YES';
    }
    else {
        echo 'NO';
    }
}
catch(Exception $e)
{ 
    echo $e->getMessage();
}
?>