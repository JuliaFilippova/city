<?php

$files = isset($_FILES['files']) ? $_FILES['files'] : [];

$filesCount = isset($files['name']) && is_array($files['name']) ? count($files['name']) : 0;

$uploadedFiles = [];

if (!is_dir('uploads')) {
    mkdir('uploads');
}

for ($i = 0; $i < $filesCount; $i++) {
    $fileName = implode(
        '',
        array_map('trim', explode(' ', $files['name'][$i]))
    );

    $filePath = 'uploads' . DIRECTORY_SEPARATOR . time() . $fileName;

    move_uploaded_file($files['tmp_name'][$i], $filePath);

    $uploadedFiles[] = [
        'name' => $files['name'][$i],
        'path' => $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR . 'mail' . DIRECTORY_SEPARATOR . $filePath
    ];
}

$recepient = "yulia.filippovaa@yandex.ru";
$siteName  = "CityCooling";

$companyName = trim($_POST["company_name"]);
$firstName   = trim($_POST["first_name"]);
$lastName    = trim($_POST["last_name"]);
$email       = trim($_POST["email"]);
$phone       = trim($_POST["phone"]);
$zipCode     = trim($_POST["zip_code"]);

$files = trim($_FILES["files"]);

$pagetitle = "Заявка с сайта {$siteName}";

$message = "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<title>{$pagetitle}</title>
</head>
<body>";

$message .= "
<p>Компания: {$companyName}</p>
<p>Имя: {$firstName}</p>
<p>Телефон: {$phone}</p>
<p>Email: {$email}</p>
<p>Индекс: {$zipCode}</p>
";

if (count($uploadedFiles) > 0) {
    $message .= "\n Файлы:";

    foreach ($uploadedFiles as $fileInfo) {
        $url = "<a href='{$fileInfo['path']}' target='_blank' download>{$fileInfo['name']}</a>";

        $message .= "\n" . $url;
    }
}

$message .= "</body></html>";

mail($recepient, $pagetitle, $message, "Content-type: text/html; charset=\"utf-8\"\n From: $recepient");