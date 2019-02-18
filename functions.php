<?php
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
}
?>