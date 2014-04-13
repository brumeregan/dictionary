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

  # Подключаем конфиг
  include 'conf.php';


  //$_POST['submit']="register";
  //$_POST['login']="vvv";
  //$_POST['password']="vvv";

  if(isset($_POST['submit']))
  {
    if($_POST['submit'] === "login")
    {
    # Вытаскиваем из БД запись, у которой логин равняеться введенному 
    $data = mysqli_fetch_assoc(mysqli_query($con, "SELECT users_id, users_password FROM `users` WHERE `users_login`='".mysqli_real_escape_string($con, $_POST['login'])."' LIMIT 1"));
     
        # Сравниваем пароли
        if($data['users_password'] === md5(md5($_POST['password'])))
        {
          session_start();
          $_SESSION['login']=$_POST['login'];

          # Генерируем случайное число и шифруем его
          $hash = md5(generateCode(10));

          # Записываем в БД новый хеш авторизации и IP
          mysqli_query($con, "UPDATE users SET users_hash='".$hash."' WHERE users_id='".$data['users_id']."'") or die("MySQL Error: " . mysql_error());

          # Переадресовываем браузер на страницу проверки нашего скрипта
          # header("Location: check.php");
          echo "successful";
          exit;
        }
        else
        {
          //print "Вы ввели неправильный логин/пароль<br>";
          echo "error";
          exit;

        }
    }
    else if($_POST['submit'] === "register")
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
            $query = mysqli_query($con, "SELECT COUNT(users_id) FROM users WHERE users_login='".mysqli_real_escape_string($con, $_POST['login'])."'")or die ("<br>Invalid query: " . mysql_error());
            if(mysqli_fetch_row($query)[0] > 0)
            {
                $err[] = "Пользователь с таким логином уже существует в базе данных";
            }

            mysqli_free_result($query);

            # Если нет ошибок, то добавляем в БД нового пользователя
            if(count($err) == 0)
            {

                $login = $_POST['login'];

                # Убераем лишние пробелы и делаем двойное шифрование
               $password = md5(md5(trim($_POST['password'])));

                mysqli_query($con, "INSERT INTO users SET users_login='".$login."', users_password='".$password."'");
                # header("Location: login.php");
                echo "successful";
                exit();
            }
            else
            {
                echo $err[0];
            }

    }
  } 
?>













  <?php
  # Проверяем наличие в куках номера ошибки
  if (isset($errors)) {print '<h4>'.$error[$errors].'</h4>';}

  ?>
