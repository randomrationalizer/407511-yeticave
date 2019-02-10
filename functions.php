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

function filter_data ($text) {
    $text = htmlspecialchars($text);

    return $text;
};

function format_price ($price) {
    if (is_string ($price)) {
        $price = strip_tags($price);
        $price = str_replace(",", ".", $price);
        settype($price, "float");
    }
    $price = ceil($price);
    $price = number_format($price, 0, ".", " ") . " <b class='rub'>р</b>";

    return $price;
};

function show_time_left () {
    $current_time = date_create("now");
    $end_time = date_create("tomorrow");
    $time_left = date_interval_format(date_diff($end_time, $current_time), "%H:%I");
    return $time_left;
}
?>