<?php

$new_bid = $_POST;

// Проверка на заполение
if (empty($new_bid["cost"])) {
	$errors["cost"] = "Введите вашу ставку";
}

// Проверка формата ставки
if (!empty($new_bid["cost"]) && (!filter_var($new_bid["cost"], FILTER_VALIDATE_INT) || $new_bid["cost"] < 0)) {
	$errors["cost"] = "Введите целое положительное число";
}

// Проверка допустимого значения ставки
if (!empty($new_bid["cost"]) && ($new_bid["cost"] < $min_bid)) {
	$errors["cost"] = "Ваша ставка не должна быть меньше " . $min_bid . " рублей";
}

if (empty($errors)) {
	$sql_add_bid = "INSERT INTO `bid` (`date`, `price`, `user_id`, `lot_id`) VALUES (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql_add_bid, [$new_bid["cost"], $user_id, $lot["id"]]);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        header("Location: lot.php?id=" . $lot['id']);
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

?>