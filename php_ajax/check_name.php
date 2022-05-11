<?php
<<<<<<< HEAD
=======
$dbservername='localhost';
$dbname='db_hw';
$dbusername='root';
$dbpassword='DBHW2';

>>>>>>> 6ab4323ddccbe1cd77031afa325e6e8da93f6a88
try {
    if (!isset($_REQUEST['uname']) || empty($_REQUEST['uname']))
    {
        echo 'FAILED';
        exit();
    }

    $uname=$_REQUEST['uname'];
<<<<<<< HEAD
    $conn = require_once "../db_account/config.php";

=======
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", 
        $dbusername, $dbpassword);

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
>>>>>>> 6ab4323ddccbe1cd77031afa325e6e8da93f6a88
    // query
    $stmt=$conn->prepare("select name from user where name=:username");
    $stmt->execute(array('username' => $uname));

<<<<<<< HEAD
    if ($stmt->rowCount() == 0){
=======
    if ($stmt->rowCount()==0){
>>>>>>> 6ab4323ddccbe1cd77031afa325e6e8da93f6a88
        echo 'YES';
    }
    else {
        echo 'NO';
    }
}
<<<<<<< HEAD
catch(Exception $e){ 
=======
catch(Exception $e)
{ 
>>>>>>> 6ab4323ddccbe1cd77031afa325e6e8da93f6a88
    echo $e->getMessage();
}
?>