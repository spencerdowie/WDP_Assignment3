<?php
require_once("db.php");

$pageTitle = "GA(IN)-ME";
require_once("components/header.php");

// select category
$selectedCategory = 0;
if (isset($_GET["category_id"])) {
    $selectedCategory = (int)$_GET["category_id"];
}

// search input
$searchQuery = "";
if (isset($_GET["search"])) {
    $rawSearch = $_GET["search"];
    $searchQuery = trim($rawSearch);
}

//fetch categories
$categories = get_all_categories();
$games = get_filtered_games($selectedCategory, $searchQuery);


$myCollectionCount = 0;
$myReviewCount = 0;

if ($isLoggedIn && !$isAdmin) {
    $myCollectionCount = count(get_user_collection($_SESSION["id"]));
    $myReviewCount = count(get_reviews_by_user($_SESSION["id"]));
}
?>

<!-- Hero Banner -->
<div class="p-5 mb-4 text-white rounded shadow-sm" 
     style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('./public/images/hero.png') no-repeat center center; background-size: cover; min-height: 300px;">
     <h1 class="display-5 fw-bold">Discover. Collect. Play.</h1>
    <p class="col-md-8 fs-5">Your personal board game library. Browse the shelf, save games to your collection, and share reviews with the community.</p>
     
    </div>

<!-- User Stats Card -->
<?php if ($isLoggedIn && !$isAdmin): ?>
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION["first_name"] ? $_SESSION["first_name"] : $_SESSION["username"]); ?>!</h5>
                <small class="text-muted">Account overview</small>
            </div>
            <div class="d-flex gap-3 text-center">
                <div>
                    <strong><?php echo $myCollectionCount; ?></strong>
                    <div class="small text-muted">Saved</div>
                </div>
                <div>
                    <strong><?php echo $myReviewCount; ?></strong>
                    <div class="small text-muted">Reviews</div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>



<!-- Header & Admin Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Board Games</h3>
    <?php if ($isAdmin): ?>
        <a href="add-game.php" class="btn btn-success btn-sm">+ Add Game</a>
    <?php endif; ?>
</div>

<!-- Games Grid -->
<?php if (empty($games)): ?>
    <p class="text-muted">No games found matching your search.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
        <?php foreach ($games as $game): ?>
            <div class="col">
                <div class="card h-100 p-2">
                    <?php if (!empty($game["image_url"])): ?>
                        <img src="public/images/games/<?php echo htmlspecialchars($game["image_url"]); ?>" class="card-img-top" style="height: 150px; object-fit: contain;">
                    <?php else: ?>
                        <div class="bg-secondary text-white text-center py-4 rounded">No Image</div>
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column p-2">
                        <span class="badge bg-secondary mb-1 align-self-start"><?php echo htmlspecialchars($game["category_name"]); ?></span>
                        <h5 class="card-title h6 mb-1"><?php echo htmlspecialchars($game["name"]); ?></h5>
                        <small class="text-muted mb-3"><?php echo $game["min_players"]; ?>–<?php echo $game["max_players"]; ?> Players | <?php echo $game["min_play_time"]; ?>–<?php echo $game["max_play_time"]; ?> mins</small>
                        <a href="game.php?id=<?php echo $game["id"]; ?>" class="btn btn-primary btn-sm mt-auto w-100">View Game</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once("components/footer.php"); ?>