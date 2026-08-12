<?php
$pageTitle = "Add Game";
require_once("components/header.php");

if (isset($_POST["submit"]))
{
    $name = $_POST["name"];
    $desc = $_POST["desc"];
    $date = $_POST["date"];

    if (isset($name) && isset($desc) && isset($date))
    {
        if (create_game_entry($name, $desc, $date))
        {
            echo "Great Succcess!!";
        }
    }
}
?>

<h1>Create New Game Page</h1>
<form method="post" action="#" class="d-flex flex-column w-50">
    <input name="submit" hidden />
    <label for="name">Name</label>
    <input type="text" id="name" name="name" />
    <label for="desc">Description</label>
    <textarea name="desc" type="text" id="desc" style="height: 200px;"></textarea>
    <label for="date">Date Published</label>
    <input type="date" name="date" id="date" />
    <button type="submit" class="btn btn-primary">Create Game</button>
</form>

<?php
require_once("./components/footer.php");
?>