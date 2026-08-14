<?php
//needs to separate from header include 
//so we can set the page title _after_ getting the game name
require_once("./db.php");


$gameID = $_GET["id"];
$game = null;

if ((isset($gameID) && is_numeric($gameID)))
{
    $game = get_game($gameID);
}

if ($game == null)
    header("Location: index.php");

if (isset($_POST["submit"]))
{
    $name = $_POST["name"];
    $desc = $_POST["desc"];
    $date = $_POST["date"];

    if (isset($name) && isset($desc) && isset($date))
    {
        if (update_game_entry($gameID, $name, $desc, $date))
        {
            header("Location: game.php?id=" . $gameID);
        }
    }
}
$pageTitle = "Edit " . $game["name"];
require_once("components/header.php");
?>

<h1>Edit <?php echo $game["name"] ?> Game Page</h1>
<form method="post" action="#" class="d-flex flex-column w-50">
    <input name="submit" hidden />
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?php echo strval($game["name"]) ?>" />
    <label for="desc">Description</label>
    <textarea name="desc" type="text" id="desc" style="height: 200px;"><?php echo $game["description"] ?></textarea>
    <label for="date">Date Published</label>
    <input type="date" name="date" id="date" value="<?php echo $game["date_published"] ?>" />
    <button type="submit" class="btn btn-primary">Save Edit</button>
</form>

<?php
require_once("./components/footer.php");
?>