<?php require_once("db_config.php"); ?>
<?php
    // echo "<script>
    // console.log('in php');
    // if(confirm('Are you sure you want to delete it?') == false){
    //     location.href = '../nav.php';
    // }
    // </script>";
    // print_r($_POST);
    if(!isset($_POST["mealname"])){
        die("No mealname");
    }
    $mealname = $_POST['mealname'];
    $shopname = $_POST['shopname'];

    // check if there exist unfinished order containing editing meal
    $status = "Not Finished";
    $orderquery = "SELECT price, quantity, mealname FROM i_order WHERE shopname = ? and mealname = ? and status = ?";
    // $o_stmt = $conn->query($orderquery);
    $o_stmt = $conn->prepare($orderquery);   // avoid sql injection
    $o_stmt->bind_param("sss", $shopname, $mealname, $status);  // 's' specifies the variable type => 'string'
    $o_stmt->execute();
    $o_result = $o_stmt->get_result();
    if($o_result->num_rows > 0){
        die("Cannot Update!! Because there exists unfinished order containing this meal!!");
    }



    /** check if shop name already exist or not */ 
    // delete from `user` where userid='$id'";
    $sql =  "DELETE FROM meal  WHERE mealname = ?";
    $stmt = $conn->prepare($sql);   // avoid sql injection
    $stmt->bind_param("s", $mealname);  // 's' specifies the variable type => 'string'
    $stmt->execute();
    $result = $stmt->get_result();
    echo "Successfully Delete! Do you want to reload the pages?";
    // echo "<script>
    //         if(confirm('Update Successfully! Do you want to go back previous page?') == true){
    //             location.href = '../nav.php';
    //         }
    //     </script>";
    
    // echo "<p> <a href='nav.php#menu1'>Back to previous page</a> </p>"
?>