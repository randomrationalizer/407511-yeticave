<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

$lots = [];
$search_query = trim($_GET["search"]) ?? "";

// Если задан пустой поисковый запрос или его длина меньше 3 символов
if (!isset($_GET["search"]) || empty($search_query) || mb_strlen($search_query, "utf8") < 3) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}

$search_all_results = "*" . filter_data($search_query) . "*"; // Поиск всех вхождений запроса

$cur_page = $_GET["page"] ?? 1;
$lots_on_page = 2;
$lots_count = count_lots_by_query($link, $search_all_results);

$pages_count = ceil($lots_count / $lots_on_page);
$offset = ($cur_page - 1) * $lots_on_page;

$pages = range(1, $pages_count);
$lots = get_lots_by_search_query($link, $search_all_results, $lots_on_page, $offset);

$page_content = include_template("search.php", [
    "lots" => $lots,
    "categories" => $categories,
    "search_query" => $search_query,
    "pages" => $pages,
    "pages_count" => $pages_count,
    "cur_page" => $cur_page
]);
$layout_content = include_template("layout.php", [
    "page_content" => $page_content,
    "user_name" => $user_name,
    "is_auth" => $is_auth,
    "user_avatar" => $user_avatar,
    "page_title" => "Результаты поиска",
    "categories" => $categories
]);
print($layout_content);

?>
