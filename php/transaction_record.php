<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

try{
    $act = $_REQUEST['action'];
    $show_act = $act;
    $account = $_SESSION["account"];
    $return = "";
    $test = 'test';
    $conn = require_once "../db_account/config.php";

    // Search & Increment append to the return result
    if ($act == 'all'){
        $sql = "SELECT * FROM transaction WHERE account = :account";
    }
    else if ($act == 'Payment'){
        $sql = "SELECT * FROM transaction WHERE account = :account and type = 'Payment'";
    }
    else if ($act == 'Recharge'){
        $sql = "SELECT * FROM transaction WHERE account = :account and type = 'Recharge'";
    }
    else if ($act == 'Collection'){
        $sql = "SELECT * FROM transaction WHERE account = :account and type = 'Collection'";
    }
    else if ($act == 'Refund'){
        $sql = "SELECT * FROM transaction WHERE account = :account and type = 'Refund'";
    }
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account];
    $stmt->execute($data);

    if ($stmt->rowCount() != 0) {
        $i = 1;
        foreach ($stmt as $row){
            $time = $row['time'];
            $type = $row['Type'];
            $trader = $row['shopname'];
            if ($type == 'Payment'){
                $amount = -$row['price'];
            }
            else{
                $amount = $row['price'];
            }
            $show_act = $type;
            $append = "<tr><th scope='row'>$i</th><td>$show_act</td><td>$time</td><td>$trader</td><td>$amount</td></tr>";
            $return = $return . $append;
            $i = $i + 1;
        }
    }

    
    echo "$return";
    // echo "<tr><th scope='row'>null</th><td>null</td><td>$act</td><td>null</td><td>null</td></tr>";
}
catch (Exception $e){
    if ($conn->inTransaction())
        $conn->rollback();
    $msg = $e->getMessage();
    echo "$msg";
}
?>