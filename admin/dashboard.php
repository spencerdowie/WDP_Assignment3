<?php
require_once("../components/db.php");

if (!isset($_SESSION["id"]) || (int)$_SESSION["role_id"] !== 1) {
    header("Location: ../index.php");
    exit;
}

$pageTitle = "Admin Dashboard";
require_once("../components/header.php");

$stats = get_stats();
$recentGames = get_all_games(5);
?>

<h2>Admin Dashboard</h2>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h3><?php echo $stats["games"]; ?></h3>
            <div class="text-muted">Total Games</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h3><?php echo $stats["users"]; ?></h3>
            <div class="text-muted">Users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <h3><?php echo $stats["reviews"]; ?></h3>
            <div class="text-muted">Reviews</div>
        </div>
    </div>
</div>

<!-- Recent Games -->
<h4>Recent Games</h4>
<ul class="list-group mb-4">
    <?php foreach ($recentGames as $game): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="../game.php?id=<?php echo $game["id"]; ?>"><?php echo htmlspecialchars($game["name"]); ?></a>
            <span class="badge bg-secondary"><?php echo htmlspecialchars($game["category_name"]); ?></span>
        </li>
    <?php endforeach; ?>
</ul>


<?php require_once("../components/footer.php"); ?>