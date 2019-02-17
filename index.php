<?php
require_once("functions.php");
require_once("data.php");

$link = mysqli_connect("localhost", "root", "", "407511-yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    print("Ошибка подключения: " + mysqli_connect_error());
} else {
    // Запрос на получение списка категорий
    $categories_sql = "SELECT * FROM `category`";
    $categories_result = mysqli_query($link, $categories_sql);

    if ($categories_result) {
        $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " + mysqli_error($link)); 
    }

    // Запрос на получение списка 9 новых открытых лотов
    $lots_sql = "SELECT `l`.`id`, `l`.`name`, `c`.`name` AS `category`, `l`.`start_price`, `l`.`img_path`, `l`.`start_date`, `l`.`end_date`
        FROM `lot` AS `l`
        JOIN `category` AS `c` 
        ON l.`category_id` = `c`.`id`
        WHERE `l`.`winner_id` IS NULL
        ORDER BY `l`.`start_date` DESC LIMIT 9;";

    $lots_result = mysqli_query($link, $lots_sql);

    if ($lots_result) {
        $lots = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " + mysqli_error($link)); 
    }
}

$page_content = include_template("index.php", ["lots" => $lots, "categories" => $categories]);
$layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Главная", "user_name" => $user_name, "categories" => $categories]);
print($layout_content);
?>