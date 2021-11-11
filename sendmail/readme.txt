This Easy SMTP Mail Class Package is useful to send email from our web server with mail() function and or directly trough smtp server like smtp.gmail.com. Enable for secure connection using SSL or TLS. Email can be sent with attachment file(s).

To send email trough smtp server with secure connection (SSL or TLS) we need the Open SSL PHP extension is loaded. Same as to send email with mail() function, we must have "a permission" to use that function.

To enable writing log file, we must create a "log" path within smtp.mail.class.php & easy.mail.class.php folder and ensure it has writable permission (777).

Note:
This class package, especially smtp.mail.class.php is inspired by and rewritten from PHPMailer (http://code.google.com/a/apache-extras.org/p/phpmailer/) and Simple SMTP Class for PHP (http://www.kidmoses.com/blog-article.php?bid=56)