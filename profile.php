<?php
require_once("components/db.php");
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["id"];
$conn = create_connection();
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_account"])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $conn->close();
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);
    if ($stmt->execute()) {
        $_SESSION["first_name"] = $firstName;
        $success = "Profile updated successfully.";
    } else {
        $errors[] = "Email is already taken.";
    }
}

$stmt = $conn->prepare("SELECT username, email, first_name, last_name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$conn->close();

$pageTitle = "My Profile - GA(IN)-ME";
require_once("components/header.php");
?>

<div class="card mx-auto shadow-sm" style="max-width:500px;">
    <div class="card-body">
        <h1 class="h3 mb-3 fw-bold">My Profile</h1>
        <?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        <form method="post" class="mb-4">
            <div class="row g-2 mb-3">
                <div class="col"><label class="form-label">First Name</label><input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user["first_name"]); ?>" required></div>
                <div class="col"><label class="form-label">Last Name</label><input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user["last_name"]); ?>" required></div>
            </div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user["email"]); ?>" required></div>
            <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($user["username"]); ?>" disabled></div>
            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
        </form>

        <div class="border-top pt-3">
            <h5 class="text-danger">Danger Zone</h5>
            <form method="post" onsubmit="return confirm('Permanently delete your user account?');">
                <button type="submit" name="delete_account" class="btn btn-outline-danger btn-sm">Delete Account</button>
            </form>
        </div>
    </div>
</div>

<?php require_once("components/footer.php"); ?>