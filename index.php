<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

$category_to_class_name = [
    "Доски и лыжи" => "boards",
    "Крепления" => "attachment",
    "Ботинки" => "boots",
    "Одежда" => "clothing",
    "Инструменты" => "tools",
    "Разное" => "other"
];

$lots = [];

$lots_sql = "SELECT `l`.`id`, `l`.`name`, `c`.`name` AS `category`, `l`.`start_price`, `l`.`img_path`, `l`.`start_date`, `l`.`end_date`
    FROM `lot` AS `l`
    JOIN `category` AS `c` 
    ON l.`category_id` = `c`.`id`
    WHERE `l`.`winner_id` IS NULL
    ORDER BY `l`.`start_date` DESC LIMIT 9;";
$lots = get_data($link, $lots_sql);

$page_content = include_template("index.php", [
    "lots" => $lots,
    "categories" => $categories,
    "category_to_class_name" => $category_to_class_name
]);
$layout_content = include_template("layout.php", [
    "page_content" => $page_content,
    "user_name" => $user_name,
    "is_auth" => $is_auth,
    "user_avatar" => $user_avatar,
    "page_title" => "Главная",
    "categories" => $categories
]);
print($layout_content);
?>