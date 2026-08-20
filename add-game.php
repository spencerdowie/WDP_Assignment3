<?php
require_once("components/db.php");

//  Auth check
if (!isset($_SESSION["id"]) || (int)$_SESSION["role_id"] !== 1)
{
    header("Location: index.php");
    exit;
}

$pageTitle = "Add Game";
require_once("components/header.php");

$errors = [];
$categories = get_all_categories();

//  Form submission logic
if (isset($_POST["submit"]))
{

    $name = "";
    if (isset($_POST["name"]))
    {
        $name = trim($_POST["name"]);
    }

    $desc = "";
    if (isset($_POST["desc"]))
    {
        $rawDesc = $_POST["desc"];
        $desc = trim($rawDesc);
    }

    $date = "";
    if (isset($_POST["date"]))
    {
        $date = $_POST["date"];
    }

    $categoryId = 0;
    if (isset($_POST["category_id"]))
    {
        $categoryId = (int)$_POST["category_id"];
    }

    $minPlayers = 1;
    if (isset($_POST["min_players"]))
    {
        $minPlayers = (int)$_POST["min_players"];
    }

    $maxPlayers = 1;
    if (isset($_POST["max_players"]))
    {
        $maxPlayers = (int)$_POST["max_players"];
    }

    $minPlayTime = 0;
    if (isset($_POST["min_play_time"]))
    {
        $minPlayTime = (int)$_POST["min_play_time"];
    }

    $maxPlayTime = 0;
    if (isset($_POST["max_play_time"]))
    {
        $maxPlayTime = (int)$_POST["max_play_time"];
    }

    $imageName = "";

    // Validation checks
    if ($name === "")
    {
        $errors[] = "Name is required.";
    }
    if ($desc === "")
    {
        $errors[] = "Description is required.";
    }
    if ($date === "")
    {
        $errors[] = "Date published is required.";
    }
    if ($categoryId <= 0)
    {
        $errors[] = "Please choose a category.";
    }
    if ($minPlayers <= 0 || $maxPlayers < $minPlayers)
    {
        $errors[] = "Player counts are invalid.";
    }

    // Image Upload
    if (isset($_FILES["image"]) && $_FILES["image"]["name"] !== "")
    {
        $allowed = ["jpg", "jpeg", "png", "gif"];
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed))
        {
            $errors[] = "Image must be a JPG, PNG, or GIF file.";
        }
        else
        {
            $imageName = uniqid("game_") . "." . $ext;
            move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . "/public/images/games/" . $imageName);
        }
    }

    // Save Game Entry
    if (count($errors) === 0)
    {
        if (create_game_entry($name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $imageName))
        {
            header("Location: index.php");
            exit;
        }
        else
        {
            $errors[] = "Failed to add game. Please try again.";
        }
    }
}
?>

<h2>Add Game</h2>

<?php if (count($errors) > 0): ?>
    <div class="alert alert-danger py-2">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card p-3 mb-4" style="max-width: 500px;">
    <form method="post" action="#" enctype="multipart/form-data">
        <input name="submit" value="submit" hidden />
        <div class="mb-2">
            <label class="form-label">Title</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat["id"]; ?>">
                        <?php echo htmlspecialchars($cat["category_name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label">Min Players</label>
                <input type="number" name="min_players" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-6">
                <label class="form-label">Max Players</label>
                <input type="number" name="max_players" class="form-control" min="1" value="4" required>
            </div>
        </div>

        <div class="row g-2 mb-2">
            <div class="col-6">
                <label class="form-label">Min Time (mins)</label>
                <input type="number" name="min_play_time" class="form-control" min="0" value="30" required>
            </div>
            <div class="col-6">
                <label class="form-label">Max Time (mins)</label>
                <input type="number" name="max_play_time" class="form-control" min="0" value="60" required>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label">Description</label>
            <textarea name="desc" class="form-control" rows="4" required><?php echo htmlspecialchars($_POST["desc"] ?? ""); ?></textarea>
        </div>

        <div class="mb-2">
            <label class="form-label">Date Published</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cover Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Create Game</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>
</div>

<?php require_once("components/footer.php"); ?>