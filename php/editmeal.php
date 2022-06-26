<?php require_once("db_config.php"); ?>
<?php
    // print_r($_POST);
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