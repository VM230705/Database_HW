<?php
$conn = require_once "../db_account/config.php";
$shopname = $_REQUEST['shopname'];
$status = $_REQUEST['status'];
$count = $_REQUEST['count'];

# shop order order details
$shop_order = [];
$query = "SELECT OID, status, start, end, shopname, mealname, price, quantity, 
distance  FROM i_order where shopname = '$shopname' and status = '$status'";

if($status=="ALL"){
    $query = "SELECT OID, status, start, end, shopname, mealname, price, quantity, 
    distance  FROM i_order where shopname = '$shopname'";
}
$inner_table = $conn->query($query);
if($inner_table->rowCount()!=0){  
    $shop_order = [];
    foreach($inner_table as $row){
        array_push($shop_order, $row);
    }
}
$subtotal = 0;
$iter = 0;
$m_num = 0;
foreach($shop_order as $s_order){
    $m_num++;
    $OID = $s_order['OID'];
    $mealname = $s_order['mealname'];
    $quantity = $s_order['quantity'];
    $price = $s_order['price'];
    if($iter!=0 and $lastID!=$OID){
        $subtotal = 0;
        $m_num=0;
    }
    $lastID = $OID;

    $s_order_stmt = $conn->query("SELECT picture from meal where shopname = '$shopname' and mealname = '$mealname'");

    // if(is_null($mealname)){
    //     $mealname = "(Deleted Meal)";
    // }
    // print_r($s_order_stmt);
    $picture=null;
    if($s_order_stmt->rowCount() != 0){
        
        foreach($s_order_stmt as $meal){
            $picture = $meal['picture'];
        }
    }
    if($subtotal == 0){
        echo "<script class='script'>
        let table".$OID."count".$count." = document.createElement('table');
        let thead".$OID."count".$count." = document.createElement('thead');
        let tbody".$OID."count".$count." = document.createElement('tbody');
        table".$OID."count".$count.".appendChild(thead".$OID."count".$count.");
        table".$OID."count".$count.".appendChild(tbody".$OID."count".$count.");
        // Adding the entire table to the body tag
        document.getElementById('".$OID."-modal').appendChild(table".$OID."count".$count.");
        let row_1".$OID."count".$count." = document.createElement('tr');
        let heading_1".$OID."count".$count." = document.createElement('th');
        heading_1".$OID."count".$count.".innerHTML = 'Picture';
        heading_1".$OID."count".$count.".setAttribute(\"style\", \"width: 25%;\")
        let heading_2".$OID."count".$count." = document.createElement('th');
        heading_2".$OID."count".$count.".innerHTML = 'Meal Name';
        heading_2".$OID."count".$count.".setAttribute(\"style\", \"width: 30%;\")
        let heading_3".$OID."count".$count." = document.createElement('th');
        heading_3".$OID."count".$count.".innerHTML = 'Price';
        heading_3".$OID."count".$count.".setAttribute(\"style\", \"width: 25%;\")
        let heading_4".$OID."count".$count."= document.createElement('th');
        heading_4".$OID."count".$count.".innerHTML = 'Quantity';
        heading_4".$OID."count".$count.".setAttribute(\"style\", \"width: 25%;\")
        
        row_1".$OID."count".$count.".appendChild(heading_1".$OID."count".$count.");
        row_1".$OID."count".$count.".appendChild(heading_2".$OID."count".$count.");
        row_1".$OID."count".$count.".appendChild(heading_3".$OID."count".$count.");
        row_1".$OID."count".$count.".appendChild(heading_4".$OID."count".$count.");
        thead".$OID."count".$count.".appendChild(row_1".$OID."count".$count.");
        </script>"
        ;
    }
    echo "<script class='script'>
    let row_2".$OID.$m_num."count".$count." = document.createElement('tr');
    let row_2_data_1".$OID.$m_num."count".$count." = document.createElement('td');
    row_2_data_1".$OID.$m_num."count".$count.".innerHTML = '<img src=\"data:image/jpg;charset=utf8;base64,".base64_encode($picture)."\" style=\"width: 40%;\" with=\"50\" heigh=\"10\" alt=\"".$mealname."\">';
    let row_2_data_2".$OID.$m_num."count".$count." = document.createElement('td');
    row_2_data_2".$OID.$m_num."count".$count.".innerHTML = '".$mealname."';
    let row_2_data_3".$OID.$m_num."count".$count."= document.createElement('td');
    row_2_data_3".$OID.$m_num."count".$count.".innerHTML = '".$price."';
    let row_2_data_4".$OID.$m_num."count".$count." = document.createElement('td');
    row_2_data_4".$OID.$m_num."count".$count.".innerHTML = '".$quantity."';

    row_2".$OID.$m_num."count".$count.".appendChild(row_2_data_1".$OID.$m_num."count".$count.");
    row_2".$OID.$m_num."count".$count.".appendChild(row_2_data_2".$OID.$m_num."count".$count.");
    row_2".$OID.$m_num."count".$count.".appendChild(row_2_data_3".$OID.$m_num."count".$count.");
    row_2".$OID.$m_num."count".$count.".appendChild(row_2_data_4".$OID.$m_num."count".$count.");
    tbody".$OID."count".$count.".appendChild(row_2".$OID.$m_num."count".$count.");
    </script>"
    ;
    $subtotal += $s_order['price'] * $s_order['quantity'];
    $iter++;
}
?>