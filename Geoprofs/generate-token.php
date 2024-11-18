<?php
$token = bin2hex(random_bytes(32)); // Generates a 64-character token
file_put_contents('token.txt', $token); // Saves the token in a text file!!
echo "Token generated and saved to token.txt\n";
?>
