<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

if ($is_auth) {
    header("Location: /"); 
}

$errors = [];
$login = [];

// Проверяет, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = $_POST["login"];
    $requered_fields = ["email", "password"];

    // Проверка на заполение обязательных полей
    foreach ($requered_fields as $field) {
        if (empty($login[$field])) {
            $errors[$field] = "Поле не заполнено";
        }
    }

    // Проверка корректности email
    if (!empty($login["email"]) && (!filter_var($login["email"], FILTER_VALIDATE_EMAIL))) {
        $errors["email"] = "Введите корректный email";
    }

    // Проверка на существование пользователя с введенным email
    $user = null;
    if (empty($errors["email"])) {
        $result = find_user_by_email ($link, $login["email"]);
        $user = $result ? $result : null;
    }

    // Если форма заполнена правильно и есть пользователь с таким email
    if (!count($errors) && !is_null($user)) {
        if (password_verify($login["password"], $user["password"])) {
            $_SESSION["user"] = $user;
            header("Location: /"); 
        } else {
            $errors["password"] = "Вы ввели неверный пароль";
        }
    } else if (!count($errors) && is_null($user)) {
        $errors["email"] = "Пользователь с таким email не существует";
    }
}

// Выводит страницу с пустой формой или форму с ошибками
$page_content = include_template("login.php", [
    "login" => $login,
    "errors" => $errors
]);
$layout_content = include_template("layout.php", [
    "page_content" => $page_content,
    "user_name" => $user_name,
    "is_auth" => $is_auth,
    "user_avatar" => $user_avatar,
    "page_title" => "Вход на сайт",
    "categories" => $categories
]);
print($layout_content);

?>
