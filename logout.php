
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("db.php");

if (isset($_SESSION["id"])) {
    // Clear token in DB
    $conn = create_connection();
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $conn->close();
}

// Clear session
$_SESSION = [];
session_destroy();

// Delete cookie by setting expiration time to past
setcookie("remember_me", "", time() - 3600, "/");

header("Location: login.php");
exit;
?>