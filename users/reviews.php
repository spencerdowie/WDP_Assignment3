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

$user = get_user($userId);

if (!$user)
{
    die("User not found.");
}

$reviews = get_reviews_by_user($userId);
?>

<h1>
    <?php echo htmlspecialchars($user["username"]); ?>'s Reviews
</h1>

<p>
    Total Reviews:
    <?php echo count($reviews) ?>
</p>

<hr>

<?php if (empty($reviews)): ?>

    <div class="alert alert-info">
        This user has not written any reviews yet.
    </div>

<?php else: ?>

    <?php foreach ($reviews as $review): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="card-title">
                    <?php echo htmlspecialchars($review["title"]); ?>
                </h2>
                <h5>
                    <?php echo htmlspecialchars($review["game_name"]); ?>
                </h5>

                <div class="mb-2">
                    <?php for ($i = 1; $i <= 5; $i++)
                    {
                        echo $i <= $review["rating"] ? "★" : "☆";
                    } ?>
                </div>

                <p>
                    <?php echo nl2br(htmlspecialchars($review["body"])); ?>
                </p>

                <p class="text-muted">
                    <?php echo htmlspecialchars($review["created_at"]); ?>
                </p>

                <a href="../reviews/details.php?id=<?php echo $review["id"]; ?>"
                    class="btn btn-outline-primary">
                    View Review
                </a>

            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<?php
require_once("../components/footer.php");
?>