<?php
$link = mysqli_connect("localhost", "root", "", "407511-yeticave");

if (!$link) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");

?>