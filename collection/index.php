<?php
require_once("../components/db.php");

if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "My Collection";
require_once("../components/header.php");

$collection = get_user_collection($_SESSION["id"]);
?>

<h1 class="display-6 fw-bold mb-3">My Collection</h1>

<?php if (empty($collection)): ?>
    <p class="text-muted">No games saved yet. <a href="../index.php">Browse board games</a> to build your collection!</p>
<?php endif; ?>

<div class="row row-cols-1 row-cols-md-3 g-3">
    <?php foreach ($collection as $game): ?>
        <div class="col">
            <div class="card h-100 p-2">
                <?php if (!empty($game["image_url"])): ?>
                    <img src="../public/images/games/<?php echo htmlspecialchars($game["image_url"]); ?>" class="card-img-top" style="height:150px; object-fit:contain;">
                <?php endif; ?>
                
                <div class="card-body d-flex flex-column p-2">
                    <h5 class="card-title h6 mb-1"><?php echo htmlspecialchars($game["name"]); ?></h5>
                    <div class="text-muted small mb-3"><?php echo htmlspecialchars($game["category_name"]); ?></div>
                    
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <a href="../game.php?id=<?php echo $game["id"]; ?>" class="btn btn-primary btn-sm">View</a>
                        
                        <form action="remove.php" method="post" class="d-inline">
                            <input type="hidden" name="game_id" value="<?php echo $game["id"]; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once("../components/footer.php"); ?>