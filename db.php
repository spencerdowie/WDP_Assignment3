<?php
// Session initialization 
if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

// Auto-login cookie
if (!isset($_SESSION["id"]) && isset($_COOKIE["remember_me"]))
{
    $token = $_COOKIE["remember_me"];
    $conn = create_connection();

    $stmt = $conn->prepare("SELECT id, username, role_id, first_name FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1)
    {
        $user = $result->fetch_assoc();
        $_SESSION["id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role_id"] = $user["role_id"];
        $_SESSION["first_name"] = $user["first_name"];
    }
    $stmt->close();
    $conn->close();
}

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

// Helper query functions
function get_all_games(int $limit = 100)
{
    $conn = create_connection();
    $sql = "SELECT games.*, categories.category_name 
            FROM games 
            JOIN categories ON games.category_id = categories.id 
            ORDER BY games.name LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $games = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $games;
}

function get_game(int $gameID)
{
    $conn = create_connection();
    $sql = "SELECT games.*, categories.category_name 
            FROM games 
            JOIN categories ON games.category_id = categories.id 
            WHERE games.id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gameID);
    $stmt->execute();
    $game = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $game;
}

function create_game_entry($name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $imageUrl = '')
{
    $conn = create_connection();
    $sql = "INSERT INTO `games` (`name`, `description`, `date_published`, `category_id`, `min_players`, `max_players`, `min_play_time`, `max_play_time`, `image_url`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiiiis", $name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $imageUrl);
    $success = $stmt->execute();
    $conn->close();
    return $success;
}

function update_game_entry($gameID, $name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $imageUrl = null)
{
    $conn = create_connection();
    if ($imageUrl !== null)
    {
        $sql = "UPDATE `games` SET `name`=?, `description`=?, `date_published`=?, `category_id`=?, `min_players`=?, `max_players`=?, `min_play_time`=?, `max_play_time`=?, `image_url`=? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiiiisi", $name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $imageUrl, $gameID);
    }
    else
    {
        $sql = "UPDATE `games` SET `name`=?, `description`=?, `date_published`=?, `category_id`=?, `min_players`=?, `max_players`=?, `min_play_time`=?, `max_play_time`=? WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiiiii", $name, $desc, $date, $categoryId, $minPlayers, $maxPlayers, $minPlayTime, $maxPlayTime, $gameID);
    }
    $success = $stmt->execute();
    $conn->close();
    return $success;
}

function delete_game_entry(int $gameID)
{
    $conn = create_connection();
    $stmt = $conn->prepare("DELETE FROM `games` WHERE `id` = ?");
    $stmt->bind_param("i", $gameID);
    $success = $stmt->execute();
    $conn->close();
    return $success;
}

function get_user(int $userID)
{
    $conn = create_connection();
    $sql = "SELECT username, email, first_name, last_name 
            FROM users 
            WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $conn->close();
    return $user;
}

function get_all_categories()
{
    $conn = create_connection();
    $res = $conn->query("SELECT * FROM categories ORDER BY category_name")->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $res;
}

function get_reviews(int $gameID)
{
    $conn = create_connection();
    $stmt = $conn->prepare("SELECT reviews.*, users.username, users.first_name FROM reviews JOIN users ON reviews.user_id = users.id WHERE reviews.game_id = ? ORDER BY reviews.created_at DESC");
    $stmt->bind_param("i", $gameID);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $res;
}

function get_reviews_by_user(int $userId)
{
    $conn = create_connection();
    $stmt = $conn->prepare("SELECT reviews.*, games.name AS game_name FROM reviews JOIN games ON reviews.game_id = games.id WHERE reviews.user_id = ? ORDER BY reviews.created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $res;
}

function delete_review(int $reviewId, int $userId, bool $isAdmin)
{
    $conn = create_connection();
    if ($isAdmin)
    {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $reviewId);
    }
    else
    {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $reviewId, $userId);
    }
    $success = $stmt->execute();
    $conn->close();
    return $success;
}

function get_user_collection(int $userId)
{
    $conn = create_connection();
    $stmt = $conn->prepare("SELECT collections.id AS collection_id, collections.date_added, games.*, categories.category_name FROM collections JOIN games ON collections.game_id = games.id JOIN categories ON games.category_id = categories.id WHERE collections.user_id = ? ORDER BY collections.date_added DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $res;
}

function is_in_collection(int $userId, int $gameId)
{
    $conn = create_connection();
    $stmt = $conn->prepare("SELECT id FROM collections WHERE user_id = ? AND game_id = ?");
    $stmt->bind_param("ii", $userId, $gameId);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $conn->close();
    return $exists;
}

function add_to_collection(int $userId, int $gameId)
{
    $conn = create_connection();
    $stmt = $conn->prepare("INSERT IGNORE INTO collections (user_id, game_id, date_added) VALUES (?, ?, CURDATE())");
    $stmt->bind_param("ii", $userId, $gameId);
    $success = $stmt->execute();
    $conn->close();
    return $success;
}

function remove_from_collection(int $userId, int $gameId)
{
    $conn = create_connection();
    $stmt = $conn->prepare("DELETE FROM collections WHERE user_id = ? AND game_id = ?");
    $stmt->bind_param("ii", $userId, $gameId);
    $success = $stmt->execute();
    $conn->close();
    return $success;
}

function get_stats()
{
    $conn = create_connection();
    $stats = [
        "games" => $conn->query("SELECT COUNT(*) c FROM games")->fetch_assoc()["c"],
        "users" => $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()["c"],
        "reviews" => $conn->query("SELECT COUNT(*) c FROM reviews")->fetch_assoc()["c"],
    ];
    $conn->close();
    return $stats;
}
