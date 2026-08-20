<?php
require_once("../components/db.php");

//admin check
if (!isset($_SESSION["id"]) || (int)$_SESSION["role_id"] !== 1) {
    header("Location: ../index.php");
    exit;
}

$pageTitle = "Categories";
require_once("../components/header.php");
$conn = create_connection();

// Delete
if (isset($_POST["delete_id"])) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $_POST["delete_id"]);
    $stmt->execute();
    header("Location: categories.php");
    exit;
}

// Add or Update
if (isset($_POST["save_category"])) {
    $id = (int)($_POST["category_id"] ?? 0);
    $name = trim($_POST["category_name"] ?? "");
    $desc = trim($_POST["description"] ?? "");

    if ($name !== "") {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE categories SET category_name = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $desc, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $desc);
        }
        $stmt->execute();
        header("Location: categories.php");
        exit;
    }
}

// Fetch for Editing
$editCat = null;
if (isset($_GET["edit"])) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $_GET["edit"]);
    $stmt->execute();
    $editCat = $stmt->get_result()->fetch_assoc();
}

// Fetch All Categories
$categories = $conn->query("
    SELECT categories.*, COUNT(games.id) AS game_count 
    FROM categories 
    LEFT JOIN games ON games.category_id = categories.id 
    GROUP BY categories.id 
    ORDER BY categories.category_name
")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<h2>Categories</h2>

<table class="table align-middle mb-4">
    <thead>
        <tr>
            <th>Category</th>
            <th>Description</th>
            <th>Games</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat["category_name"]) ?></td>
                <td><?= htmlspecialchars($cat["description"]) ?></td>
                <td><?= $cat["game_count"] ?></td>
                <td>
                    <a href="categories.php?edit=<?= $cat["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form method="post" action="#" onsubmit="return confirm('Delete category?');" class="d-inline">
                        <input type="hidden" name="delete_id" value="<?= $cat["id"] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="card p-3" style="max-width: 400px;">
    <h4><?= $editCat ? "Edit Category" : "Add Category" ?></h4>

    <form method="post" action="#">
        <input type="hidden" name="category_id" value="<?= $editCat['id'] ?? 0 ?>">

        <div class="mb-2">
            <label class="form-label">Category Name</label>
            <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($editCat['category_name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($editCat['description'] ?? '') ?>">
        </div>

        <button type="submit" name="save_category" class="btn btn-primary"><?= $editCat ? "Update" : "Add" ?></button>
        <?php if ($editCat): ?>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<?php require_once("../components/footer.php"); ?>