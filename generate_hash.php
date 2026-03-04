<?php
$password = 'mypassword';
$hash = password_hash($password,PASSWORD_DEFAULT);
echo $hash;
?>