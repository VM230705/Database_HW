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