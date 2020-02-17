<?php

if (!empty($_COOKIE['sid'])) {
    // check session id in cookies
    session_id($_COOKIE['sid']);
}

session_start();
require_once 'classes/Auth.class.php';

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Andrii Kostiukevych</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
      .form_mail_wrapper{width:900px;}
      #frmContact {border-top:#F0F0F0 2px solid;background:#FAF8F8;padding:2px;}
      #frmContact div{margin-bottom: 2px}
      #frmContact div label{margin-left: 2px}
      .demoInputBox{padding:10px; border:#F0F0F0 1px solid; border-radius:4px;}
      .info{font-size:.8em;color: #FF6600;letter-spacing:2px;padding-left:5px;}
      </style>
  </head>

  <body>

    <div class="container">

      <?php if (Auth\User::isAuthorized()): ?>

          <?php 
            $host = 'localhost';  // Хост, у нас все локально
            $user = '';    // Имя созданного вами пользователя
            $pass = ''; // Установленный вами пароль пользователю
            $db_name = '';   // Имя базы данных
            $link = mysqli_connect($host, $user, $pass, $db_name);
          
            $user_true = mysqli_query($link, "SELECT username FROM `users` WHERE id=".$_SESSION["user_id"]."     ");
          while ($result2 = mysqli_fetch_array($user_true)) {
              $username = $result2['username'];
              echo "<h1>Your are welcome<span style='font-weight: bold'> ". $username." </span>!</h1>";
          }
          ?>
        
      <form class="ajax" method="post" action="./ajax.php">
          <input type="hidden" name="act" value="logout">
          <div class="form-actions">
              <button class="btn btn-large btn-primary" type="submit">Logout</button>
          </div>
      </form>
      <p style="color:blue;">Список пользователей с их статусом:</p>

      <?php
        $host = 'localhost';  // Хост, у нас все локально
        $user = '';    // Имя созданного вами пользователя
        $pass = ''; // Установленный вами пароль пользователю
        $db_name = '';   // Имя базы данных
        $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой

        // Проблемы с соединением, проверка
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
        	date_default_timezone_set ('Europe/Moscow');
        	$cur_date = strtotime(date("Y-m-d H:i:s"));
        	//echo "Текущая дата ($cur_date) </br>";
        	
        	$get_time_status = strtotime($result['status']);
        	//echo $get_time_status."</br>";
        	$get_time_status = $cur_date - $get_time_status; // (24 * 60 * 60)
        	//echo "разница времени $get_time_status"."</br>";
        	if ($get_time_status < 300){
        		$on_off = "<span class='badge badge-pill badge-success' style='color:white;'>online</span>"."Пользователь: $username </br>";
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
      </div></br></br>
    <!-- this email FORM-->
      <div class="form_mail_wrapper">
        <span>Обратная связь не будет работать, т.к. необходимо оплачивать услуги хостинга. Поэтому, чтобы проверить, что это ajax/валидацию достаточно заполнить данные и нажать кнопку Send</span>
        <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script>
        function sendContact() {
          var valid;  
          valid = validateContact();
          if(valid) {
            jQuery.ajax({
            url: "classes/contact_mail.php",
            data:'userName='+$("#userName").val()+'&userEmail='+$("#userEmail").val()+'&subject='+$("#subject").val()+'&content='+$(content).val(),
            type: "POST",
            success:function(data){
            $("#mail-status").html(data);
            },
            error:function (){}
            });
          }
        }

        function validateContact() {
          var valid = true; 
          $(".demoInputBox").css('background-color','');
          $(".info").html('');
          
          if(!$("#userName").val()) {
            $("#userName-info").html("(required)");
            $("#userName").css('background-color','#FFFFDF');
            valid = false;
          }
          if(!$("#userEmail").val()) {
            $("#userEmail-info").html("(required)");
            $("#userEmail").css('background-color','#FFFFDF');
            valid = false;
          }
          if(!$("#userEmail").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
            $("#userEmail-info").html("(invalid)");
            $("#userEmail").css('background-color','#FFFFDF');
            valid = false;
          }
          if(!$("#subject").val()) {
            $("#subject-info").html("(required)");
            $("#subject").css('background-color','#FFFFDF');
            valid = false;
          }
          if(!$("#content").val()) {
            $("#content-info").html("(required)");
            $("#content").css('background-color','#FFFFDF');
            valid = false;
          }
          
          return valid;
        }
        </script>
        <div id="frmContact">
          <div id="mail-status"></div>
            <div>
              <label style="padding-top:20px;">Name</label>
              <span id="userName-info" class="info"></span><br/>
              <input type="text" name="userName" id="userName" class="demoInputBox">
            </div>
            <div>
              <label>Email</label>
              <span id="userEmail-info" class="info"></span><br/>
              <input type="text" name="userEmail" id="userEmail" class="demoInputBox">
            </div>
            <div>
              <label>Subject</label> 
              <span id="subject-info" class="info"></span><br/>
              <input type="text" name="subject" id="subject" class="demoInputBox">
            </div>
            <div>
              <label>Content</label> 
              <span id="content-info" class="info"></span><br/>
              <textarea name="content" id="content" class="demoInputBox" cols="60" rows="3"></textarea>
            </div>
            <div>
              <button name="submit" class="btnAction btn btn-success" onClick="sendContact();">Send</button>
            </div>
        </div>
      </div>

      <?php else: ?>

      <form class="form-signin ajax" method="post" action="./ajax.php">
        <div class="main-error alert alert-error hide"></div>

        <h2 class="form-signin-heading">Please sign in</h2>
        <input name="username" type="text" class="input-block-level" placeholder="Username" autofocus>
        <input name="password" type="password" class="input-block-level" placeholder="Password">
        <label class="checkbox">
          <input name="remember-me" type="checkbox" value="remember-me" checked> Remember me
        </label>
        <input type="hidden" name="act" value="login">
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
    
        <div class="alert alert-info" style="margin-top:15px;">
            <p>Not have an account? <a href="register.php">Register it.</a>
        </div>
      </form>

      <?php endif; ?>

    </div> <!-- /container -->
    <script src="./vendor/jquery-2.0.3.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/ajax-form.js"></script>

  </body>
</html>
