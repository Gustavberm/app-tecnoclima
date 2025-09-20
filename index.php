<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Layout general */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: #2c3e50;
            color: #fff;
            width: 250px;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            padding: 1rem;
            background: #1a252f;
            margin: 0;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            color: #fff;
            display: block;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background: #34495e;
        }

        .sidebar .logout {
            margin-top: auto;
            background: #c0392b;
            text-align: center;
        }

        /* Contenido */
        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .toggle-btn {
            display: none;
            font-size: 20px;
            cursor: pointer;
            color: #2c3e50;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100%;
                transform: translateX(-100%);
                z-index: 1000;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .toggle-btn {
                display: block;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>Menú</h2>
        <a href="index.php">🏠 Inicio</a>
        <a href="registrar_proforma.php">➕ Nueva Proforma</a>
        <a href="proformas.php">📄 Ver Proformas</a>
        <a href="clientes.php">👥 Clientes</a>
        <a href="editar_empresa.php">🏢 Empresa</a>
        <a href="logout.php" class="logout">🚪 Cerrar Sesión</a>
    </div>

    <!-- Contenido -->
    <div class="content">
        <header>
            <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
            <h1>Panel Principal</h1>
            <div>
                Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
            </div>
        </header>

        <main>
            <h2>Bienvenido al sistema de presupuestos</h2>
            
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>



