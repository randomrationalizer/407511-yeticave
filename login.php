<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

// Проверяет, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = $_POST["login"];

    $errors = [];
    $requered_fields = ["email", "password"];

    // Проверка на заполение обязательных полей
    foreach ($requered_fields as $field) {
        if (empty($login[$field])) {
            $errors[$field] = "Поле не заполнено";
        }
    }

    // Проверка корректности email
    if (isset($login["email"]) && (!filter_var($login["email"], FILTER_VALIDATE_EMAIL))) {
        $errors["email"] = "Введите корректный email";
    }

    // Проверка на существование пользователя с введенным email
    $result = find_user_by_email ($link, $login["email"]);
    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

    // Если форма заполнена правильно и есть пользователь с таким email
    if (!count($errors) && $user) {
        if (password_verify($login["password"], $user["password"])) {
            $_SESSION["user"] = $user;
        } else {
            $errors["password"] = "Вы ввели неверный пароль";
        }
    } else {
        $errors["email"] = "Пользователь с таким email не существует";
    }

    // Если форма заполнена корректно, перенаправляет на главную
    if (!count($errors)) {
        header("Location: /"); 
    }

    // Выводит форму с ошибками
    $page_content = include_template("login.php", ["login" => $login, "errors" => $errors, "categories" => $categories]);
    $layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Вход на сайт", "categories" => $categories]);
    print($layout_content);
    
} else {
    // Если форма не была отправлена
    if (isset($_SESSION["user"])) {
        header("Location: /"); 
    }

    $page_content = include_template("login.php", ["login" => [], "errors" => [], "categories" => $categories]);
    $layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Вход на сайт", "categories" => $categories]);
    print($layout_content);
}

?>
