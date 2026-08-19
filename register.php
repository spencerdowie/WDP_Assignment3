<?php
require_once('components/header.php');
$pageTitle = "Register - GA(IN)-ME";
require_once('db.php');

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$isValid = true;

if (isset($_POST['submit'])) {

    if (
        empty($_POST['first_name']) || 
        empty($_POST['last_name']) || 
        empty($_POST['username']) || 
        empty($_POST['email']) || 
        empty($_POST['password']) || 
        empty($_POST['confirmPassword']) || 
        empty($_POST['terms']) || 
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false || 
        $_POST['password'] !== $_POST['confirmPassword']
    ) {
        $isValid = false;
    }

    if ($isValid == true) {
        $conn = create_connection();

        // Check if username or email already exists
        $checkQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
        if ($checkStmt = $conn->prepare($checkQuery)) {
            $checkStmt->bind_param("ss", $_POST['username'], $_POST['email']);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                $isValid = false;
            } else {
                // Insert new user into database
                $query = "INSERT INTO users (first_name, last_name, username, email, password, role_id) VALUES (?, ?, ?, ?, ?, 2)";
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("sssss", $_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['email'], $hashedPassword);
                    
                    if ($stmt->execute()) {
                        $stmt->close();
                        $conn->close();
                        header("Location: login.php?registered=true");
                        exit;
                    } else {
                        $isValid = false;
                    }
                }
            }
            $checkStmt->close();
        }
        $conn->close();
    }
}
?>

<div class="card mx-auto shadow-sm my-4" style="max-width: 500px;">
    <div class="card-body p-4">
        <h1 class="h3 card-title text-center mb-4 fw-bold">Create Account</h1>

        <?php if ($isValid === false) : ?>
            <div class="alert alert-danger" role="alert">
                There was an error with your registration. Please check all fields and ensure the passwords match and terms are accepted.
            </div>
        <?php endif; ?>

        <form method="post" action="#">
            <div class="row">
                <div class="col mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="col mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">I agree to the Terms & Conditions</label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="mt-3 text-center">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</div>

<?php require_once('components/footer.php'); ?>