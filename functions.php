<?php
require_once("mysql_helper.php");

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
};

function get_data($connect, $sql) {
    $result = mysqli_query($connect, $sql);

    if ($result) {
        $result_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " + mysqli_error($connect)); 
    }

    return $result_data;
};

function get_lot_by_id($connect, $id) {
    $lot_sql = "SELECT  `l`.`id`,  `l`.`start_date`,  `l`.`name`,  `l`.`description`,  `l`.`img_path`, `l`.`end_date`, `l`.`start_price`, `l`.`step`, `c`.`name` AS `category`, MAX(`b`.`price`) AS `current_price`
    FROM `lot` AS `l` 
    JOIN `category` AS `c` 
    ON `l`.`category_id` = `c`.`id` 
    LEFT JOIN `bid` AS `b` 
    ON `b`.`lot_id` = `l`.`id`
    WHERE `l`.`id` = " . $id;

    $result = mysqli_query($connect, $lot_sql);

    if ($result) {
        $result_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " + mysqli_error($connect)); 
    }

    return $result_data;

};

function filter_data ($text) {
    $text = htmlspecialchars($text);

    return $text;
};

function format_price ($price) {
    $price = ceil($price);
    $price = number_format($price, 0, ".", " ") . " <b class='rub'>р</b>";

    return $price;
};

function show_current_price ($start_price, $current_price) {
    return $current_price ? $current_price : $start_price;
};

function show_min_bid ($start_price, $current_price, $step) {
    return $current_price ? $current_price + $step : $start_price + $step;
};

function show_time_left ($start, $end) {
    $start_time = new DateTime($start);
    $end_time = new DateTime($end);
    $time_left = date_interval_format(date_diff($start_time, $end_time), "%H:%I");
    return $time_left;
};

function find_user_by_email ($connect, $user_email) {
    $email = mysqli_real_escape_string($connect, $user_email);
    $sql_check_email = "SELECT * FROM `user` WHERE `user`.`email` = '$email'";
    $result = mysqli_query($connect, $sql_check_email);
  
    return $result;
}

?>
