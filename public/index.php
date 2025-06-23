<?php
require_once(__DIR__ . '/../app/autoload.php');
$router = new Router();
$router->run();

// if (isset($_POST['registrarUsuario'])) {
//     $nombre = $_POST['nombre'];
//     $correo = $_POST['correo'];
//     $contraseña = $_POST['contraseña'];
//     $rol = $_POST['rol_id'];

//     // Ruta absoluta en servidor para guardar imágenes
//     $directorio = __DIR__ . '/imagenes/';

//     // Crear carpeta si no existe
//     if (!is_dir($directorio)) {
//         mkdir($directorio, 0755, true);
//     }

//     $typeImagen = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

//     // Insertar usuario sin imagen primero
//     $stmt = $conn->prepare('INSERT INTO usuario (nombre, correo, contraseña, rol_id, imagen) VALUES (?, ?, ?, ?, ?)');
//     $stmt->execute([$nombre, $correo, $contraseña, $rol, '']);

//     $lastId = $conn->lastInsertId();

//     // Nombre final y ruta para guardar
//     $nombreArchivo = $lastId . '.' . $typeImagen;
//     $rutaFisica = $directorio . $nombreArchivo; // ruta para mover archivo
//     $rutaBD = '/imagenes/' . $nombreArchivo;   // ruta que guardarás en BD

//     // Mover archivo subido a la carpeta destino
//     if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFisica)) {
//         // Actualizar ruta imagen en la DB
//         $stmt = $conn->prepare('UPDATE usuario SET imagen = ? WHERE id = ?');
//         $stmt->execute([$rutaBD, $lastId]);
//         echo "<script>alert('Usuario registrado correctamente');</script>";
//     } else {
//         echo "<script>alert('Error al subir la imagen');</script>";
//     }
// }

?>