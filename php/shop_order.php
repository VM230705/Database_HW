<?php
session_start();

if ($_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

try{
    $act = $_REQUEST['action'];
    $shopname = $_REQUEST["shopname"];
    $return = "";
    $test = 'test';
    $conn = require_once "../db_account/config.php";

    // Search & Increment append to the return result
    if ($act == 'ALL'){
        $sql = "SELECT OID, status, start, end, shopname, mealname, price, quantity, sum(subtotal) as total, 
            count(OID) as num, distance  FROM i_order WHERE shopname = :shopname GROUP BY OID ";
    }
    else if ($act == 'Finished'){
        $sql = "SELECT OID, status, start, end, shopname, mealname, price, quantity, sum(subtotal) as total, 
            count(OID) as num, distance  FROM i_order WHERE shopname = :shopname and status = 'Finished' GROUP BY OID ";
    }
    else if ($act == 'Not Finished'){
        $sql = "SELECT OID, status, start, end, shopname, mealname, price, quantity, sum(subtotal) as total, 
            count(OID) as num, distance  FROM i_order WHERE shopname = :shopname and status = 'Not Finished' GROUP BY OID ";
    }
    else if ($act == 'Canceled'){
        $sql = "SELECT OID, status, start, end, shopname, mealname, price, quantity, sum(subtotal) as total, 
            count(OID) as num, distance  FROM i_order WHERE shopname = :shopname and status = 'Canceled' GROUP BY OID ";
    }
    $stmt = $conn->prepare($sql);
    $data = [':shopname'=>$shopname];
    $stmt->execute($data);
    $total_price = 0;

    if ($stmt->rowCount() != 0) {
        $i = 1;
        foreach ($stmt as $row){
            $OID = $row['OID'];
            $status = $row['status'];
            $start = $row['start'];
            $end = $row['end'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $subtotal = $row['total'];
            $num = $row['num'];
            $distance = $row['distance'];

            $delivery_fee = intval($distance / 100);
            if($delivery_fee < 10 && $distance!=0){
                $delivery_fee = 10;
            }
            $total_price = $subtotal + $delivery_fee;
            $num = $row['num'];
            $append = "<tr id='tr_$OID'>";
            if($status == "Not Finished"){
                $append = $append . "
                <th>
                    <input type='checkbox' class='s_checkbox' name='s_checkbox[]' value='$OID' id='s_checkbox_$OID'>
                </th>";
            }
            else{
                $append = $append . "
                <th>
                </th>";
            }
            
            $append = $append . "
            <th scope='row' id='OID_$OID'>$i</th>
            <td id='status_$OID'> $status</td>
            <td id='start_$OID'> $start</td>
            <td id='end_$OID'> $end</td>
            <td id='shopname_$OID'> $shopname</td>
            <td id='total_$OID'> $total_price</td>
            <td id='details_$OID'><button type='button' class='btn btn-info' data-toggle='modal'
                data-target='#$OID-details'> order details </button></td>
            ";

            if(is_null($row['end'])){
                $btn =  "<td id='done_$OID'><button id='".$OID."_done' name='$OID' type='button' 
                        class='btn btn-info' style='background-color: #4CAF50;' onclick='shop_done(this)'>Done</button></td>
                    <td id='cancel_$OID'><button id='".$OID."_cancel' name='$OID' type='button' 
                        class='btn btn-info' style='background-color: #f44336;' onclick='shop_cancel(this)'>Cancel</button></td>
                    
                    ";
                $append = $append . $btn;
            }
            $temp = "<div class='modal fade' id='$OID-details' data-backdrop='static' tabindex='-1' role='dialog' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document' >
                        <div class='modal-content'>
                            <div class='modal-header'>
                            <h5 class='modal-title'>$OID Order</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>
                            <div class='modal-body' id='$OID-modal'>
                            </div>
                            
                            <div class='modal-footer' id='footer_$OID'>
                            <div> Subtotal $subtotal</div>
                            <div> Delivery fee $delivery_fee</div>
                            <div> Total $total_price</div>
                            </div>
                        </div>
                        </div>
                    </div>
                </tr>";
            $append = $append . $temp;
                        
            // modal
            // $modal_header = "
            //         <div class='modal fade' id='$OID-details' data-backdrop='static' tabindex='-1' role='dialog' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
            //             <div class='modal-dialog' role='document' >
            //             <div class='modal-content'>
            //                 <div class='modal-header'>
            //                 <h5 class='modal-title'>$OID Order</h5>
            //                 <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            //                     <span aria-hidden='true'>&times;</span>
            //                 </button>
            //                 </div>
            //                 ";
            // $modal_body = " <div class='modal-body' id='$OID-modal'>
            // <table>
            //     <thead>
            //         <tr>
            //             <th style='width: 25%;'>Picture</th>
            //             <th style='width: 30%;'>Meal Name</th>
            //             <th style='width: 25%;'>Price</th>
            //             <th style='width: 25%;'>Quantity</th>
            //         </tr>
            //     </thead>
            //     <tbody>";
            // $query = "SELECT OID, status, start, end, shopname, mealname, price, quantity, 
            // distance  FROM i_order where OID = :OID";
            // $stmt_detail = $conn->prepare($query);
            // $data_detail = [':OID'=>$OID];
            // $stmt_detail->execute($data_detail);
            // if($stmt_detail->rowCount() != 0){
            //     $j=1;
            //     foreach($stmt_detail as $row_detail){
            //         $mealname = $row_detail['mealname'];
            //         $price = $row_detail['price'];
            //         $quantity = $row_detail['quantity'];
            //         $query_picture = "SELECT picture from meal where shopname = :shopname and mealname = :mealname";
            //         $stmt_picture = $conn->prepare($query_picture);
            //         $data_picture = [':shopname'=>$shopname, ':mealname'=>$mealname];
            //         $stmt_picture->execute($data_picture);
            //         if($stmt_picture->rowCount() != 0){
            //             foreach($stmt_picture as $meal){
            //                 $picture = $meal['picture'];
            //             }
            //         }

            //         $tbody_row = "
            //         <tr>
            //             <td><img src=\"data:image/jpg;charset=utf8;base64,".base64_encode($picture)."\" style=\"width: 40%;\" with=\"50\" heigh=\"10\" alt=\"".$mealname."\"></td>
            //             <td>$mealname</td>
            //             <td>$price</td>
            //             <td>$quantity</td>
            //         </tr>";
            //         $modal_body = $modal_body . $tbody_row;
                
            //         $j = $j + 1;
            //     }
            //     $modal_body = $modal_body . "
            //                 </tbody>
            //             </table>
            //         </div>";
            // }
            
            // $modal_footer ="
            //                 <div class='modal-footer' id='footer_$OID'>
            //                     <div> Subtotal  $subtotal</div>
            //                     <div> Delivery fee $delivery_fee</div>
            //                     <div> Total $total_price</div>
            //                 </div>
            //             </div>
            //             </div>
            //         </div>
            //         </tr>";
            // $modal = $modal_header;
            // echo $modal;
            // $modal = $modal . $modal_body;
            // $modal = $modal . $modal_footer;
            // $append = $append . $modal;
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
    // 
    // 
    // 
    # shop order order details
    // $shop_order = [];
    // $inner_table = $conn->query("SELECT OID, status, start, end, shopname, mealname, price, quantity, 
    // distance  FROM i_order where shopname = '$shopname'");
    // if($inner_table->rowCount() != 0){  
    //     $shop_order = [];
    //     foreach($inner_table as $row){
    //         array_push($shop_order, $row);
    //     }
    // }
    // $subtotal = 0;
    // $iter = 0;
    // $m_num = 0;
    // foreach($shop_order as $s_order){
    //     $m_num++;
    //     $OID = $s_order['OID'];
    //     $mealname = $s_order['mealname'];
    //     $quantity = $s_order['quantity'];
    //     $price = $s_order['price'];
    //     if($iter!=0 and $lastID!=$OID){
    //         $subtotal = 0;
    //         $m_num=0;
    //     }
    //     $lastID = $OID;

    //     $s_order_stmt = $conn->query("SELECT picture from meal where shopname = '$shopname' and mealname = '$mealname'");
    //     // print_r($s_order_stmt);
    //     if($s_order_stmt->rowCount() != 0){
    //         foreach($s_order_stmt as $meal){
    //             $picture = $meal['picture'];
    //         }
    //     }
    //     if($subtotal == 0){
    //         echo "<script>
    //         let table".$OID." = document.createElement('table');
    //         let thead".$OID." = document.createElement('thead');
    //         let tbody".$OID." = document.createElement('tbody');
    //         table".$OID.".appendChild(thead".$OID.");
    //         table".$OID.".appendChild(tbody".$OID.");
    //         // Adding the entire table to the body tag
    //         document.getElementById('".$OID."-modal').appendChild(table".$OID.");
    //         let row_1".$OID." = document.createElement('tr');
    //         let heading_1".$OID." = document.createElement('th');
    //         heading_1".$OID.".innerHTML = 'Picture';
    //         heading_1".$OID.".setAttribute(\"style\", \"width: 25%;\")
    //         let heading_2".$OID." = document.createElement('th');
    //         heading_2".$OID.".innerHTML = 'Meal Name';
    //         heading_2".$OID.".setAttribute(\"style\", \"width: 30%;\")
    //         let heading_3".$OID." = document.createElement('th');
    //         heading_3".$OID.".innerHTML = 'Price';
    //         heading_3".$OID.".setAttribute(\"style\", \"width: 25%;\")
    //         let heading_4".$OID."= document.createElement('th');
    //         heading_4".$OID.".innerHTML = 'Quantity';
    //         heading_4".$OID.".setAttribute(\"style\", \"width: 25%;\")
            
    //         row_1".$OID.".appendChild(heading_1".$OID.");
    //         row_1".$OID.".appendChild(heading_2".$OID.");
    //         row_1".$OID.".appendChild(heading_3".$OID.");
    //         row_1".$OID.".appendChild(heading_4".$OID.");
    //         thead".$OID.".appendChild(row_1".$OID.");
    //         </script>\n"
    //         ;
    //     }
    //     echo "<script>
    //     let row_2".$OID.$m_num." = document.createElement('tr');
    //     let row_2_data_1".$OID.$m_num." = document.createElement('td');
    //     row_2_data_1".$OID.$m_num.".innerHTML = '<img src=\"data:image/jpg;charset=utf8;base64,".base64_encode($picture)."\" style=\"width: 40%;\" with=\"50\" heigh=\"10\" alt=\"".$mealname."\">';
    //     let row_2_data_2".$OID.$m_num." = document.createElement('td');
    //     row_2_data_2".$OID.$m_num.".innerHTML = '".$mealname."';
    //     let row_2_data_3".$OID.$m_num."= document.createElement('td');
    //     row_2_data_3".$OID.$m_num.".innerHTML = '".$price."';
    //     let row_2_data_4".$OID.$m_num." = document.createElement('td');
    //     row_2_data_4".$OID.$m_num.".innerHTML = '".$quantity."';

    //     row_2".$OID.$m_num.".appendChild(row_2_data_1".$OID.$m_num.");
    //     row_2".$OID.$m_num.".appendChild(row_2_data_2".$OID.$m_num.");
    //     row_2".$OID.$m_num.".appendChild(row_2_data_3".$OID.$m_num.");
    //     row_2".$OID.$m_num.".appendChild(row_2_data_4".$OID.$m_num.");
    //     tbody".$OID.".appendChild(row_2".$OID.$m_num.");
    //     </script>\n"
    //     ;
    //     $subtotal += $s_order['price'] * $s_order['quantity'];
    //     $iter++;
    // }
?>