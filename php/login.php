<?php
// Process and return if the registeration is success
$account = $_REQUEST['account'];
$password = $_REQUEST['password'];

// connect to database
$conn = require_once "../db_account/config.php";
$hashpwd = hash('sha256', $password);

try {
    $sql = "SELECT * FROM user WHERE account = :account AND password = :hashpwd";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account, ':hashpwd'=>$hashpwd];
    $stmt->execute($data);

    // fetch data and store in session
    if ($stmt->rowCount() != 0) {
        $_SESSION['logged'] = true;
        $_SESSION['account'] = $account;
        echo "
        <script>
            alert ('登入成功');
            window.location.href = '../nav.html';
        </script>
        ";
    }
    else {
        alert_index ("登入失敗 $hashpwd");
    }
}
catch (Exception $e){
    alert_index("$e->getMessage()");
}

// alert on index.php
function alert_index ($msg){
    echo "
    <script>
        alert('$msg');
        window.location.href = '../index.php';
    </script>
    ";
}

?>