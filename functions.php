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
    $lot_sql = "SELECT  `l`.`id`,  `l`.`start_date`,  `l`.`name`,  `l`.`description`,  `l`.`img_path`, `l`.`end_date`, `l`.`start_price`, `l`.`step`, `l`.`author_id`, `c`.`name` AS `category`, MAX(`b`.`price`) AS `current_price`
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

function get_lot_bids ($connect, $id) {
    $sql_lot_bids = "SELECT `b`.`date`, `b`.`price`, `b`.`user_id`, `b`.`lot_id`, `u`.`username` 
    FROM  `bid` AS `b` 
    JOIN `user` AS `u` 
    ON `b`.`user_id` = `u`.`id` 
    WHERE `b`.`lot_id` = " . $id . " ORDER BY `b`.`date` DESC";

    $result = mysqli_query($connect, $sql_lot_bids);

    if ($result) {
        $result_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " + mysqli_error($connect)); 
    }

    return $result_data;

}

function filter_data ($text) {
    $text = htmlspecialchars($text);

    return $text;
};

function format_price ($price) {
    $price = ceil($price);
    $price = number_format($price, 0, ".", " ") . " <b class='rub'>р</b>";

    return $price;
};

function show_time_left ($start, $end) {
    $start_time = new DateTime($start);
    $end_time = new DateTime($end);
    $time_left = date_interval_format(date_diff($start_time, $end_time), "%H:%I");
    return $time_left;
};

function show_bid_age ($bid_date) {
    $cur_date = date_create();
    $bid_time = date_create($bid_date);
    $diff = date_diff($bid_time, $cur_date);

    $days_count = date_interval_format($diff, "%d");
    $days = ($days_count !== 0) ? $days_count . " дней" : "";
    $hours_count = date_interval_format($diff, "%h");
    $hours = ($hours_count !== 0) ? $hours_count . " часов" : "";
    $minutes_count = date_interval_format($diff, "%i");
    $minutes = ($minutes_count !== 0) ? $minutes_count . " минут" : "";
    $bid_age = $days . " " . $hours . " " . $minutes . " " . " назад";
    return $bid_age;
};

function find_user_by_email ($connect, $user_email) {
    $email = mysqli_real_escape_string($connect, $user_email);
    $sql_check_email = "SELECT * FROM `user` WHERE `user`.`email` = '$email'";
    $result = mysqli_query($connect, $sql_check_email);
  
    return $result;
}

?>
