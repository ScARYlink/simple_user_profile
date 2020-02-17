<?php
	$toEmail = "scarylink25@gmail.com";
	$mailHeaders = "From: " . $_POST["userName"] . "<". $_POST["userEmail"] .">\r\n";
	if(mail($toEmail, $_POST["subject"], $_POST["content"], $mailHeaders)) {
	print "<p class='success' style='background-color: #12CC1A;border:#0FA015 1px solid;padding: 5px 10px;color: #FFFFFF;border-radius:4px;'>Contact Mail Sent.</p>";
	} else {
	print "<p class='Error' style='background-color: #FF6600;border:#AA4502 1px solid;padding: 5px 10px;color: #FFFFFF;border-radius:4px;'>Problem in Sending Mail.</p>";
	}
