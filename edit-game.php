<?php
require_once("db.php");
// Inline Auth Check
if (!isset($_SESSION["id"]) || (int)$_SESSION["role_id"] !== 1) {
    header("Location: index.php");
    exit;
}

$gameID = isset($_GET["id"]) ? (int) $_GET["id"] : (int) ($_POST["id"] ?? 0);
$game = $gameID > 0 ? get_game($gameID) : null;
if (!$game) {
    header("Location: index.php");
    exit;
}

$errors = [];
$categories = get_all_categories();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $desc = trim($_POST["desc"] ?? "");
    $date = $_POST["date"] ?? "";
    $categoryId = (int) ($_POST["category_id"] ?? 0);
    $minPlayers = (int) ($_POST["min_players"] ?? 1);
    $maxPlayers = (int) ($_POST["max_players"] ?? 1);
    $minPlayTime = (int) ($_POST["min_play_time"] ?? 0);
    $maxPlayTime = (int) ($_POST["max_play_time"] ?? 0);
    $imageName = null;

    if ($name === "") $errors[] = "Name is required.";
    if (!empty($_FILES["image"]["name"])) {
        $allowed = ["jpg", "jpeg", "png", "gif"];
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Image must be a JPG, PNG, or GIF file.";
        } else {
            $imageName = uniqid("game_") . "." . $ext;
            move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . "/public/images/games/" . $imageName);
        }
    }

    if (empty($errors)) {
        if (update_game_entry($gameID, $name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $imageName)) {
            header("Location: game.php?id=" . $gameID);
            exit;
        } else {
            $errors[] = "Failed to update game.";
        }
    }
}

$pageTitle = "Edit " . $game["name"] . " - GA(IN)-ME";
require_once("components/header.php");
?>

<div class="card mx-auto shadow-sm" style="max-width: 650px;">
    <div class="card-header bg-dark text-white"><h1 class="h4 mb-0">Edit Game</h1></div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $game["id"]; ?>">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($game["name"]); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat["id"]; ?>" <?php echo $cat["id"] == $game["category_id"] ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($cat["category_name"]); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row g-2 mb-3">
                <div class="col"><label class="form-label">Min Players</label><input type="number" name="min_players" class="form-control" value="<?php echo $game["min_players"]; ?>" required></div>
                <div class="col"><label class="form-label">Max Players</label><input type="number" name="max_players" class="form-control" value="<?php echo $game["max_players"]; ?>" required></div>
                <div class="col"><label class="form-label">Min Time</label><input type="number" name="min_play_time" class="form-control" value="<?php echo $game["min_play_time"]; ?>" required></div>
                <div class="col"><label class="form-label">Max Time</label><input type="number" name="max_play_time" class="form-control" value="<?php echo $game["max_play_time"]; ?>" required></div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="desc" class="form-control" rows="4" required><?php echo htmlspecialchars($game["description"]); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Date Published</label>
                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($game["date_published"]); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Replace Image (Optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-warning fw-semibold">Save Changes</button>
            <a href="game.php?id=<?php echo $game["id"]; ?>" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

<?php require_once("components/footer.php"); ?>