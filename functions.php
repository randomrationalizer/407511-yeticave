<?php
require_once("mysql_helper.php");

/**
 * Подключает файл шаблона и передает в него данные. Генерирует html документ.
 *
 * @param $name string Имя файла шаблона
 * @param array $data Данные для вставки в шаблон
 *
 * @return string Сгенерированный html документ
 */
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

/**
 * Выполняет запрос к БД и возвращает массив данных 
 *
 * @param $connect mysqli Ресурс соединения
 * @param $sql string SQL запрос
 *
 * @return array Ассоциативный массив данных
 */
function get_data($connect, $sql) {
    $result = mysqli_query($connect, $sql);

    if ($result) {
        $result_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " . mysqli_error($connect)); 
    }

    return $result_data;
};

/**
 * Выполняет запрос к БД и возвращает лот по его id
 *
 * @param $connect mysqli Ресурс соединения
 * @param $id int Идентификатор лота
 *
 * @return array Данные лота
 */
function get_lot_by_id($connect, $id) {
    $result_data = [];
    $lot_sql = "SELECT  `l`.`id`,  `l`.`start_date`,  `l`.`name`,  `l`.`description`,  `l`.`img_path`, `l`.`end_date`, `l`.`start_price`, `l`.`step`, `l`.`author_id`, `c`.`name` AS `category`, MAX(`b`.`price`) AS `current_price`
    FROM `lot` AS `l` 
    JOIN `category` AS `c` 
    ON `l`.`category_id` = `c`.`id` 
    LEFT JOIN `bid` AS `b` 
    ON `b`.`lot_id` = `l`.`id` 
    WHERE `l`.`id` = ? 
    GROUP BY `l`.`id`";

    $stmt = db_get_prepare_stmt($connect, $lot_sql, [$id]);
    $res = mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $result_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " . mysqli_error($connect)); 
    }

    return $result_data;

};

/**
 * Выполняет запрос к БД и возвращает все ставки для лота с указаным id в порядке убывания новизны
 *
 * @param $connect mysqli Ресурс соединения
 * @param $id int Идентификатор лота
 *
 * @return array Массив с данными ставок
 */
function get_lot_bids ($connect, $id) {
    $result_data = [];
    $sql_lot_bids = "SELECT `b`.`date`, `b`.`price`, `b`.`user_id`, `b`.`lot_id`, `u`.`username` 
    FROM  `bid` AS `b` 
    JOIN `user` AS `u` 
    ON `b`.`user_id` = `u`.`id` 
    WHERE `b`.`lot_id` = ? 
    ORDER BY `b`.`date` DESC";

    $stmt = db_get_prepare_stmt($connect, $sql_lot_bids, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $result_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " . mysqli_error($connect)); 
    }

    return $result_data;

}

/**
 * Преобразует html симоволы в строке в безопасные значения
 *
 * @param $text string Исходная строка
 *
 * @return string Преобразованная строка
 */
function filter_data ($text) {
    $text = htmlspecialchars($text);

    return $text;
};

/**
 * Преобразует цену к требуемому формату, добавляет символ рубля
 *
 * @param $price int/float Исходное значение цены
 *
 * @return string Строка с отформатированной ценой
 */
function format_price ($price) {
    $price = ceil($price);
    $price = number_format($price, 0, ".", " ") . " <b class='rub'>р</b>";

    return $price;
};

/**
 * Возвращает строку со временем, оставшимся до конца размещения лота
 *
 * @param $start stringt Дата начала размещения лота
 *
 * @param $start stringt Дата окончания размещения лота
 *
 * @return string Строка с отформатированным интервалом времени
 */
function show_time_left ($start, $end) {
    $start_time = new DateTime($start);
    $end_time = new DateTime($end);
    $time_left = date_interval_format(date_diff($start_time, $end_time), "%H:%I");
    return $time_left;
};

/**
 * Возвращает строку с временным промежутком с момента размещения ставки
 *
 * @param $bid_date string Время добавления ставки
 *
 * @return string Строка с отформатированным с временным промежутком
 */
function show_bid_age ($bid_date) {
    $cur_date = date_create();
    $bid_time = date_create($bid_date);
    $diff = date_diff($bid_time, $cur_date);

    $days_count = date_interval_format($diff, "%d");
    $days = ($days_count !== "0") ? $days_count . " дней" : "";
    $hours_count = date_interval_format($diff, "%h");
    $hours = ($hours_count !== "0") ? $hours_count . " часов" : "";
    $minutes_count = date_interval_format($diff, "%i");
    $minutes = ($minutes_count !== "0") ? $minutes_count . " минут" : "";
    $bid_age = $days . " " . $hours . " " . $minutes . " " . " назад";
    return $bid_age;
};

/**
 * Выполняет запрос к БД и возвращаетс данные пользователя по Email
 *
 * @param $connect mysqli Ресурс соединения
 * @param $user_email string Email пользователя
 *
 * @return array Массив с данными пользователя
 */
function find_user_by_email ($connect, $user_email) {
    $sql_check_email = "SELECT * FROM `user` WHERE `user`.`email` = ?";
    $stmt = db_get_prepare_stmt($connect, $sql_check_email, [$user_email]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $result_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " . mysqli_error($connect)); 
    }

    return $result_data;
}

?>
