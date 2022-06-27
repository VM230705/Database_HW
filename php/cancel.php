<?php
    session_start();

    if (!isset($_SESSION['logged']) || $_SESSION['logged']!=true) {
        echo "
        <script>
          alert('Please loggin');
          window.location.href = '../index.php';
        </script>
        ";
        exit();
    }



    $conn = require "../db_account/config.php";
        
        
    $OID = $_POST['cancel'];

    $sql = "select account,shopname,subtotal,distance,status from i_order where OID =:OID";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array('OID'=>$OID));
    $flag = False;
    $sum = 0;
    foreach($stmt as $rows){
        $account = $rows['account'];
        $shopname = $rows['shopname'];
        $distance = $rows['distance'];
        $sum+=$rows['subtotal'];

        if($rows['status']!="Not Finished"){
            $flag = True;
            break;
        }
    }
    $delivery_f = intval($distance/100);
    if($delivery_f<10){
      $delivery_f=10;
      $total=$sum+10;
    }
    else{
      $total=$sum+$delivery_f;
    }

    if(!$flag){
            // update i_order
            $stmt = $conn->prepare("UPDATE i_order SET status='Canceled',end=current_timestamp() where OID =:OID");
            $stmt->execute(array('OID'=>$OID));

            //update meal number
            $sql = "select mealname, shopname, quantity from i_order where OID =:OID";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('OID'=>$OID));
            foreach($stmt as $rows){
                $mealname = $rows['mealname'];
                $quantity = $rows['quantity'];
                $sql = "select quantity from meal where shopname =:shopname and mealname =:mealname";
                $stmt1 = $conn->prepare($sql);
                $stmt1->execute(array('shopname'=>$shopname,'mealname'=>$mealname));
                foreach($stmt1 as $temp1){
                    $newquantity = $temp1['quantity']+$quantity;
                    $sql = "UPDATE meal SET quantity=$newquantity where shopname=:shopname and mealname=:mealname";
                    $stmt2 = $conn->prepare($sql);
                    $stmt2->execute(array('shopname'=>$shopname,'mealname'=>$mealname));
                }
            }

            //refund user money
            $sql = "select balance from user where account =:account;";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array("account"=>$account));
            
            foreach($stmt as $rows){
                $balance=$rows['balance'];
                $newbalance = $balance+$total;
                $sql = 'UPDATE user SET balance =:balance where account =:account';
                $stmt1 = $conn->prepare($sql);
                $stmt1->execute(array('balance'=>$newbalance,'account'=>$account));
            }

            //take money away from shopkeeper
            $sql = "SELECT account from shop where shopname =:shopname";
            $stmt=$conn->prepare($sql);
            $stmt->execute(array('shopname'=>$shopname));
            foreach($stmt as $rows){
                $shop_account = $rows['account'];
            }
            $sql = "select balance from user where account =:account";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('account'=>$shop_account));
            foreach($stmt as $rows){
                $balance=$rows['balance'];
                $newbalance = $balance-$total;
                $sql = "UPDATE user SET balance=$newbalance where account =:account";
                $stmt1 = $conn->prepare($sql);
                $stmt1->execute(array('account'=>$shop_account));
            }

            
            // generate user's record
            $sql = "
            INSERT INTO transaction (OID, account, shopname, price, time, Type)
            VALUES (:OID, :account, :shopname, :price, current_timestamp(), :type)";
            $stmt = $conn->prepare($sql);
            $datas = [':OID'=>$OID, 'account'=>$account, 'shopname'=>$shopname, 'price'=>$total, 'type'=>'Refund'];
            $stmt->execute($datas);

            // generate shopkeeper's record
            $sql = "SELECT account from shop where shopname =:shopname";
            $stmt=$conn->prepare($sql);
            $stmt->execute(array('shopname'=>$shopname));
            foreach($stmt as $rows){
                $shop_account = $rows['account'];
            }

            $sql = "
            INSERT INTO transaction (OID, account, shopname, price, time, Type)
            VALUES (:OID, :account, :shopname, :price, current_timestamp(), :type)";
            $stmt = $conn->prepare($sql);
            $datas = [':OID'=>$OID, 'account'=>$shop_account, 'shopname'=>$account, 'price'=>-$total, 'type'=>'Refund'];
            $stmt->execute($datas);

            echo <<<EOT
            <script>
            window.alert("Canceled")
            location.replace("../nav.php");
            </script>
            EOT;
        }else{
            echo <<<EOT
            <script>
            window.alert("Cancel Failed")
            location.replace("../nav.php");
            </script>
            EOT;
        }
    
    
    

    



    
?>