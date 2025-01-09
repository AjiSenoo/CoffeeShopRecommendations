<?php
$storedHash = '$2y$10$EJs3lt5VfuyqQ4B9FMBm0.8.FaS3GFz3Ol/bIkSmO.bN30.Iyo.KS';
$inputPassword = 'admin123';

if (password_verify($inputPassword, $storedHash)) {
    echo "Password verification succeeded!";
} else {
    echo "Password verification failed!";
}
