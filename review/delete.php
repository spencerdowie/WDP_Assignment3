<?php
require_once("../db.php");

// auth check
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

// post inputs
$reviewId = 0;
if (isset($_POST["id"])) {
    $reviewId = (int)$_POST["id"];
}

$gameId = 0;
if (isset($_POST["game_id"])) {
    $gameId = (int)$_POST["game_id"];
}

// admin check
$isAdmin = false;
if (isset($_SESSION["role_id"]) && (int)$_SESSION["role_id"] === 1) {
    $isAdmin = true;
}

// if valid delete
if ($_SERVER["REQUEST_METHOD"] === "POST" && $reviewId > 0) {
    delete_review($reviewId, $_SESSION["id"], $isAdmin);
    
    if ($gameId > 0) {
        header("Location: ../game.php?id=" . $gameId);
        exit;
    }
}

header("Location: ../index.php");
exit;
?>