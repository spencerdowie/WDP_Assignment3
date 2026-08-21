<?php
if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}
require_once("db.php");

if (!isset($pageTitle) || !$pageTitle || $pageTitle == "")
    $pageTitle = "GA(IN)-ME";
else
    $pageTitle .= " - GA(IN)-ME";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</head>


<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold text-warning" href="/index.php">GA(IN)-ME</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarText">
                <!-- Left-aligned links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if ($isAdmin): ?>
                        <!-- Admin Links -->
                        <li class="nav-item"><a class="nav-link text-warning" href="/admin/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/categories.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/users.php">Users</a></li>

                    <?php elseif ($isLoggedIn): ?>
                        <!-- User Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="/collection/index.php">My Collection</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="/users/reviews.php?id=<?php echo $_SESSION['id'] ?>">My Reviews</a>
                        </li>

                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/about.php">About</a>
                    </li>
                </ul>

                <!-- Right-aligned buttons -->
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (!$isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login.php">Login</a>
                        </li>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-warning btn-sm text-dark fw-semibold" href="/register.php">Register</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile.php"> Profile</a>
                        </li>
                        <li class="nav-item mt-2 mt-lg-0">
                            <a class="btn btn-outline-danger btn-sm py-1 px-3" href="/logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4 flex-grow-1">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- -------------------------REST OF CONTENT OF BODY GOES HERE------------------------------ -->