<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

$errors = [];

$page_content = include_template("login.php", ["errors" => $errors, "categories" => $categories]);
$layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Вход на сайт", "user_name" => $user_name, "categories" => $categories]);
print($layout_content);

?>