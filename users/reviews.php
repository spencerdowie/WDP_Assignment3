<?php
$pageTitle = "User Reviews";

require_once("../components/header.php");

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

$noReviewMsg = "This user has not written any reviews yet.";
$title = htmlspecialchars($user["first_name"] ?? $user["username"]) . "'s Reviews";
if ($isLoggedIn && $_SESSION["id"] == $userId)
{
    $noReviewMsg = "You have not written any reviews yet.";
    $title = "My Reviews";
}
?>

<h1 class="display-6 fw-bold mb-3">
    <?php echo $title ?>
</h1>

<p>
    Total Reviews:
    <?php echo count($reviews) ?>
</p>

<hr>

<?php if (empty($reviews)): ?>

    <div class="alert alert-info">
        <?php echo $noReviewMsg ?>
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