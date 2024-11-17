<?php
$token = bin2hex(random_bytes(32)); // Generates a 64-character token
echo $token . PHP_EOL;
