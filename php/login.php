<?php
// session start, clear formor authentication
session_start();
$_SESSION['logged'] = false;

// Process and return if the registeration is success
$account = $_REQUEST['account'];
$password = $_REQUEST['password'];

// connect to database
$conn = require_once "../db_account/config.php";
$hashpwd = hash('sha256', $password);

try {
    $sql = "SELECT *, ST_X(location) as latitude, ST_Y(location) as longitude FROM user WHERE account = :account AND password = :hashpwd";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account, ':hashpwd'=>$hashpwd];
    $stmt->execute($data);

    // fetch data and store in session
    if ($stmt->rowCount() != 0) {
        $row = $stmt->fetch();

        $_SESSION['logged'] = true;
        $_SESSION['account'] = $account;
        $_SESSION['name'] = $row['name'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['latitude'] = $row['latitude'];
        $_SESSION['longitude'] = $row['longitude'];

        echo "
        <script>
            alert ('登入成功');
            window.location.href = '../nav.html';
        </script>
        ";
    }
    else {
        alert_index ("登入失敗");
    }
}
catch (Exception $e){
    alert_index("$e->getMessage()");

    // exception handle, destroy session
    session_unset();
    session_destroy();
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