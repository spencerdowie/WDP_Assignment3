<?php
require_once("../db.php");
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "My Reviews";
require_once("../components/header.php");
$reviews = get_reviews_by_user($_SESSION["id"]);
?>

<h1 class="h2 mb-4 fw-bold">My Reviews</h1>

<?php if (empty($reviews)): ?>
    <p class="text-muted">You have not written any reviews yet.</p>
<?php endif; ?>

<?php foreach ($reviews as $review): ?>
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="../game.php?id=<?php echo $review["game_id"]; ?>" class="h5 card-title text-decoration-none fw-bold"><?php echo htmlspecialchars($review["game_name"]); ?></a>
                <span class="text-warning font-monospace"><?php echo str_repeat("★", $review["rating"]) . str_repeat("☆", 5 - $review["rating"]); ?></span>
            </div>
            <h6 class="fw-semibold text-secondary"><?php echo htmlspecialchars($review["title"]); ?></h6>
            <p class="card-text mb-2"><?php echo nl2br(htmlspecialchars($review["body"])); ?></p>
            <div class="d-flex gap-2">
                <a href="../review/edit.php?id=<?php echo $review["id"]; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form action="../review/delete.php" method="post" onsubmit="return confirm('Delete this review?');">
                    <input type="hidden" name="id" value="<?php echo $review["id"]; ?>">
                    <input type="hidden" name="game_id" value="<?php echo $review["game_id"]; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php require_once("../components/footer.php"); ?>