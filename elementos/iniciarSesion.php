<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method='POST' action='index.php'>
<input type='hidden' name='login' value='login'>
<label for='usuario'>Usuario:</label>
<input type='text' name='usuario' required><br><br>
<label for='credencial'>Contraseña:</label>
<input type='credencial' name='credencial' required><br><br>
<input type='submit' value='Iniciar sesión'>
</form>
</body>
</html>



<?php

echo "<h1> HOLA </h1>";

?>