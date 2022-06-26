<?php
include "db_config.php";
if(isset($_POST['shopname'])){
    $shopname = mysqli_real_escape_string($conn, $_POST['shopname']);
    $query = "select count(*) as cntUser from shop where shopname ='".$shopname."'";
    $result = mysqli_query($conn, $query);
    $response = "<span style='color: green;'>Available.</span>";
    if(mysqli_num_rows($result)){
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        if($count > 0){
            $response = "<span style='color: red;'>Not Available.</span>";
        }    
    }
    echo $response;
    die;
}
?>