<?php
date_default_timezone_set("Europe/Moscow");
$user_name = 'Маргарита';

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

?>