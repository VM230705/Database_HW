<?php require_once("db_config.php"); ?>
<?php
    // Update 
    if(empty($_POST["price"]) && empty($_POST["quantity"])){
        die("Nothing Change");
    }

    if(!ctype_digit($_POST["price"]) || !ctype_digit($_POST["quantity"])){
        die("Wrong format!! Price and quantity must be integer!!");
    }
    if(!isset($_POST["mealname"])){
        die("No mealname");
    }
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $mealname = $_POST["mealname"];
    $shopname = $_POST["shopname"];

     // check if there exist unfinished order containing editing meal
    $status = "Not Finished";
    $orderquery = "SELECT price, quantity, mealname FROM i_order WHERE shopname = ? and mealname = ? and status = ?";
    // $o_stmt = $conn->query($orderquery);
    $o_stmt = $conn->prepare($orderquery);   // avoid sql injection
    $o_stmt->bind_param("sss", $shopname, $mealname, $status);  // 's' specifies the variable type => 'string'
    $o_stmt->execute();
    $o_result = $o_stmt->get_result();
    if($o_result->num_rows > 0){
        // $row = $o_result->fetch_assoc();
        $mealquery = "SELECT price, quantity, mealname FROM meal WHERE shopname = ? and mealname = ?";
        $m_stmt = $conn->prepare($mealquery);   // avoid sql injection
        $m_stmt->bind_param("ss", $shopname, $mealname);  // 's' specifies the variable type => 'string'
        $m_stmt->execute();
        $m_result = $m_stmt->get_result();
        if($m_result->num_rows > 0){
            $row = $m_result->fetch_assoc();

            // 設定只能增加quantity
            if($price == $row['price'] && $quantity > $row['quantity'] && $mealname == $row['mealname']){
                echo "ok";
            }
            else{
                die("
                <script>
                    if(confirm('Cannot Update!!! Because there exists unfinished order containing this meal!!') == true){
                        location.href = '../nav.php';
                    }
                </script>");
            }      
        }
        else{
            die("
            <script>
                if(confirm('Can't find meal!!') == true){
                    location.href = '../nav.php';
                }
            </script>");
        }      
        
    }
    // if($o_stmt->num_rows>0){ 
    //     while($meal = $o_stmt->fetch_assoc()){
    //         array_push($order_meal, $meal);
    //     }
    // }
    // else{
    //     die("Selection = 0");
    // }



    /** check if shop name already exist or not */ 
    $sql =  "UPDATE meal SET price = ?, quantity = ? WHERE mealname = ?";
    $stmt = $conn->prepare($sql);   // avoid sql injection
    $stmt->bind_param("iis", $price, $quantity, $mealname);  // 's' specifies the variable type => 'string'
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<script>
            if(confirm('Update Successfully! Do you want to go back previous page?') == true){
                location.href = '../nav.php';
            }
        </script>";

    echo "<p> <a href='../nav.php#menu1'>Back to previous page</a> </p>"
?>