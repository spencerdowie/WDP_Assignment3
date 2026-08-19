<?php
require_once("../db.php");

//  Auth check
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

// game id 
$gameId = 0;
if (isset($_POST["game_id"])) {
    $gameId = (int)$_POST["game_id"];
}

// remove when valid 
if ($_SERVER["REQUEST_METHOD"] === "POST" && $gameId > 0) {
    remove_from_collection($_SESSION["id"], $gameId);
    header("Location: index.php");
    exit;
}


header("Location: ../index.php");
exit;
?>