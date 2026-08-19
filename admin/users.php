<?php
require_once("../db.php");

//admin check
if (!isset($_SESSION["id"]) || (int)$_SESSION["role_id"] !== 1) {
    header("Location: ../index.php");
    exit;
}

$pageTitle = "Users";
require_once("../components/header.php");
$conn = create_connection();

// Delete User
if (isset($_POST["delete_id"])) {
    $id = (int)$_POST["delete_id"];
    if ($id !== (int)$_SESSION["id"]) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: users.php");
    exit;
}

//  Update User
if (isset($_POST["update_user"])) {
    $id = (int)($_POST["user_id"] ?? 0);
    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $roleId = (int)($_POST["role_id"] ?? 2);

    if ($id > 0 && $firstName !== "" && $email !== "") {
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, role_id = ? WHERE id = ?");
        $stmt->bind_param("sssii", $firstName, $lastName, $email, $roleId, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: users.php");
        exit;
    }
}

//  Get Active Edit User ID
$editId = isset($_GET["edit"]) ? (int)$_GET["edit"] : 0;

//  Fetch Users and Roles
$users = $conn->query("
    SELECT users.*, roles.role_name 
    FROM users 
    JOIN roles ON users.role_id = roles.id 
    ORDER BY users.created_at DESC
")->fetch_all(MYSQLI_ASSOC);

$roles = $conn->query("SELECT * FROM roles ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<h2>Users</h2>

<table class="table align-middle mb-4">
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u["first_name"] . " " . $u["last_name"]); ?></td>
                <td><?php echo htmlspecialchars($u["username"]); ?></td>
                <td><?php echo htmlspecialchars($u["email"]); ?></td>
                <td>
                    <span class="badge bg-<?php echo $u["role_id"] == 1 ? "dark" : "secondary"; ?>">
                        <?php echo htmlspecialchars($u["role_name"]); ?>
                    </span>
                </td>
                <td><?php echo date("M j, Y", strtotime($u["created_at"])); ?></td>
                <td>
                    <a href="users.php?edit=<?php echo $u["id"]; ?>" class="btn btn-warning btn-sm">Edit</a>
                    
                    <?php if ($u["id"] != $_SESSION["id"]): ?>
                        <form method="post" action="#" onsubmit="return confirm('Delete this user?');" class="d-inline">
                            <input type="hidden" name="delete_id" value="<?php echo $u["id"]; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- Inline Edit Form Row -->
            <?php if ($editId === (int)$u["id"]): ?>
                <tr class="table-light">
                    <td colspan="6">
                        <form method="post" action="#" class="row g-2 align-items-center">
                            <input type="hidden" name="user_id" value="<?php echo $u["id"]; ?>">
                            
                            <div class="col-md-3">
                                <input type="text" name="first_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($u["first_name"]); ?>" placeholder="First Name" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="last_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($u["last_name"]); ?>" placeholder="Last Name">
                            </div>
                            <div class="col-md-3">
                                <input type="email" name="email" class="form-control form-control-sm" value="<?php echo htmlspecialchars($u["email"]); ?>" placeholder="Email" required>
                            </div>
                            <div class="col-md-2">
                                <select name="role_id" class="form-select form-select-sm">
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role["id"]; ?>" <?php echo $role["id"] == $u["role_id"] ? "selected" : ""; ?>>
                                            <?php echo htmlspecialchars($role["role_name"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" name="update_user" class="btn btn-primary btn-sm w-100">Save</button>
                            </div>
                            <div class="col-md-12 text-end">
                                <a href="users.php" class="text-secondary small text-decoration-none">Cancel Edit</a>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>

        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once("../components/footer.php"); ?>