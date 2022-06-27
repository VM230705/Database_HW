<?php
function isValidLatitude($latitude){
    if (preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,14}$/", $latitude)) {
        return true;
    } else {
        return false;
    }
}

function isValidLongitude($longitude){
    if(preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,14}$/", $longitude)) {
        return true;
    } else {
        return false;
    }
}

    /** connect to database */
    $host='localhost';
    //$username='root';
    //$password='DBHW2';
    //$dbname='db_hw';
    $dbname='db_hw2';
    //$dbusername='root';
    $username='eric';
    //$dbpassword='DBHW2';
    $password='eric';

    $conn = mysqli_connect($host, $username, $password, $dbname);
    
    if($conn){
        /** set link encoding as utf8 */  
        mysqli_query($conn, "SET NAMES utf8");
        // echo "Connect correctly!<br/>";
    }
    else{
        die("Connection failed: " . mysqli_connect_error());
    }

    /** obtain the input value of register shop */   
    $shopname = $_POST["shopname"];
    $category = $_POST["category"];
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    $account = $_POST["account"];

    if(empty($_POST["shopname"]) || empty($_POST["category"]) || empty($_POST["latitude"]) || empty($_POST["longitude"])){
        die("All fields must be filled!!");
    }

    if(!isValidLatitude($latitude) || !isValidLongitude($longitude)){
        die("Wrong location format!! Both must be numeric data have at least one floating number!!");
    }

    
    /** check if shop name already exist or not */ 
    $sql =  "SELECT * FROM shop WHERE shopname = ?";
    $stmt = $conn->prepare($sql);   // avoid sql injection
    $stmt->bind_param("s", $shopname);  // 's' specifies the variable type => 'string'
    $stmt->execute();
    $result = $stmt->get_result();

    // while ($row = $result->fetch_assoc()) {
    //     print_r($row);
    // }
    if($result->num_rows != 0){
        die("The shop name already exists!!");
    }
    else{
        /**Success Register*/
        $location = "POINT(".$longitude." ".$latitude.")";
        $query = "INSERT INTO shop (shopname, location, category, account) VALUES (?, ST_GeomFromText(?), ?, ?)";
        $stmt2 = $conn->prepare($query);

        $stmt2->bind_param('ssss', $shopname, $location, $category, $account); // replace question marks with values
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $stmt2->close();
        echo "shop name has been registered!!";
    }

    mysqli_close($conn);
?>