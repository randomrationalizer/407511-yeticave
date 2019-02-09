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
    $text = strip_tags($text);

    return $text;
};

function format_price ($price) {
    if (is_string ($price)) {
        $price = filter_data($price);
        settype($price, "integer");
    }
    $price = ceil($price);
    $price = number_format($price, 0, ".", " ") . " <b class='rub'>р</b>";

    return $price;
}
?>