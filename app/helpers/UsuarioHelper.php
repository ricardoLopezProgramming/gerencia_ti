<?php

function insertUsuario(PDO $connection, array $data, array $file): void
{
    $nombre = $data['nombre'];
    $correo = $data['correo'];
    $password = $data['password'];
    $rol_id = $data['rol_id'];
    $imagenTmp = $file['tmp_name'];
    $imagenNombre = $file['name'];

    $ext = strtolower(pathinfo($imagenNombre, PATHINFO_EXTENSION));
    $directorio = realpath(__DIR__ . '/../../public/assets/image');

if (!$directorio) {
    die('Error: No se encontró el directorio de destino.');
}


    
    $publicPath = '/public/assets/image/';

    $stmt = $connection->prepare("INSERT INTO usuario (nombre, correo, password, rol_id, imagen) VALUES (?, ?, ?, ?, '')");
    $stmt->execute([$nombre, $correo, $password, $rol_id]);

    $lastId = $connection->lastInsertId();

    $nombreImagen = $lastId . '.' . $ext;
    $rutaRelativa = $publicPath . $nombreImagen;
    $rutaCompleta = $directorio . '/' . $nombreImagen;

    if (move_uploaded_file($imagenTmp, $rutaCompleta)) {
        $stmt = $connection->prepare("UPDATE usuario SET imagen = ? WHERE id = ?");
        $stmt->execute([$rutaRelativa, $lastId]);
    } else {
        echo "<script>alert('Error al subir la imagen.');</script>";
    }

    header('Location: /public/usuario/home');
    exit;
}
