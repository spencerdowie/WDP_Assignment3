<?php
require_once("../db.php");

// auth check
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

// game id 
$gameId = 0;
if (isset($_POST["game_id"])) {
    $gameId = (int)$_POST["game_id"];
}

// if valid add 
if ($_SERVER["REQUEST_METHOD"] === "POST" && $gameId > 0) {
    add_to_collection($_SESSION["id"], $gameId);
    header("Location: ../game.php?id=" . $gameId);
    exit;
}


header("Location: ../index.php");
exit;
?>