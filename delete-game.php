<?php
//needs to separate from header include 
//so we can set the page title _after_ getting the game name
require_once("./db.php");

if (isset($_POST["delete"]))
{
    $gameID = $_POST["id"];

    if (isset($gameID))
    {
        if (delete_game_entry($gameID))
        {
            header("Location: index.php");
        }
    }
}
$pageTitle = "Error";
require_once("components/header.php");
?>
<div class="flex-grow-1 d-flex flex-column mt-5 pt-5">
    <h1 class="text-center">Error deleting game</h1>
</div>

<?php
require_once("./components/footer.php");
?>