<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

$errors = [];
$new_lot = [];

// Если пользователь не авторизован, показывает 403 ошибку
if (!$is_auth) {
    http_response_code(403);
    $page_content = include_template("error.php", [
        "error_header" => "403 Доступ запрещен",
        "error_text" => "Доступ к данной странице разрешен только зарегистрированным пользователям."
    ]);
    $layout_content = include_template("layout.php", [
        "page_content" => $page_content,
        "user_name" => $user_name,
        "is_auth" => $is_auth,
        "user_avatar" => $user_avatar,
        "page_title" => "403 Доступ запрещен",
        "categories" => $categories
    ]);
    print($layout_content);

    exit;
}

// Проверяет, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new_lot = $_POST["lot"];

    $errors = [];
    $requered_fields = ["name", "description", "start_price", "step", "end_date"];
    $integer_fields = ["start_price", "step"];

    // Проверка на заполение обязательных полей
    foreach ($requered_fields as $field) {
        if (empty($new_lot[$field])) {
            $errors[$field] = "Поле не заполнено";
        }
    }

    // Проверка категории
    if (empty($new_lot["category"]) || $new_lot["category"] === "0") {
        $errors["category"] = "Выберите категорию";
    }

    // Проверка на существование категории в БД
    $sql_check_category = "SELECT * FROM `category` WHERE `category`.`id` =" . $new_lot["category"];
    if (!count(get_data($link, $sql_check_category))) {
        $errors["category"] = "Ошибка БД: выбранной категории не существует";
    };

    // Проверка значений цены и ставки
    foreach ($integer_fields as $field) {
        if (isset($new_lot[$field]) && (!filter_var($new_lot[$field], FILTER_VALIDATE_INT) || $new_lot[$field] < 0)) {
            $errors[$field] = "Введите целое положительное число";
        }
    }

    // Проверка корректности даты окончания аукциона
    $min_lot_lifetime = 86400;
    $start_date = time();
    $end_date = strtotime($new_lot["end_date"]);

    if ($end_date - $start_date < $min_lot_lifetime) {
        $errors["end_date"] = "Дата окончания торгов должна быть больше текущей хотя бы на 1 сутки";
    }

    // Проверка загруженных изображений
    if (isset($_FILES["lot-photo"]["name"]) && !empty($_FILES["lot-photo"]["name"])) {
            
        $tmp_name = $_FILES["lot-photo"]["tmp_name"];
        $filename = filter_data($_FILES["lot-photo"]["name"]); // path

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        $valid_types = ["image/jpg", "image/jpeg", "image/png"];

        if (!in_array($file_type, $valid_types, false)) {
            $errors["file"] = "Загрузите изображение в фомате jpg или png";
        } else {
            move_uploaded_file($tmp_name, "img/" . $filename);
            $new_lot["photo"] = $filename;
            $new_lot["img_path"] = "img/" . $filename;
        }
    } else {
        $errors["file"] = "Вы не загрузили файл";
    }
    
    // Если форма заполенена правильно
    if (empty($errors)) {
        $sql_add_lot = "INSERT INTO `lot` (`start_date`, `name`, `description`, `img_path`, `start_price`, `end_date`, `step`, `author_id`, `winner_id`, `category_id`) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, NULL, ?)";

        $stmt = db_get_prepare_stmt($link, $sql_add_lot, [$new_lot['name'], $new_lot['description'], $new_lot["img_path"], $new_lot["start_price"], $new_lot["end_date"], $new_lot['step'], $user_id, $new_lot['category']]);

        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        }
            
        $page_content = include_template("error.php", [
            "error_header" => "Ошибка запроса",
            "error_text" => mysqli_error($link)
        ]);
        $layout_content = include_template("layout.php", [
            "page_content" => $page_content,
            "user_name" => $user_name,
            "is_auth" => $is_auth,
            "user_avatar" => $user_avatar,
            "page_title" => "Ошибка отправки формы",
            "categories" => $categories
        ]);
        print($layout_content);

        exit;
    }
}

// Выводит страницу с пустой формой или с формой с ошибками
$page_content = include_template("add.php", [
    "lot" => $new_lot,
    "errors" => $errors,
    "categories" => $categories
]);
$layout_content = include_template("layout.php", [
    "page_content" => $page_content,
    "user_name" => $user_name,
    "is_auth" => $is_auth,
    "user_avatar" => $user_avatar,
    "page_title" => "Добавить лот",
    "categories" => $categories
]);
print($layout_content);

?>
