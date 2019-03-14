<?php
date_default_timezone_set("Europe/Moscow");

$is_auth = isset($_SESSION["user"]) ? true : false;
$user_name = $is_auth ? $_SESSION["user"]["username"] : "";
$user_avatar = $is_auth ? $_SESSION["user"]["avatar_path"] : "";
$user_id = $is_auth ? intval($_SESSION["user"]["id"]) : null;

$categories = [];

// Получение списка категорий
$categories_sql = "SELECT * FROM `category` ORDER BY `category`. `id`";
$categories = get_data($link, $categories_sql);

?>