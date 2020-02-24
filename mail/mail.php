<!-- <?php

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

?> -->

<?php

function multi_attach_mail($to, $subject, $message, $senderEmail, $senderName, $files = [])
{
    $from    = $senderName . " <" . $senderEmail . ">";
    $headers = "From: $from";

    // Boundary
    $semi_rand     = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // Headers for attachment
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    // Multipart boundary
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

    // Preparing attachment
    if (!empty($files)) {
        for ($i = 0; $i < count($files); $i++) {
            if (is_file($files[$i])) {
                $file_name = basename($files[$i]);
                $file_size = filesize($files[$i]);

                $message .= "--{$mime_boundary}\n";
                $fp      = @fopen($files[$i], "rb");
                $data    = @fread($fp, $file_size);
                fclose($fp);
                $data    = chunk_split(base64_encode($data));
                $message .= "Content-Type: application/octet-stream; name=\"" . $file_name . "\"\n" .
                    "Content-Description: " . $file_name . "\n" .
                    "Content-Disposition: attachment;\n" . " filename=\"" . $file_name . "\"; size=" . $file_size . ";\n" .
                    "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            }
        }
    }

    $message    .= "--{$mime_boundary}--";
    $returnpath = "-f" . $senderEmail;

    mail($to, $subject, $message, $headers, $returnpath);
}


$recepient = "yulia.filippovaa@yandex.ru";
$siteName  = "CityCooling";

$companyName = trim($_POST["company_name"]);
$firstName   = trim($_POST["first_name"]);
$lastName    = trim($_POST["last_name"]);
$email       = trim($_POST["email"]);
$phone       = trim($_POST["phone"]);
$zipCode     = trim($_POST["zip_code"]);

$files = isset($_FILES['files']) && is_array($_FILES['files']) ? $_FILES['files'] : [];

$message = "Компания: $companyName Имя: $firstName \nТелефон: $phone \nEmail: $email \nИндекс: $zipCode";

$pagetitle = "Заявка с сайта \"$siteName\"";

multi_attach_mail($recepient, $pagetitle, $message, $recepient, $siteName, $files);

?>