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
    
    $lot_sql = "SELECT  `l`.`id`,  `l`.`start_date`,  `l`.`name`,  `l`.`description`,  `l`.`img_path`, `l`.`end_date`, `l`.`start_price`, `l`.`step`, `l`.`author_id`, `c`.`name` AS `category`, MAX(`b`.`price`) AS `current_price`
    FROM `lot` AS `l` 
    JOIN `category` AS `c` 
    ON `l`.`category_id` = `c`.`id` 
    LEFT JOIN `bid` AS `b` 
    ON `b`.`lot_id` = `l`.`id` 
    WHERE `l`.`id` = ? 
    GROUP BY `l`.`id`";

    $stmt = db_get_prepare_stmt($connect, $lot_sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $result_data = [];

    if ($result) {
        $result_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
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

};

/**
 * Выполняет запрос к БД и возвращает данные категории по её id
 *
 * @param $connect mysqli Ресурс соединения
 * @param $id int Идентификатор категории
 *
 * @return array Массив с данными категории
 */
function get_category_by_id($connect, $id) {

    $category_sql = "SELECT * FROM `category` WHERE `id` = ?;";

    $stmt = db_get_prepare_stmt($connect, $category_sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $result_data = [];
     
    if ($result) {
        $result_data = mysqli_fetch_assoc($result);
    }

    return $result_data;
};

/**
 * Выполняет запрос к БД и возвращает массив открытых лотов, принадлежащих категории
 * @param $connect mysqli Ресурс соединения
 * @param $id int Идентификатор категории
 * @param $items_on_page int Количество лотов на странице
 * @param $offset int Смещение для пагинации
 *
 * @return array Массив лотов
 */
function get_lots_by_category_id ($connect, $id, $items_on_page, $offset) {
    
    $lots_in_category_sql = "SELECT `l`.`id`, `l`.`name`, `c`.`name` AS `category`, `l`.`start_price`, `l`.`img_path`, `l`.`start_date`, `l`.`end_date`
        FROM `lot` AS `l`
        JOIN `category` AS `c` 
        ON l.`category_id` = `c`.`id`
        WHERE `l`.`winner_id` IS NULL
        AND `l`.`end_date` > NOW() 
        AND `c`.`id` = ?
        ORDER BY `l`.`start_date` DESC LIMIT ? OFFSET ?;";


    $stmt = db_get_prepare_stmt($connect, $lots_in_category_sql, [$id, $items_on_page, $offset]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $result_data = [];

    if ($result) {
        $result_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $result_data;
};

/**
 * Возвращает количество открытых лотов в категории
 * @param $connect mysqli Ресурс соединения
 * @param $id int Идентификатор категории
 *
 * @return array Массив лотов
 */

function count_lots_in_category ($connect, $id) {

    $lots_in_category_sql = "SELECT COUNT(*) AS `cnt`
        FROM `lot` 
        WHERE `lot`.`winner_id` IS NULL
        AND `lot`.`end_date` > NOW() 
        AND `lot`.`category_id` = ?;";
    
    $stmt = db_get_prepare_stmt($connect, $lots_in_category_sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items_count = null;

    if ($result) {
        $items_count= mysqli_fetch_assoc($result)['cnt'];
    }

    return $items_count;
};

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
 * @param $end stringt Дата окончания размещения лота
 *
 * @return string Строка с интервалом времени
 */
function show_time_left ($end) {
    $diff = strtotime($end) - time();
    $time_left = "00:00";
    if ($diff > 0) {
        $hours = floor($diff / 3600);
        $minutes = floor(($diff - $hours * 3600) / 60);
        $time_left = $hours . ":" . $minutes;
    }
    
    return $time_left;
};

/**
 * Возвращает строку со модификатором класса для таймера лота, торги по которому завершаются или уже завершены
 *
 * @param $end stringt Дата окончания размещения лота
 *
 * @return string Строка с именем классом
 */
function show_finishing_class ($end) {
    $classname = "";
    $diff = strtotime($end) - time();
    if ($diff < 3600) {
        $classname = "timer--finishing";
    }
    return $classname;
};

/**
 * Возвращает строку с временным промежутком с момента размещения ставки
 *
 * @param $bid_date string Время добавления ставки
 *
 * @return string Строка с отформатированным с временным промежутком
 */
function show_bid_age ($bid_date) {
    $diff = time() - strtotime($bid_date);
    $bid_age = "";
    if ($diff < 60) {
        $bid_age = $diff . " с назад";
    } else if ($diff > 60 && $diff < 3600) {
        $bid_age = floor($diff / 60) . " м назад";
    } else if ($diff > 3600 && $diff < 86400) {
        $hours = floor($diff / 3600);
        $minutes = floor(($diff - $hours * 3600) / 60);
        $bid_age = $hours . " ч " . $minutes . " м назад";
    } else if ($diff > 86400) {
        $days = floor($diff / 86400);
        $hours = floor(($diff - $days * 86400) / 3600);
        $bid_age = $days . " д" . $hours . " ч назад";
    }

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
    
    $result_data = null;

    if ($result) {
        $result_data = mysqli_fetch_assoc($result);
    }

    return $result_data;
}

?>
