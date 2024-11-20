<?php
// Incluir la conexión a la base de datos
include 'db.php';
session_start();

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validaciones simples
    if (empty($username) || empty($password)) {
        echo "Por favor complete ambos campos.";
        exit;
    }

    // Consulta preparada para obtener el usuario
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $dbUsername, $dbPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $dbPassword)) {
        // Crear sesión
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $dbUsername;
        header("Location: index.html");
    } else {
        echo "Usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
