<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

try{
    $recharge = $_REQUEST['rechargeValue'];
    $current_oid = 0;
    $current_tid = 0;
    $account = $_SESSION["account"];
    // $time = date('Y-m-d h:i:s', time());

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

    // Insert transaction record
    $sql = "
        INSERT INTO transaction (TID, OID, account, shopname, price, time, type)
        VALUES (:TID, :OID, :account, :shopname, :price, current_timestamp(), :type)";
    $stmt = $conn->prepare($sql);
    $datas = [':TID'=>$current_tid, ':OID'=>$current_oid, ':account'=>$account, ':shopname'=>$account, ':price'=>$recharge, ':type'=>'Recharge'];
    $stmt->execute($datas);
    // echo "$current_tid, $current_oid, $account, $shop_name, $total_price, $time"; // test

    // Update user wallet
    $new_balance = $_SESSION['balance'] + $recharge;
    $_SESSION['balance'] = $new_balance;
    $sql = "UPDATE user SET balance=:balance where account=:account";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account, ':balance'=>$new_balance];
    $stmt->execute($data);
    
    // Commit 
    $conn->commit();

    // Return
    echo "Success to recharge $recharge (current: $new_balance) !!";

}
catch (Exception $e){
    if ($conn->inTransaction())
        $conn->rollback();
    $msg = $e->getMessage();
    echo "$msg";
}

?>