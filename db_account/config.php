<?php
    $dbservername='localhost';
    $dbname='db_hw';
    $dbusername='root';
    $dbpassword='DBHW2';

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