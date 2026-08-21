<?php
require_once("../components/db.php");

//  Auth check
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

// game id 
$gameId = 0;
if (isset($_GET["game_id"])) {
    $gameId = (int)$_GET["game_id"];
}

// Fetch game info
$game = get_game($gameId);

if (!$game) {
    header("Location: ../index.php");
    exit;
}

// Handle form submission
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rating = 0;
    if (isset($_POST["rating"])) {
        $rating = (int)$_POST["rating"];

        $errorMessage = "Please select a rating between 1 and 5.";
    }

    $title = "";
    if (isset($_POST["title"])) {
        $rawTitle = $_POST["title"];
        $title = trim($rawTitle);
    }

    $body = "";
    if (isset($_POST["body"])) {
        $rawBody = $_POST["body"];
        $body = trim($rawBody);
    }

    $recommend = 1;
    if (isset($_POST["recommend"])) {
        $recommend = (int)$_POST["recommend"];
    }

    $playCount = 1;
    if (isset($_POST["play_count"])) {
        $playCount = (int)$_POST["play_count"];
    }

    // Validate inputs
    if ($rating < 1 || $rating > 5 || $title === "" || $body === "") {
        $errorMessage = "Please complete all required fields.";
    } else {
        $userId = (int)$_SESSION["id"];
        if (create_review($userId, $gameId, $rating, $title, $body, $recommend, $playCount)) {
            header("Location: /game.php?id=" . $gameId);
            exit;
        } else {
            $errorMessage = "Could not save review. Please try again.";
        }
    }
}

$pageTitle = "Write Review";
require_once("../components/header.php");
?>

<h2>Write Review</h2>
<h4 class="text-muted mb-3"><?php echo htmlspecialchars($game["name"]); ?></h4>

<?php if ($errorMessage !== ""): ?>
    <div class="alert alert-danger py-2"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<div class="card p-3" style="max-width: 500px;">
    <form method="post" action="#">

        <div class="mb-2">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-select" required>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
        </div>

        <div class="mb-2">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Review</label>
            <textarea name="body" class="form-control" rows="4" required></textarea>
        </div>


        <div class="mb-2">
            <label class="form-label">Would you recommend this game?</label>
            <select name="recommend" class="form-select">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>


        <label for="title" class="form-label">
            Review Title
        </label>

        <div class="mb-3">
            <label class="form-label">Times Played</label>
            <input type="number" name="play_count" class="form-control" min="0" value="1">
        </div>

        <button type="submit" class="btn btn-primary">Submit Review</button>
        <a href="../game.php?id=<?php echo $gameId; ?>" class="btn btn-secondary">Cancel</a>

    </form>
</div>

<?php require_once("../components/footer.php"); ?>