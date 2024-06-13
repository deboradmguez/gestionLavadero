
<?php
/*
require 'db.php'; // Archivo de conexión a la base de datos

// Contraseña administrativa en texto plano
$contraseña_administrativa = "dom12";

// Hashear la contraseña administrativa con MD5
$contraseña_administrativa_md5 = md5($contraseña_administrativa);

// Insertar el usuario administrador en la base de datos con la contraseña hasheada
$stmt = $conn->prepare("INSERT INTO usuarios (nombreusuario, contraseña) VALUES (?, ?)");
$usuario_administrador = "admin";
$stmt->bind_param("ss", $usuario_administrador, $contraseña_administrativa_md5);
$stmt->execute();

echo "Usuario administrador insertado correctamente en la base de datos.";
?>
*/