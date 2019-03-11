<?php
date_default_timezone_set("Europe/Moscow");

$is_auth = isset($_SESSION["user"]) ? true : false;
$user_name = $is_auth ? $_SESSION["user"]["username"] : "";
$user_avatar = $is_auth ? $_SESSION["user"]["avatar_path"] : "";
$user_id = $is_auth ? $_SESSION["user"]["id"] : "";

$categories = [];
$lots = [];

// Запрос на получение списка 9 новых открытых лотов
$lots_sql = "SELECT `l`.`id`, `l`.`name`, `c`.`name` AS `category`, `l`.`start_price`, `l`.`img_path`, `l`.`start_date`, `l`.`end_date`
    FROM `lot` AS `l`
    JOIN `category` AS `c` 
    ON l.`category_id` = `c`.`id`
     WHERE `l`.`winner_id` IS NULL
    ORDER BY `l`.`start_date` DESC LIMIT 9;";

// Запрос на получение списка категорий
$categories_sql = "SELECT * FROM `category`";

$categories = get_data($link, $categories_sql);
$lots = get_data($link, $lots_sql);

?>