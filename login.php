<?php
require_once('components/header.php');
$pageTitle = "Login - GA(IN)-ME";
require_once('db.php');

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$isValid = true;

if (isset($_POST['submit'])) {

    if (empty($_POST['email']) || empty($_POST['password']) || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
        $isValid = false;
    }

    if ($isValid) {
        $conn = create_connection();
        $query = "SELECT id, email, password, username, role_id, first_name FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($query)) {

            $stmt->bind_param("s", $_POST['email']);

            if ($stmt->execute() == false) {
                echo "Execute failed: " . $stmt->error;
            } else {

                $result = $stmt->get_result();
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if (empty($rows)) {
                    $isValid = false;
                } else {
                    $user = $rows[0];

                    if (password_verify($_POST['password'], $user['password']) == true) {

                        // Password is valid - set session variables
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role_id'] = $user['role_id'];
                        $_SESSION['first_name'] = $user['first_name'];

                        // Handle Remember Me cookie
                        if (isset($_POST['remember_me'])) {
                            $token = bin2hex(random_bytes(32));
                            $updateQuery = "UPDATE users SET remember_token = ? WHERE id = ?";
                            if ($updateStmt = $conn->prepare($updateQuery)) {
                                $updateStmt->bind_param("si", $token, $user['id']);
                                $updateStmt->execute();
                                $updateStmt->close();
                            }
                            setcookie("remember_me", $token, time() + (86400 * 30), "/", "", false, true);
                        }

                        header("Location: index.php");
                        exit;
                    } else {
                        $isValid = false;
                    }
                }
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>
 <!-- <h1>Login</h1> -->



<div class="card mx-auto shadow-sm my-4" style="max-width: 400px;">
    <div class="card-body p-4">
        <?php if (isset($_GET['registered']) && $_GET['registered'] == true) : ?>
    <div class="alert alert-success" role="alert">
        Your account is registered! Please log in.
    </div>
<?php endif; ?>

<?php if ($isValid === false) : ?>
    <div class="alert alert-danger" role="alert">
        Your email/password is incorrect.
    </div>
<?php endif; ?>
       

        <form method="post" action="#">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="mt-3 text-center">
            <a href="register.php">Sign up for new account</a>
        </div>
    </div>
</div>
<?php require_once('components/footer.php'); ?>