<?php
function create_connection()
{
    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "wdp_a3";

    $conn = mysqli_connect($hostname, $username, $password, $dbname);

    if ($conn->connect_error)
    {
        die("Connection Failed: " . $conn->connect_error);
    }

    return $conn;
}

function get_all_games(int $limit)
{
    $conn = create_connection();
    $games = [];

    $sql = "SELECT `id`, `name`, `date_published`, `description` FROM `games` LIMIT ?";
    if ($query = $conn->prepare($sql))
    {
        $query->bind_param("i", $limit);
        if ($query->execute())
        {
            $result = $query->get_result();
            $games = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    $conn->close();
    return $games;
}

function get_game(int $gameID)
{
    $conn = create_connection();
    $game = null;

    $sql = "SELECT `id`, `name`, `date_published`,`description` FROM `games` WHERE `id` = ? LIMIT 1";
    if ($query = $conn->prepare($sql))
    {
        $query->bind_param("i", $gameID);
        if ($query->execute())
        {
            $result = $query->get_result();
            $game = $result->fetch_all(MYSQLI_ASSOC)[0];
        }
    }

    $conn->close();
    return $game;
}

// function check_duplicates(){

// }

function create_game_entry(string $name, string $desc, string $date)
{
    $conn = create_connection();
    $success = false;

    $sql = "INSERT INTO `games` (`name`, `description`, `date_published`) VALUES (?, ?, ?)";
    if ($query = $conn->prepare($sql))
    {
        $query->bind_param("sss", $name, $desc, $date);
        if ($query->execute())
        {
            //echo $conn->insert_id;
            $_SESSION["createSuccess"] = true;
            $success = true;
        }
    }
    else
    {
        $_SESSION["createSuccess"] = false;
        $success = false;
    }

    $conn->close();
    return $success;
}

function update_game_entry(int $gameID, string $name, string $desc, string $date)
{
    $conn = create_connection();
    $success = false;

    $sql = "UPDATE `games` SET `name` = ?, `description` = ?, `date_published` = ? WHERE `id` = ?";
    if ($query = $conn->prepare($sql))
    {
        $query->bind_param("sssi", $name, $desc, $date, $gameID);
        if ($query->execute())
        {
            //echo $conn->insert_id;
            $_SESSION["updateSuccess"] = true;
            $success = true;
        }
    }
    else
    {
        $_SESSION["updateSuccess"] = false;
        $success = false;
    }

    $conn->close();
    return $success;
}

function get_reviews($gameID)
{
    $review = [];
    $review["body"] = "Review Body Text";
    return [$review];
}
