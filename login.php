<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

$servername = "47.245.98.74";
$username = "SAMPMobile";
$password = "wI124elOs886ka4iNIYEduNOXEfuR5";
$dbname = "sampmobile";

$headers = getallheaders();
$clientKey = $headers['X-Api-Key'] ?? '';

if ($clientKey !== 'f9bcffe35040c63926306fbbc5b5182b') {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit();
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode(["success" => false, "error" => "Missing fields"]);
    exit();
}

$user = $_POST['username'];
$pass = $_POST['password'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($pass, $row['password'])) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => "Server error"]);
}
?>