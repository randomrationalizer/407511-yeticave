<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

// Задан неверный id
if (!isset($_GET['id'])) {
    http_response_code(404);
    $page_content = include_template("error.php", [
        "error_header" => "404 Страница не найдена",
        "error_text" => "Данной страницы не существует на сайте.",
        "categories" => $categories
    ]);
    $layout_content = include_template("layout.php", [
        "page_content" => $page_content,
        "user_name" => $user_name,
        "is_auth" => $is_auth,
        "user_avatar" => $user_avatar,
        "page_title" => "404 Страница не найдена",
        "categories" => $categories
    ]);
    exit;
}

$id = intval($_GET['id']);
$category = get_category_by_id($link, $id);

// Категории нет в БД
if (is_null($category)) {
    http_response_code(404);
    $page_content = include_template("error.php", [
        "error_header" => "404 Страница не найдена",
        "error_text" => "Данной страницы не существует на сайте.",
        "categories" => $categories
    ]);
    $layout_content = include_template("layout.php", [
        "page_content" => $page_content,
        "user_name" => $user_name,
        "is_auth" => $is_auth,
        "user_avatar" => $user_avatar,
        "page_title" => "404 Страница не найдена",
        "categories" => $categories
    ]);
    exit;
}

$cur_page = $_GET["page"] ?? 1;
$lots_on_page = 9;
$lots_count = count_lots_in_category($link, $id);

$pages_count = ceil($lots_count / $lots_on_page);
$offset = ($cur_page - 1) * $lots_on_page;

$pages = range(1, $pages_count);

$lots_in_category = get_lots_by_category_id($link, $id, $lots_on_page, $offset);



$page_content = include_template("all_lots.php", [
    "lots" => $lots_in_category,
    "categories" => $categories,
    "current_category" => $category,
    "pages" => $pages,
    "pages_count" => $pages_count,
    "cur_page" => $cur_page
]);
$layout_content = include_template("layout.php", [
    "page_content" => $page_content,
    "user_name" => $user_name,
    "is_auth" => $is_auth,
    "user_avatar" => $user_avatar,
    "page_title" => "Все лоты категории " . $category["name"],
    "categories" => $categories
]);
print($layout_content);
?>