<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

// Process and return if the registeration is success
$account = $_SESSION['account'];
// $password = $_SESSION['password'];

// connect to database
$conn = require_once "../db_account/config.php";

// Refresh session
$sql = "SELECT * FROM user WHERE account = :account";
$stmt = $conn->prepare($sql);
$data = [':account'=>$account];
$stmt->execute($data);

$row = $stmt->fetch();
$_SESSION['balance'] = $row['balance'];
$output = $_SESSION['balance'];

echo "$output";
?>