<?php
session_start();
include 'config.php';

$error = '';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario=? AND password=?");
    $stmt->bind_param("ss",$usuario,$password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
/* Fondo completo centrado y responsive */
body.login {
    margin: 0;
    font-family: Arial, sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;

    background-image: url('assets/img/1.jpeg'); /* Ruta de tu fondo */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

/* Contenedor del formulario */
.login-container {
    background: rgba(255,255,255,0.9);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    width: 90%;
    max-width: 400px;
    text-align: center;
}

/* Logo responsive */
.login-container img {
    max-width: 100%;
    margin-bottom: 20px;
}

/* Inputs y botón */
.login-container input[type="text"],
.login-container input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.login-container input[type="submit"] {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: none;
    background: #2c3e50;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

.login-container input[type="submit"]:hover {
    background: #34495e;
}

/* Mensaje de error */
.error {
    color: #c0392b;
    margin-bottom: 15px;
}
</style>
</head>
<body class="login">
    <div class="login-container">
        <!-- Logo del usuario -->
        <img src="assets/img/logo.png" alt="Logo">

        <!-- Formulario -->
        <form method="POST" action="login.php">
            <?php if($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>
