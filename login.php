<?php
  # Функция для генерации случайной строки 
  function generateCode($length=6) { 
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"; 
    $code = ""; 
    $clen = strlen($chars) - 1;   
    while (strlen($code) < $length) { 
        $code .= $chars[mt_rand(0,$clen)];   
    } 
    return $code; 
  } 
  
  # Если есть куки с ошибкой то выводим их в переменную и удаляем куки
  if (isset($_COOKIE['errors'])){
      $errors = $_COOKIE['errors'];
      setcookie('errors', '', time() - 60*24*30*12, '/');
  }

  # Подключаем конфиг
  include 'conf.php';

  if(isset($_POST['submit'])) 
  { 
    if($_POST['submit'] === "login_form") {
    # Вытаскиваем из БД запись, у которой логин равняеться введенному 
    $data = mysql_fetch_assoc(mysql_query("SELECT users_id, users_password FROM `users` WHERE `users_login`='".mysql_real_escape_string($_POST['login'])."' LIMIT 1")); 
     
    # Соавниваем пароли 
    if($data['users_password'] === md5(md5($_POST['password']))) 
    { 
      # Генерируем случайное число и шифруем его 
      $hash = md5(generateCode(10)); 
           
      # Записываем в БД новый хеш авторизации и IP 
      mysql_query("UPDATE users SET users_hash='".$hash."' WHERE users_id='".$data['users_id']."'") or die("MySQL Error: " . mysql_error()); 
       
      # Ставим куки 
      setcookie("id", $data['users_id'], time()+60*60*24*30); 
      setcookie("hash", $hash, time()+60*60*24*30); 
       
      # Переадресовываем браузер на страницу проверки нашего скрипта 
      header("Location: check.php"); exit(); 
    } 
    else 
    { 
      print "Вы ввели неправильный логин/пароль<br>"; 
    }
    }
    else if($_POST['submit'] === "register_form")
    {
        $err = array();

            # проверям логин
           if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
            {
                $err[] = "Логин может состоять только из букв английского алфавита и цифр";
            }

            if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
            {
                $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
            }

            # проверяем, не сущестует ли пользователя с таким именем
          $query = mysql_query("SELECT COUNT(users_id) FROM users WHERE users_login='".mysql_real_escape_string($_POST['login'])."'")or die ("<br>Invalid query: " . mysql_error());
            if(mysql_result($query, 0) > 0)
            {
                $err[] = "Пользователь с таким логином уже существует в базе данных";
            }


            # Если нет ошибок, то добавляем в БД нового пользователя
           if(count($err) == 0)
            {

                $login = $_POST['login'];

                # Убераем лишние пробелы и делаем двойное шифрование
               $password = md5(md5(trim($_POST['password'])));

                mysql_query("INSERT INTO users SET users_login='".$login."', users_password='".$password."'");
                header("Location: login.php"); exit();
            }

    }
  } 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login page</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
    .well {
        margin-top: 240px;
    }
    .hidden {
        display: none;
    }
    </style>
</head>
<body>

<div class="container" id="login">
    <div class="row">
        <div class="well span4 offset4">
            <legend>Авторизация на сайте</legend>
            <form method="post" action="" accept-charset="UTF-8" class="form-horizontal" name="login_form">
                <div class="control-group">
                <input type="text" class="span4" placeholder="Ваш логин" name="login">
                </div>
                <div class="control-group">
                <input type="password" class="span4" placeholder="Ваш пароль" name="password">
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-success" value="login_form">Авторизоваться</button>
                    </div>
                    <div class="span6">
                    	<button class="btn btn-lg btn-primary btn-block" onclick="showRegistration();return false;">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container hidden" id="register">
    <div class="row">
        <div class="well span4 offset4">
            <legend>Регистрация на сайте</legend>
            <form method="post" action="" accept-charset="UTF-8" class="form-horizontal" name="register_form">
                <div class="control-group">
                <input type="text" class="span4" placeholder="Ваш логин" name="login">
                </div>
                <div class="control-group">
                <input type="password" class="span4" placeholder="Ваш пароль" name="password">
                </div>
                <button type="submit" name="submit" class="btn btn-block btn-success" value="register_form">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/login.js"></script>
</body>
</html>












  <?php
  # Проверяем наличие в куках номера ошибки
  if (isset($errors)) {print '<h4>'.$error[$errors].'</h4>';}

  ?>
