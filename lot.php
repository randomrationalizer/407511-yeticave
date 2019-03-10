<?php

require_once("connection.php");
require_once("functions.php");
require_once("data.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $lot = get_lot_by_id($link, $id);
    if (!is_null($lot['id'])) {
        $page_content = include_template("lot.php", [
            "lot" => $lot,
            "is_auth" => $is_auth
        ]);
        $layout_content = include_template("layout.php", [
            "page_content" => $page_content,
            "user_name" => $user_name,
            "is_auth" => $is_auth,
            "user_avatar" => $user_avatar,
            "page_title" => $lot["name"],
            "categories" => $categories
        ]);

    } else {
        http_response_code(404);
        $page_content = include_template("error.php", [
            "error_header" => "404 Страница не найдена",
            "error_text" => "Данной страницы не существует на сайте."
        ]);
        $layout_content = include_template("layout.php", [
            "page_content" => $page_content,
            "user_name" => $user_name,
            "is_auth" => $is_auth,
            "user_avatar" => $user_avatar,
            "page_title" => "404 Страница не найдена",
            "categories" => $categories
        ]);
    }
}

print($layout_content);
?>