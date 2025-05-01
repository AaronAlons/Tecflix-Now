<?php
include '../includes/config.php';
session_start(); // ¡Inicia la sesión AQUÍ!

$mostrar_busqueda = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $error = "El nombre de usuario o correo electrónico ya está en uso";
        } else {
            $error = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Registrarse en Tecflix</h2>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>