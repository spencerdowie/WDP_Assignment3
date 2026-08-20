<?php
require_once("../components/db.php");

// Auth check
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

// Get review ID
$reviewId = (int)($_REQUEST["id"] ?? 0);

// Fetch review
$conn = create_connection();
$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param("i", $reviewId);
$stmt->execute();
$review = $stmt->get_result()->fetch_assoc();

// Check permissions
$isAdmin = isset($_SESSION["role_id"]) && (int)$_SESSION["role_id"] === 1;
if (!$review || ($review["user_id"] != $_SESSION["id"] && !$isAdmin)) {
    header("Location: ../index.php");
    exit;
}

// Update review
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("UPDATE reviews SET rating=?, title=?, body=?, recommend=?, play_count=? WHERE id=?");
    $stmt->bind_param("issiii", $_POST["rating"], $_POST["title"], $_POST["body"], $_POST["recommend"], $_POST["play_count"], $reviewId);
    $stmt->execute();
    
    header("Location: ../game.php?id=" . $review["game_id"]);
    exit;
}

$pageTitle = "Edit Review";
require_once("../components/header.php");
?>

<div class="container my-4" style="max-width: 500px;">
    <h2 class="mb-3 fw-bold">Edit Review</h2>

    <form method="post">
        <input type="hidden" name="id" value="<?= $review["id"] ?>">

        <div class="mb-3">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-select">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>" <?= $review["rating"] == $i ? "selected" : "" ?>><?= $i ?> Stars</option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($review["title"]) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Review</label>
            <textarea name="body" class="form-control" rows="3" required><?= htmlspecialchars($review["body"]) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Recommend</label>
            <select name="recommend" class="form-select">
                <option value="1" <?= $review["recommend"] ? "selected" : "" ?>>Yes</option>
                <option value="0" <?= !$review["recommend"] ? "selected" : "" ?>>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Times Played</label>
            <input type="number" name="play_count" class="form-control" min="0" value="<?= $review["play_count"] ?>">
        </div>

        <button type="submit" class="btn btn-warning w-100 fw-bold">Update Review</button>
    </form>
</div>

<?php require_once("../components/footer.php"); ?>