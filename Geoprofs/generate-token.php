<?php
$token = bin2hex(random_bytes(32));
file_put_contents('token.txt', $token . PHP_EOL, FILE_APPEND);
echo "Token generated and saved to token.txt\n";
?>
