<?php
require_once("connection.php");
require_once("functions.php");
require_once("data.php");

$errors = [];
$new_lot = [];

// Проверяет, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
    if (empty($new_lot["category"]) || $new_lot["category"] == "0") {
        $errors["category"] = "Выберите категорию";
    }

    // Проверка значений цены и ставки
    foreach ($integer_fields as $field) {
        if (isset($new_lot[$field]) && (!filter_var($new_lot[$field], FILTER_VALIDATE_INT) || $new_lot[$field] < 0)) {
            $errors[$field] = "Введите целое положительное число";
        }
    }

    // Проверка корректности даты окончания аукциона
    if (strtotime($new_lot["end_date"]) < strtotime('tomorrow')) {
        $errors["end_date"] = "Введена некорректная дата завершения аукциона";
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

    // Если найдены ошибки в заполнении формы
    if (count($errors)) {
        $page_content = include_template("add.php", ["lot" => $new_lot, "errors" => $errors, "categories" => $categories]);
        $layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Добавить лот", "user_name" => $user_name, "categories" => $categories]);
        print($layout_content);
    } else {
        // Если форма заполенена правильно
        $sql_add_lot = "INSERT INTO `lot` (`start_date`, `name`, `description`, `img_path`, `start_price`, `end_date`, `step`, `author_id`, `winner_id`, `category_id`) VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, NULL, ?)";

        $stmt = db_get_prepare_stmt($link, $sql_add_lot, [$new_lot['name'], $new_lot['description'], $new_lot["img_path"], $new_lot["start_price"], $new_lot["end_date"], $new_lot['step'], $new_lot['category']]);

        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            $page_content = include_template("error.php", ["error_header" => "Ошибка запроса", "error_text" => mysqli_error($link)]);
            $layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Ошибка отправки формы", "user_name" => $user_name, "categories" => $categories]);
            print($layout_content);
        }
    }
} else {
    // Если форма не отправлена, выводит страницу с пустой формой
    $page_content = include_template("add.php", ["lot" => $new_lot, "errors" => $errors, "categories" => $categories]);
    $layout_content = include_template("layout.php", ["page_content" => $page_content, "page_title" => "Добавить лот", "user_name" => $user_name, "categories" => $categories]);
    print($layout_content);
}

?>