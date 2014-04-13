<?php
# настройки
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'dict_admin');
define ('DB_PASSWORD', 'eureka');
define ('DB_NAME', 'dict_db');
#$con = mysqli_connect($DB_HOST,$DB_LOGIN, $DB_PASSWORD,$DB_NAME);
$con = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysqli_query($con, "set names utf8") or die ("<br>Invalid query: " . mysql_error());
mysqli_select_db($con, DB_NAME) or die ("<br>Invalid query: " . mysql_error());

# массив ошибок
$error[0] = 'Я вас не знаю';
$error[1] = 'Включи куки';
$error[2] = 'Тебе сюда нельзя';
?>
