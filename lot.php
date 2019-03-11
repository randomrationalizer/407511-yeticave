<?php

require_once("connection.php");
require_once("functions.php");
require_once("data.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $lot = get_lot_by_id($link, $id);
    
    if (!is_null($lot['id'])) {

        $current_price = $lot["current_price"] ? $lot["current_price"] : $lot["start_price"];
        $min_bid = $current_price + $lot["step"];
        $errors = [];

        $bids = get_lot_bids($link, $lot['id']);

        $current_time = time();
        $is_expired = strtotime($lot["end_date"]) < $current_time;

        $last_bid_autor = $bids? $bids[0]["user_id"] : null;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($lot["author_id"] != $user_id) {
                require_once("bid.php");
            }
        }

        $page_content = include_template("lot.php", [
            "lot" => $lot,
            "is_auth" => $is_auth,
            "is_expired" => $is_expired,
            "user_id" => $user_id,
            "last_bid_autor" => $last_bid_autor,
            "current_price" => $current_price,
            "min_bid" => $min_bid,
            "errors" => $errors,
            "bids" => $bids
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