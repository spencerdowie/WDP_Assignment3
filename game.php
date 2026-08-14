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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Post</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure that you want to <strong>DELETE</strong> this game page?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="./delete-game.php" method="post" class="align-self-center">
                    <input name="delete" hidden />
                    <input name="id" type="text" value="<?php echo $gameID ?>" hidden />
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="d-flex w-100 flex-row">
    <h1><?php echo $game["name"] ?></h1><a class="btn btn-primary ms-5 me-2 align-self-center"
        href=<?php echo "./edit-game.php?id=" . $gameID ?>>Edit</a>
    <button type="button" class="btn btn-danger align-self-center" data-bs-toggle="modal" data-bs-target="#deleteModal">
        Delete
    </button>
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