<?php
require_once("components/db.php");
// Inline Admin Check
if (!isset($_SESSION["id"]) || (int)$_SESSION["role_id"] !== 1) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    $gameID = (int) ($_POST["id"] ?? 0);
    if ($gameID > 0) {
        delete_game_entry($gameID);
    }
}
header("Location: index.php");
exit;
?>