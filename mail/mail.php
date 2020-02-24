<?php

$recepient = "yulia.filippovaa@yandex.ru";
$siteName = "CityCooling";

$companyName = trim($_POST["company_name"]);
$firstName = trim($_POST["first_name"]);
$lastName = trim($_POST["last_name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$zipСode = trim($_POST["zip_code"]);

$files = trim($_FILES["files"]);

$message = "Компания: $companyName Имя: $firstName \nТелефон: $phone \nEmail: $email \nИндекс: $zipСode \nФайл: $files";

$pagetitle = "Заявка с сайта \"$siteName\"";
mail($recepient, $pagetitle, $message, "Content-type: text/plain; charset=\"utf-8\"\n From: $recepient");

?>