<?php
require_once("./db.php");
$game = get_game(1);
$pageTitle = $game["name"];
require_once("components/header.php");

?>

<h1><?php echo $game["name"] ?></h1>
<p>Published: <?php echo $game["date_published"] ?></p>
<p><?php echo $game["description"] ?></p>

<?php
require_once("./components/footer.php");
?>