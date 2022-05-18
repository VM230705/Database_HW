<?php 
    // Include the database configuration file  
    require_once 'db_config.php'; 
    print_r($_POST);
    // print_r($_FILES);
    if(empty($_POST["shopname"])){
        die("<span style='color: red;'>No shop name</span>");
    }
    if(empty($_POST["mealname"]) || empty($_POST["price"]) || empty($_POST["quantity"]) || empty($_FILES["myFile"])){
        die("<span style='color: red;'>All fields must be filled!!</span>");
    }

    if(!ctype_digit($_POST["price"]) || !ctype_digit($_POST["quantity"])){
        die("<span style='color: red;'>Wrong format!! Price and quantity must be integer!!</span>");
    }
    
    $mealname = $_POST["mealname"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    //暫時
    $shopname = $_POST["shopname"];
   
    // If file upload form is submitted 
    $status = $statusMsg = ''; 
    if(isset($_POST["mealname"])){
        $status = 'error'; 
        if(!empty($_FILES["myFile"]["name"])) { 
            // Get file info 
            $fileName = basename($_FILES["myFile"]["name"]); 
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 

            // Allow certain file formats 
            $allowTypes = array('jpg','png','jpeg','gif'); 
            if(in_array($fileType, $allowTypes)){
                //開啟圖片檔
                $file = fopen($_FILES["myFile"]["tmp_name"], "rb");
                // 讀入圖片檔資料
                $fileContents = fread($file, filesize($_FILES["myFile"]["tmp_name"])); 
                //關閉圖片檔
                fclose($file);
                //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
                //$fileContents = base64_decode($fileContents);
                //echo $fileContents;
                //$image = $_FILES['myFile']['tmp_name']; 
                //$imgContent = addslashes(file_get_contents($image));
                //echo $imgContent;
                //echo "fuck you";
                //echo base64_decode($imgContent); 
                $query = "INSERT INTO meal (mealname, picture, price, quantity, shopname) 
                VALUES (?, ?, ?, ?, ?)";
                // Insert image content into database 

                // $insert = $conn->query($query) or die($conn->error); 
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssiis', $mealname, $fileContents, $price, $quantity, $shopname); // replace question marks with values
                $stmt->execute();
                //$stmt->execute(array(":mealname"=>$mealname, ":imgContent"=>$imgContent,":price"=>$price,":quantity"=>$quantity,":shopname"=>$shopname));
                $result = $stmt->get_result();
                if($result){ 
                    
                    $status = 'Success!'; 
                    $statusMsg = "File uploaded successfully.";
                    echo "<span style='color: green;'>".$status."</span><br/>";
                }
                echo "<span style='color: green;'>File uploaded successfully.".$statusMsg."</span>"; 
                // else{
                //     echo $result."1";
                //     $statusMsg = "File upload failed, please try again."; 
                // }  
            }
            else{ 
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
                echo "<span style='color: red;'>".$statusMsg."</span>";
            } 
        }
        else{ 
            $statusMsg = 'Please select an image file to upload.';
            echo "<span style='color: red;'>".$statusMsg."</span>";

        } 
    }
    // echo "end";
    // Display status message 
?>
