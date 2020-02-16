<?php

if (!empty($_COOKIE['sid'])) {
    // check session id in cookies
    session_id($_COOKIE['sid']);
}
session_start();
require_once './classes/Auth.class.php';

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PHP Ajax Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
  </head>

  <body>

    <div class="container">

      <?php if (Auth\User::isAuthorized()): ?>
    
      <h1>Your are already registered!</h1>

      <form class="ajax" method="post" action="./ajax.php">
          <input type="hidden" name="act" value="logout">
          <div class="form-actions">
              <button class="btn btn-large btn-primary" type="submit">Logout</button>
          </div>
      </form>
      <?php
        $host = 'localhost';  // Хост, у нас все локально
        $user = 'testdb';    // Имя созданного вами пользователя
        $pass = 'testdb'; // Установленный вами пароль пользователю
        $db_name = 'testdb';   // Имя базы данных
        $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой

        // Ругаемся, если соединение установить не удалось
        if (!$link) {
          echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
          exit;
        }

        $sql = mysqli_query($link, 'SELECT * FROM `users`');


        while ($result = mysqli_fetch_array($sql)) {

          $id = $result['id'];
          $username = $result['username'];
          $last_visit_time = $result['status'];


          //get timestamp now
          date_default_timezone_set ('Europe/Kiev');
          $cur_date = strtotime(date("Y-m-d H:i:s"));
          //echo "Текущая дата ($cur_date) </br>";
          
          $get_time_status = strtotime($result['status']);
          //echo $get_time_status."</br>";
          $get_time_status = $cur_date - $get_time_status; // (24 * 60 * 60)
          //echo "разница времени $get_time_status"."</br>";
          if ($get_time_status < 300){
            $on_off = "<span class='badge badge-pill badge-success' style='color:white;'>online</span>"."Пользователь: $username ";
            echo $on_off;
          } else {
            $on_off = "<span class='badge badge-pill badge-danger' style='color:red;'>offline</span>"."Пользователь: $username "."Последний раз был в сети: "."$last_visit_time</br>";
            echo $on_off;
          }
          
          //echo $on_off;
          
        }
      ?>
      <hr>
      <div>
        <button type="button" class="btn btn-info">
          <a href="game" style="color:black;">Очень интересная кнопка</a>
        </button>
      </div>

      <?php else: ?>
          
      <form class="form-signin ajax" method="post" action="./ajax.php">
        <div class="main-error alert alert-error hide"></div>

        <h2 class="form-signin-heading">Please sign up</h2>
        <input name="username" type="text" class="input-block-level" placeholder="Username" autofocus>
        <input name="password1" type="password" class="input-block-level" placeholder="Password">
        <input name="password2" type="password" class="input-block-level" placeholder="Confirm password">
        <input type="hidden" name="act" value="register">
        <button class="btn btn-large btn-primary" type="submit">Register</button>
        <div class="alert alert-info" style="margin-top:15px;">
            <p>Already have account? <a href="index.php">Sign In.</a>
        </div>
      </form>

      <?php endif; ?>

    </div> <!-- /container -->

    <script src="./vendor/jquery-2.0.3.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/ajax-form.js"></script>

  </body>
</html>
