<?php
# Подключаем конфиг 
include 'conf.php'; 

if(isset($_POST['submit'])) 
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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign up page</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
    .well {
        margin-top: 240px;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="well span4 offset4">
            <legend>Регистрация на сайте</legend>
            <form method="post" action="" accept-charset="UTF-8" class="form-horizontal">
                <div class="control-group">
                <input type="text" class="span4" placeholder="Ваш логин" name="login">
                </div>
                <div class="control-group">
                <input type="password" class="span4" placeholder="Ваш пароль" name="password">
                </div>
                <button type="submit" name="submit" class="btn btn-block btn-success">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</div>


<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>



  <?php
    if (isset($err)) {
      print "<b>При регистрации произошли следующие ошибки:</b><br>"; 
      foreach($err AS $error) 
      { 
        print $error."<br>"; 
      }   
    }
  ?>
