<?php

require_once("connection.php");
require_once("functions.php");
require_once("data.php");

unset($_SESSION["user"]);
header("Location: /");

?>
