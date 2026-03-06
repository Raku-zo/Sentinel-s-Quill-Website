<?php
$password = 'ansarapmoian';
$hash = password_hash($password,PASSWORD_DEFAULT);
echo $hash;
?>