<?php
require_once("functions.php");
require_once("data.php");

$page_content = include_template("index.php", ["lots" => $lots, "categories" => $categories]);
$layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Главная", "user_name" => $user_name, "categories" => $categories]);
print($layout_content);
?>