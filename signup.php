<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

$errors = [];
$user = [];

if ($is_auth) {
    header("Location: /"); 
}

// Проверяет, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = $_POST["signup"];
    $requered_fields = ["email", "password", "username", "contacts"];

    // Проверка на заполение обязательных полей
    foreach ($requered_fields as $field) {
        $value = trim($user[$field]);
        if (!isset($user[$field]) || empty(trim($user[$field]))) {
            $errors[$field] = "Поле не заполнено";
        }
    }

    // Проверка корректности email
    if (!empty($user["email"]) && (!filter_var($user["email"], FILTER_VALIDATE_EMAIL))) {
        $errors["email"] = "Введите корректный email";
    }

    // Проверка email на существование в БД
    $is_user_exist = find_user_by_email($link, $user["email"]);
    if (empty($errors["email"]) && !is_null($is_user_exist)) {
        $errors["email"] = "Пользователь с таким email уже существует";
    }

    // Проверка загруженного изображения
    if (isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $_FILES["avatar"]["tmp_name"]);
        $valid_types = ["image/jpg", "image/jpeg", "image/png"];

        if (!in_array($file_type, $valid_types, false)) {
            $errors["file"] = "Загрузите изображение в фомате jpg или png";
        } 
    }

    // Если форма заполенена правильно
    if (empty($errors)) {

        if (isset($_FILES["avatar"]["name"]) && !empty($_FILES["avatar"]["name"])) {
            $tmp_name = $_FILES["avatar"]["tmp_name"];
            $filename = htmlspecialchars($_FILES["avatar"]["name"]);
            move_uploaded_file($tmp_name, "img/" . $filename);

            $user["avatar"] = $filename;
            $user["avatar_path"] = "img/" . $filename;
        } else {
            $user["avatar"] = null;
            $user["avatar_path"] = null;
        }

        $password = password_hash($user["password"], PASSWORD_DEFAULT);

        $sql_add_user = "INSERT INTO `user` (`registration_date`, `email`,`username`, `password`, `avatar_path`, `contacts`) VALUES (NOW(), ?, ?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($link, $sql_add_user, [
            $user["email"],
            $user["username"],
            $password,
            $user["avatar_path"],
            $user["contacts"]
        ]);

        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: login.php");
        }
                
        $page_content = include_template("error.php", [
            "error_header" => "Ошибка запроса",
            "error_text" => mysqli_error($link),
            "categories" => $categories
        ]);
        $layout_content = include_template("layout.php", [
            "page_content" => $page_content,
            "user_name" => $user_name,
            "is_auth" => $is_auth,
            "page_title" => "Ошибка отправки формы",
            "categories" => $categories
        ]);
        print($layout_content);

        exit;
    }
}

// Выводит страницу с пустой формой или форму с ошибками
$page_content = include_template("signup.php", [
    "signup" => $user,
    "errors" => $errors,
    "categories" => $categories
]);
$layout_content = include_template("layout.php", [
    "page_content" => $page_content,
    "user_name" => $user_name,
    "is_auth" => $is_auth,
    "user_avatar" => $user_avatar,
    "page_title" => "Регистрация пользователя",
    "categories" => $categories
]);
print($layout_content);

?>
