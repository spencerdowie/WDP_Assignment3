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

//if id is missing or not a number or game wasn't found redirect 
if ($game == null)
    header("Location: index.php");

$reviews = get_reviews($game["id"]);

$pageTitle = $game["name"];
require_once("components/header.php");
?>

<div class="d-flex w-100 flex-row">
    <h1><?php echo $game["name"] ?></h1><a class="btn btn-primary ms-5 align-self-center"
        href=<?php echo "./edit-game.php?id=" . $gameID ?>>Edit</a>
</div>
<p>Published: <?php echo $game["date_published"] ?></p>
<p><?php echo $game["description"] ?></p>
<h2>Reviews</h2>
<div>
    <?php foreach ($reviews as $review): ?>
        <?php echo $review["body"] ?>
    <?php endforeach; ?>
</div>

<?php
require_once("./components/footer.php");
?>