<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged']!=true) {
    echo "
    <script>
      alert('Please loggin');
      window.location.href = 'index.php';
    </script>
    ";
    exit();
}
try{
    $act = $_REQUEST['action'];
    $account = $_SESSION['account'];
    $return = "";
    $test = 'test';
    $conn = require_once "../db_account/config.php";

    if($act=='All'){
        $stmt=$conn->prepare("select * from i_order where account=:account;");
        $stmt->execute(array('account' => $account));
      }
    else{
        $stmt=$conn->prepare("select * from i_order where account=:account and status=:status");
        $stmt->execute(array('account' => $account, 'status' => $act));
    }
    $a = array();
    if($stmt->fetchColumn()>0){
        if($act=='All'){
            $stmt=$conn->prepare("select OID, status, start, end, shopname, price, sum(subtotal) as total, count(OID) as num, distance from i_order where account=:account Group BY OID;");
            $stmt->execute(array('account' => $account));
        }
        else{
            $stmt=$conn->prepare("select OID, status, start, end, shopname, price, sum(subtotal) as total, count(OID) as num, distance from i_order where account=:account and status =:status Group BY OID;");
            $stmt->execute(array('account' => $account, 'status' => $act));
        }
        $result=$stmt->fetchAll();
        $i=0;
        $oid_array = [];
        foreach ($result as $row){
            $i+=1;
            $nstatus=$row['status'];
            $start = $row['start'];
            $end = $row['end'];
            $shop = $row['shopname'];
            $price = $row['total'];
            $OID = $row['OID'];
            $distance=$row['distance'];
            
            if(intval($distance/100)<10){
                $price = $price+10;
            }else{
                $price = $price+intval($distance/100);
            }
            $flag = False;
            array_push($a, array($shop, $OID));
            echo <<<EOT
            <tr>
            EOT;
            if($nstatus=="Not Finished"){
                echo<<<EOT
                <th>
                <input type="checkbox" class="checkbox" name="checkbox[]" value="$OID" id="checkbox_$OID">
                </th>
                EOT;
            }else{
                echo<<<EOT
                <th>
                </th>
                EOT;
            }

            echo<<<EOT
            <th scope="row">$i</th>
            <td>$nstatus</td>
            <td>$start</td>
            <td>$end</td>
            <td>$shop</td>
            <td>$price</td>
          
            <td><button type="button" class="btn btn-info " data-toggle="modal" data-target="#$shop$OID">Open menu</button></td>
            EOT;
            if($nstatus=="Not Finished"){
              echo <<<EOT
              <td>
              <form action ="php/cancel.php" method="post">
              <input name="cancel" type="hidden" value=$OID>
              <input type="submit" class="btn btn-danger" id = "cancel" value="Cancel" onclick="return confirm('Are you sure to cancel the order?');">
              </form>
              </td>
              EOT;
            }
            echo '</tr>';
        }
    }
   
}
catch(Exception $e){
    if($conn->inTransction()){
        $conn->rollback();
    }
    $msg = $e->getMessage();
    echo "$msg";

}


?>