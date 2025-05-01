<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tecflix - Tu plataforma de streaming</title>
    <link rel="stylesheet" href="/Tecflix-Now/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>Tecflix</h1>
            </div>
            <?php if (isset($mostrar_busqueda) && $mostrar_busqueda): ?>
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="query" placeholder="Buscar películas...">
                        <button type="submit">Buscar</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="btn">Cerrar sesión</a>
                <?php else: ?>
                    <a href="/Tecflix-Now/auth/login.php" class="btn">Iniciar sesión</a>
                    <a href="/Tecflix-Now/auth/register.php" class="btn btn-primary">Registrarse</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main>