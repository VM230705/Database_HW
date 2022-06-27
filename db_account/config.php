<?php
    $dbservername='localhost';
    $dbname='db_hw2';
    //$dbusername='root';
    $dbusername='eric';
    //$dbpassword='DBHW2';
    $dbpassword='eric';

    /* Attempt to make connection to database */
    try {
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", 
            $dbusername, $dbpassword);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // return PDO object
        return $conn;
    }
    catch (Exception $e){
        return $e->getMessage();
    }
?>