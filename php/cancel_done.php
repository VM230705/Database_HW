<?php require_once("db_config.php"); ?>
<?php
    session_start();

    if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
        echo 'FAILED ON AUTHENTICATION';
        exit();
    }

    if(!isset($_POST["OID"]) or !isset($_POST["ele_id"])){
        die("No OID or no element id");
    }
    if(!isset($_POST["start"])){
        die("No start value");
    }
    if(!isset($_POST["shopname"])){
        die("No shopname");
    }
    $OID = $_POST['OID'];
    $ele_id = $_POST["ele_id"];
    $start = $_POST["start"];
    $shopname = $_POST["shopname"];
    $account = $_SESSION["account"];


    // Cancel or Done 
    $total_price = 0;
    $time = date('Y-m-d h:i:s', time());
    $action = "";

    $order_meal = [];
    $orderquery = "SELECT price, quantity, mealname, subtotal, distance, account FROM i_order WHERE OID = $OID";
    $o_stmt = $conn->query($orderquery);
    if($o_stmt->num_rows>0){ 
        while($meal = $o_stmt->fetch_assoc()){
            array_push($order_meal, $meal);
        }
    }
    else{
        die("Selection number = 0");
    }


    if($ele_id == $OID."_cancel"){
        $action = "cancel";
        foreach($order_meal as $o_meal){
            $mealname = $o_meal['mealname'];
            $quantity = $o_meal['quantity'];
            $subtotal = $o_meal['subtotal'];
            $o_account = $o_meal['account'];
            $delivery_fee = intval($o_meal['distance'] / 100);
            $total_price += $subtotal;
        }
        if($delivery_fee < 10 && $delivery_fee!=0){
            $delivery_fee = 10;
        }
        $total_price += $delivery_fee;

        $sql =  "UPDATE i_order SET status = 'Cancel', start = '$start', end = '$time' WHERE OID = $OID";
    }
    else if($ele_id == $OID."_done"){
        // check meal exist and quantity enough
        // 後來用禁止修改或刪除餐點當有訂單包含該餐點未完成
        $action = "done";

        foreach($order_meal as $o_meal){
            $mealname = $o_meal['mealname'];
            $quantity = $o_meal['quantity'];
            $subtotal = $o_meal['subtotal'];
            $delivery_fee = intval($o_meal['distance'] / 100);
            $o_account = $o_meal['account'];
            $mealquery = "SELECT price, quantity, mealname FROM meal WHERE shopname = '$shopname' and mealname = '$mealname'";
            $m_stmt = $conn->query($mealquery);
            if($o_stmt->num_rows>0){ 
                // insert order時就扣過了
                // while($meal = $m_stmt->fetch_assoc()){
                //     if($quantity > $meal['quantity']){
                //         die("Quantity of ".$mealname." isn't enough.");
                //     }
                // }
            }
            else{
                die($mealname." is no longer exist.");
            }
            $total_price += $subtotal;
        }
        if($delivery_fee < 10 && $delivery_fee!=0){
            $delivery_fee = 10;
        }
        $total_price += $delivery_fee;
        
        $sql =  "UPDATE i_order SET status = 'Finished', start = '$start', end = '$time' WHERE OID = $OID";
    }
    else{
        die("Something Wrong\n");
    }
    
    // update order
    if ($conn->query($sql) === TRUE) {
        // echo "Order update successfully";
    } 
    else {
        die("Error: " . $sql . "<br>" . $conn->error);
    }


    // update transaction
    $t_query = "SELECT MAX(TID) as max_tid FROM transaction";
    $t_stmt = $conn->query($t_query);
    $row = $t_stmt->fetch_assoc();
    $current_tid = $row['max_tid'] + 1;
    $time = date('Y-m-d h:i:s', time());
    if($action == "done"){
        // 這裡的shopname 要存買家的user account
        $i_query = "INSERT INTO transaction (TID, OID, account, shopname, price, time, type)
            VALUES ($current_tid, $OID, '$account', '$o_account', $total_price, '$time', 'Collection')";
        if ($conn->query($i_query) === TRUE) {
            // echo "New record created successfully";
        } 
        else {
            die("Error: " . $i_query . "<br>" . $conn->error);
        }

        $u_query = "select balance from user where account = '$account';";
        $u_stmt = $conn->query($u_query);
        
        if($u_stmt->num_rows>0){ 
            $row = $u_stmt->fetch_assoc();
            $balance = $row['balance'];
            $newbalance = $balance + $total_price;
            $b_query = "UPDATE user SET balance = $newbalance where account = '$account'";
            if ($conn->query($b_query) === TRUE) {
                // echo "balance update successfully";
            } 
            else {
                die("Error: " . $b_query . "<br>" . $conn->error);
            }
            $_SESSION['balance'] = $newbalance;
        }
        
    }
    else if($action == "cancel"){
        $i_query = "INSERT INTO transaction (TID, OID, account, shopname, price, time, type)
            VALUES ($current_tid, $OID, '$o_account', '$shopname', $total_price, '$time', 'Refund')";
        if ($conn->query($i_query) === TRUE) {
            // echo "New record created successfully";
        } 
        else {
            die("Error: " . $i_query . "<br>" . $conn->error);
        }

        $u_query = "select balance from user where account = '$o_account';";
        $u_stmt = $conn->query($u_query);

        if($u_stmt->num_rows>0){ 
            $row = $u_stmt->fetch_assoc();
            $balance = $row['balance'];
            $newbalance = $balance + $total_price;
            $b_query = "UPDATE user SET balance = $newbalance where account = '$o_account'";
            if ($conn->query($b_query) === TRUE) {
                // echo "balance update successfully";
            } 
            else {
                die("Error: " . $b_query . "<br>" . $conn->error);
            }
        }
    }
    echo "Successfully Update! Do you want to reload the pages?";
?>