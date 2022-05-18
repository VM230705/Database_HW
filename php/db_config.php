<?php 
    // connect to database
    $host='localhost';
    $username='root';
    //$username='eric';
    $password='DBHW2';
    //$password='eric';
    $dbname='db_hw';

    $conn = mysqli_connect($host, $username, $password, $dbname);
    
    if($conn){
        //set link encoding as utf8
        mysqli_query($conn, "SET NAMES utf8");
        // echo "Connect correctly!<br/>";
    }
    else{
        die("Connection failed: " . mysqli_connect_error());
    }
?>


