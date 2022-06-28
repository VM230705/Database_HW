<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

try{
    $ordered = $_REQUEST['activitiesArray'];
    $shop_name = $_REQUEST['shopName'];
    $delivery_fee = $_REQUEST['deliveryFee'];
    $delivery_type = $_REQUEST['deliveryType'];
    $current_oid = 0;
    $current_tid = 0;
    $account = $_SESSION["account"];
    $time = date('Y-m-d h:i:s', time());

    $conn = require_once "../db_account/config.php";

    // Start Transaction
    $conn->beginTransaction();

    // Find highest OID in trans
    $sql = "SELECT MAX(OID) as max_oid FROM transaction";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    $current_oid = $row['max_oid'] + 1;

    // Find highest TID in trans
    $sql = "SELECT MAX(TID) as max_tid FROM transaction";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    $current_tid = $row['max_tid'] + 1;

    // Find highest ID in i_order
    $sql = "SELECT MAX(ID) as max_id FROM i_order";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $row = $stmt->fetch();
    $current_id = $row['max_id'] + 1;

    // Insert transaction record
    $total_price = 0;
    foreach ($ordered as $meal) {
        $total_price += $meal[1] * $meal[2];
    }
    $total_price += $delivery_fee;
    $sql = "
        INSERT INTO transaction (TID, OID, account, shopname, price, time, type)
        VALUES (:TID, :OID, :account, :shopname, :price, :time, :type)";
    $stmt = $conn->prepare($sql);
    $datas = [':TID'=>$current_tid, ':OID'=>$current_oid, ':account'=>$account, ':shopname'=>$shop_name, ':price'=>$total_price, ':time'=>$time, ':type'=>'Payment'];
    $stmt->execute($datas);
    // echo "$current_tid, $current_oid, $account, $shop_name, $total_price, $time"; // test

    // /* i_order insert */
    // Get user logitude and latitude
    $sql = "SELECT ST_X(location) as longitude, ST_Y(location) as latitude FROM user WHERE account = :account";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account];
    $stmt->execute($data);
    $row = $stmt->fetch();
    $latitude = $row['latitude'];
    $longitude = $row['longitude']; 

    // Get the distance
    $stmt = $conn->prepare("select *, ST_Distance_Sphere(POINT(:longitude,:latitude),location) as distance from shop where shopname = :shopname");
    $stmt->execute(array('longitude'=>$longitude, 'latitude'=>$latitude, 'shopname'=>$shop_name));
    $row = $stmt->fetch();
    $distance = $row['distance'];

    // pick up distance == 0
    if ($delivery_type == 'pick-up'){
        $distance = 0;
    }

    // Insert i_order with increment id
    foreach ($ordered as $meal) {
        $now_id = $current_id;
        $name = $meal[0];
        $price = $meal[1];
        $quantity = $meal[2];
        $subtotal = $meal[1] * $meal[2];
        $start = $time;
        $end = NULL;
        $status = 'Not Finished';
        // Insert
        $sql = "
            INSERT INTO i_order (id, OID, account, shopname, mealname, price, quantity, subtotal, status, start, end, distance)
            VALUES (:id, :OID, :account, :shopname, :mealname, :price, :quantity, :subtotal, :status, :start, :end, :distance)";
        $stmt = $conn->prepare($sql);
        $datas = [':id'=>$now_id, ':OID'=>$current_oid, ':account'=>$account, ':shopname'=>$shop_name,
            ':mealname'=>$name, ':price'=>$price, ':quantity'=>$quantity, ':subtotal'=>$subtotal, 
            ':status'=>$status, ':start'=>$start, ':end'=>$end, ':distance'=>$distance];
        $stmt->execute($datas);

        // Update numbers of items
        // get quantity
        $stmt = $conn->prepare("select quantity from meal where shopname = :shopname and mealname = :mealname");
        $stmt->execute(array('mealname'=>$name, 'shopname'=>$shop_name));
        $row = $stmt->fetch();
        $current_quantity = $row['quantity'];

        // update
        $new_quantity = $current_quantity - $quantity;
        $sql = "UPDATE meal SET quantity=:quantity where mealname = :mealname and shopname = :shopname";
        $stmt = $conn->prepare($sql);
        $data = [':quantity'=>$new_quantity, ':mealname'=>$name, ':shopname'=>$shop_name];
        $stmt->execute($data);

        // test
        // echo "\n$now_id, $current_oid, $account, $shop_name, $name, $price, $quantity, Subtotal: $subtotal, $status, $start, $end, $distance";
        $current_id += 1;
    }
    
    // // Insert Delivery Fee
    // $sql = "
    //     INSERT INTO i_order (id, OID, account, shopname, mealname, price, quantity, subtotal, status, start, end, distance)
    //     VALUES (:id, :OID, :account, :shopname, :mealname, :price, :quantity, :subtotal, :status, :start, :end, :distance)";
    // $stmt = $conn->prepare($sql);
    // $datas = [':id'=>$current_id, ':OID'=>$current_oid, ':account'=>$account, ':shopname'=>$shop_name,
    //     ':mealname'=>'delivery_fee', ':price'=>$delivery_fee, ':quantity'=>'1', ':subtotal'=>$delivery_fee, 
    //     ':status'=>'Not Finisted', ':start'=>$time, ':end'=>null, ':distance'=>$distance];
    // $stmt->execute($datas);

    // Update user wallet
    $new_balance = $_SESSION['balance'] - $total_price;
    $_SESSION['balance'] = $new_balance;
    $sql = "UPDATE user SET balance=:balance where account=:account";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account, ':balance'=>$new_balance];
    $stmt->execute($data);
    
    
    // Commit 
    $conn->commit();

    // Return
    echo "Success !!";

}
catch (Exception $e){
    if ($conn->inTransaction())
        $conn->rollback();
    $msg = $e->getMessage();
    echo "$msg";
}
?>