<?php
$token = bin2hex(random_bytes(32)); // Generates a 64-character token
file_put_contents('token.txt', $token . PHP_EOL, FILE_APPEND); // Appends the token to the file with a newline
echo "Token generated and saved to token.txt\n";
?>
