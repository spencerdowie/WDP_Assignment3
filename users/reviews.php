<?php
$pageTitle = "User Reviews";

require_once("../components/header.php");
require_once("../db.php");

$conn = create_connection();

$userId = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($userId <= 0)
{
    die("Invalid user.");
}

// Get user
$sql = "SELECT id, username
        FROM users
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user)
{
    die("User not found.");
}

// Get user's reviews
$sql = "SELECT
            reviews.id,
            reviews.game_id,
            reviews.rating,
            reviews.title,
            reviews.body,
            reviews.created_at,
            games.name AS game_name
        FROM reviews
        INNER JOIN games
            ON reviews.game_id = games.id
        WHERE reviews.user_id = ?
        ORDER BY reviews.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$reviews = $stmt->get_result();
?>

<h1>
    <?php echo htmlspecialchars($user["username"]); ?>'s Reviews
</h1>

<p>
    Total Reviews:
    <?php echo $reviews->num_rows; ?>
</p>

<hr>

<?php if ($reviews->num_rows === 0): ?>

    <div class="alert alert-info">
        This user has not written any reviews yet.
    </div>

<?php else: ?>

    <?php while ($review = $reviews->fetch_assoc()): ?>

        <div class="card mb-3">

            <div class="card-body">

                <h2 class="card-title">
                    <?php echo htmlspecialchars($review["title"]); ?>
                </h2>

                <h5>
                    <?php echo htmlspecialchars($review["game_name"]); ?>
                </h5>

                <div class="mb-2">

                    <?php
                    for ($i = 1; $i <= 5; $i++)
                    {
                        echo $i <= $review["rating"] ? "★" : "☆";
                    }
                    ?>

                </div>

                <p>
                    <?php echo nl2br(htmlspecialchars($review["body"])); ?>
                </p>

                <p class="text-muted">
                    <?php echo htmlspecialchars($review["created_at"]); ?>
                </p>

                <a
                    href="../reviews/details.php?id=<?php echo $review["id"]; ?>"
                    class="btn btn-outline-primary"
                >
                    View Review
                </a>

            </div>

        </div>

    <?php endwhile; ?>

<?php endif; ?>

<?php
$conn->close();

require_once("../components/footer.php");
?>