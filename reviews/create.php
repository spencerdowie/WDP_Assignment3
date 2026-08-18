<?php
$pageTitle = "Write a Review";

require_once("../components/header.php");
require_once("../db.php");

$conn = create_connection();

/*
    MOCK USER
    This will be replaced with:
    $_SESSION["id"]
    when the real login system is connected.
*/
$userId = 1;

$gameId = isset($_GET["game_id"]) ? (int)$_GET["game_id"] : 0;

if ($gameId <= 0)
{
    die("Invalid game.");
}

// Get game
$sql = "SELECT id, name
        FROM games
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $gameId);
$stmt->execute();

$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game)
{
    die("Game not found.");
}

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $rating = (int)($_POST["rating"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $body = trim($_POST["body"] ?? "");
    $recommend = (int)($_POST["recommend"] ?? 0);
    $playCount = (int)($_POST["play_count"] ?? 0);

    // PHP validation

    if ($rating < 1 || $rating > 5)
    {
        $errorMessage = "Please select a rating from 1 to 5.";
    }
    elseif ($title === "")
    {
        $errorMessage = "Please enter a review title.";
    }
    elseif ($body === "")
    {
        $errorMessage = "Please enter your review.";
    }
    elseif ($playCount < 0)
    {
        $errorMessage = "Play count cannot be negative.";
    }
    else
    {
        $sql = "INSERT INTO reviews
                (
                    user_id,
                    game_id,
                    rating,
                    title,
                    body,
                    recommend,
                    play_count
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iiissii",
            $userId,
            $gameId,
            $rating,
            $title,
            $body,
            $recommend,
            $playCount
        );

        if ($stmt->execute())
        {
            header("Location: index.php?game_id=" . $gameId);
            exit;
        }

        $errorMessage = "Unable to create review.";
    }
}
?>

<h1>Write a Review</h1>

<h2>
    <?php echo htmlspecialchars($game["name"]); ?>
</h2>

<?php if ($errorMessage !== ""): ?>

    <div class="alert alert-danger">
        <?php echo htmlspecialchars($errorMessage); ?>
    </div>

<?php endif; ?>

<form method="POST" id="reviewForm">

    <div class="mb-3">

        <label for="rating" class="form-label">
            Rating
        </label>

        <select
            name="rating"
            id="rating"
            class="form-select"
        >
            <option value="">Select rating</option>
            <option value="1">★ 1</option>
            <option value="2">★ 2</option>
            <option value="3">★ 3</option>
            <option value="4">★ 4</option>
            <option value="5">★ 5</option>
        </select>

        <div id="ratingError" class="text-danger"></div>

    </div>


    <div class="mb-3">

        <label for="title" class="form-label">
            Review Title
        </label>

        <input
            type="text"
            name="title"
            id="title"
            class="form-control"
            maxlength="255"
        >

        <div id="titleError" class="text-danger"></div>

    </div>


    <div class="mb-3">

        <label for="body" class="form-label">
            Your Review
        </label>

        <textarea
            name="body"
            id="body"
            class="form-control"
            rows="6"
        ></textarea>

        <div id="bodyError" class="text-danger"></div>

    </div>


    <div class="mb-3">

        <label for="recommend" class="form-label">
            Would you recommend this game?
        </label>

        <select
            name="recommend"
            id="recommend"
            class="form-select"
        >
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

    </div>


    <div class="mb-3">

        <label for="play_count" class="form-label">
            How many times have you played this game?
        </label>

        <input
            type="number"
            name="play_count"
            id="play_count"
            class="form-control"
            min="0"
            value="0"
        >

        <div id="playCountError" class="text-danger"></div>

    </div>


    <button
        type="submit"
        class="btn btn-primary"
    >
        Submit Review
    </button>

    <a
        href="index.php?game_id=<?php echo $gameId; ?>"
        class="btn btn-secondary"
    >
        Cancel
    </a>

</form>


<script>

window.onload = function ()
{
    const form = document.getElementById("reviewForm");

    form.addEventListener("submit", function (event)
    {
        let isValid = true;

        const rating = document.getElementById("rating");
        const title = document.getElementById("title");
        const body = document.getElementById("body");
        const playCount = document.getElementById("play_count");

        document.getElementById("ratingError").textContent = "";
        document.getElementById("titleError").textContent = "";
        document.getElementById("bodyError").textContent = "";
        document.getElementById("playCountError").textContent = "";


        if (rating.value < 1 || rating.value > 5)
        {
            document.getElementById("ratingError").textContent =
                "Please select a rating.";

            isValid = false;
        }


        if (title.value.trim() === "")
        {
            document.getElementById("titleError").textContent =
                "Please enter a review title.";

            isValid = false;
        }


        if (body.value.trim() === "")
        {
            document.getElementById("bodyError").textContent =
                "Please enter your review.";

            isValid = false;
        }


        if (playCount.value === "" || playCount.value < 0)
        {
            document.getElementById("playCountError").textContent =
                "Please enter a valid play count.";

            isValid = false;
        }


        if (!isValid)
        {
            event.preventDefault();
        }
    });
};

</script>


<?php
$conn->close();

require_once("../components/footer.php");
?>