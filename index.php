<?php
$pageTitle = "Home";
require_once("components/header.php");
$games = get_all_games(10);
?>

<h1>Dashboard</h1>
<h2>All Games</h2>
<ul>
    <?php foreach ($games as $game): ?>
        <li><?php echo $game["name"] ?></li>
    <?php endforeach; ?>
</ul>
<?php
require_once("./components/footer.php");
?>