<?php
try {
    if (!isset($_REQUEST['uname']) || empty($_REQUEST['uname']))
    {
        echo 'FAILED';
        exit();
    }

    $uname=$_REQUEST['uname'];
    $conn = require_once "../db_account/config.php";

    // query
    $stmt=$conn->prepare("select name from user where name=:username");
    $stmt->execute(array('username' => $uname));

    if ($stmt->rowCount() == 0){
        echo 'YES';
    }
    else {
        echo 'NO';
    }
}
catch(Exception $e){ 
    echo $e->getMessage();
}
?>