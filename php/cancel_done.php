<?php require_once("db_config.php"); ?>
<?php
    if(!isset($_POST["OID"]) or !isset($_POST["ele_id"])){
        die("No OID or no element id");
    }
    if(!isset($_POST["start"])){
        die("No start value");
    }
    $OID = $_POST['OID'];
    $ele_id = $_POST["ele_id"];
    $start = $_POST["start"];
    
    if($ele_id == $OID."_cancel"){
        // echo "cancel\n";
        $sql =  "UPDATE i_order SET status = 'Cancel', start = ?, end = NOW() WHERE OID = ?";
    }
    else if($ele_id == $OID."_done"){
        // echo "done\n";
        $sql =  "UPDATE i_order SET status = 'Finished', start = ?, end = NOW() WHERE OID = ?";
    }
    else{
        die("Something Wrong\n");
    }
    
    $stmt = $conn->prepare($sql);   // avoid sql injection
    $stmt->bind_param("si", $start ,$OID);  // 's' specifies the variable type => 'string'
    $stmt->execute();
    $result = $stmt->get_result();
    echo "Successfully Update! Do you want to reload the pages?";
?>