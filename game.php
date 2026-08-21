<?php
require_once("./components/db.php");

$game = null;
if (isset($_GET["id"])) {
    $game = get_game($_GET["id"]);
}

if (!$game) {
    header("Location: index.php");
    exit;
}

$reviews = get_reviews($game["id"]);
$inCollection =  false;
$hasReviewed =  false;

if ($isLoggedIn && !$isAdmin) {
    $inCollection = is_in_collection($_SESSION["id"], $game["id"]);
    $hasReviewed = has_reviewed($_SESSION["id"], $game["id"]);
}

$pageTitle = $game["name"];
require_once("components/header.php");
?>

<div class="row g-4 my-3">
    <!-- Game Image -->
    <div class="col-md-4 text-center">
        <?php if (!empty($game["image_url"])): ?>
            <img src="public/images/games/<?= htmlspecialchars($game["image_url"]); ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($game["name"]); ?>">
        <?php else: ?>
            <div class="p-5 bg-light text-muted border rounded">No Image Available</div>
        <?php endif; ?>
    </div>

    <!-- Game Details -->
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h2 mb-0 fw-bold"><?= htmlspecialchars($game["name"]); ?></h1>
            <?php if ($isAdmin): ?>
                <div class="d-flex gap-2">
                    <a href="edit-game.php?id=<?= $gameID; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="delete-game.php" method="post" onsubmit="return confirm('Delete this game permanently?');">
                        <input type="hidden" name="id" value="<?= $gameID; ?>">
                        <button type="submit" name="delete" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <span class="badge bg-secondary mb-3"><?= htmlspecialchars($game["category_name"]); ?></span>

        <p class="small text-muted mb-3">
            <strong>Players:</strong> <?= $game["min_players"]; ?>–<?= $game["max_players"]; ?> &bull;
            <strong>Play Time:</strong> <?= $game["min_play_time"]; ?>–<?= $game["max_play_time"]; ?> mins &bull;
            <strong>Published:</strong> <?= htmlspecialchars($game["date_published"]); ?>
        </p>

        <p><?= nl2br(htmlspecialchars($game["description"])); ?></p>

        <!-- User Actions -->
        <div class="mt-4">
            <?php if ($isLoggedIn && !$isAdmin): ?>
                <?php if ($inCollection): ?>
                    <form action="collection/remove.php" method="post" class="d-inline">
                        <input type="hidden" name="game_id" value="<?= $game["id"]; ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Remove from Collection</button>
                    </form>
                <?php else: ?>
                    <form action="collection/add.php" method="post" class="d-inline">
                        <input type="hidden" name="game_id" value="<?= $game["id"]; ?>">
                        <button type="submit" class="btn btn-success btn-sm">+ Add to Collection</button>
                    </form>
                <?php endif; ?>
                <?php if (!$hasReviewed): ?>
                    <a href="reviews/create.php?game_id=<?= $game["id"]; ?>" class="btn btn-primary btn-sm ms-2">Write Review</a>
                <?php endif; ?>
            <?php elseif (!$isLoggedIn): ?>
                <a href="login.php" class="btn btn-warning btn-sm">Log in to save or review this game</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Community Reviews Section -->
<section class="mt-5">
    <h3 class="h5 border-bottom pb-2 mb-3">Community Reviews</h3>

    <?php if (empty($reviews)): ?>
        <p class="text-muted small">No reviews yet. Be the first to share your thoughts!</p>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($reviews as $review): ?>
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="h6 fw-bold mb-0"><?= htmlspecialchars($review["title"]); ?></h4>
                            <span class="text-warning small"><?= str_repeat("★", $review["rating"]) . str_repeat("☆", 5 - $review["rating"]); ?></span>
                        </div>
                        <p class="text-muted extra-small mb-2" style="font-size: 0.85rem;">
                            By <a href="/users/reviews.php?id=<?php echo $review['user_id'] ?>"><?= htmlspecialchars($review["first_name"] ?: $review["username"]); ?></a> on <?= date("M j, Y", strtotime($review["created_at"])); ?>
                            <?php if ($review["updated_at"] !== $review["created_at"]): ?>
                                <span class="ms-1">· Updated <?= date("M j, Y", strtotime($review["updated_at"])); ?></span>
                            <?php endif; ?>
                        </p>

                        <p class="card-text mb-2"><?= nl2br(htmlspecialchars($review["body"])); ?></p>

                        <div class="small text-muted">
                            <span class="me-3">Played <?= (int)$review["play_count"]; ?> times</span>
                            <span><?= $review["recommend"] ? "👍 Recommended" : "👎 Not recommended"; ?></span>
                        </div>
                        <?php if ($isLoggedIn && ($_SESSION["id"] == $review["user_id"] || $isAdmin)): ?>
                            <div class="d-flex gap-2 flex-row align-items-center">
                                <a href="reviews/edit.php?id=<?= $review["id"]; ?>" class="btn btn-link btn-sm text-secondary p-0">Edit</a>
                                <button type="button" class="btn btn-link btn-sm text-danger p-0" data-bs-toggle="modal" data-bs-target="#deleteReviewModal<?= $review["id"]; ?>">Delete</button>

                                <div class="modal fade" id="deleteReviewModal<?= $review["id"]; ?>" tabindex="-1" aria-labelledby="deleteReviewLabel<?= $review["id"]; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold" id="deleteReviewLabel<?= $review["id"]; ?>">Delete Review</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center py-4">
                                                <h5 class="fw-bold">Delete this review?</h5>
                                                <p class="text-muted mb-0">Are you sure you want to delete <strong><?= htmlspecialchars($review["title"]); ?></strong>?</p>
                                                <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="reviews/delete.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $review["id"]; ?>">
                                                    <input type="hidden" name="game_id" value="<?= $game["id"]; ?>">
                                                    <button type="submit" class="btn btn-danger">Delete Review</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once("components/footer.php"); ?>