<?php
include '../includes/config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$mostrar_busqueda = false;
include '../includes/header.php';
?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Iniciar sesión en Tecflix</h2>
        <div id="error-message-container">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
        <form method="POST" id="login-form">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</main>

<div id="loading-animation">
    <div class="netflix-n">T</div>
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        document.getElementById('loading-animation').style.display = 'flex';
        setTimeout(function() {
            document.getElementById('login-form').submit();
        }, 2000);
    });
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mostrar la animación SOLO si el formulario se ha enviado
    echo '<script>document.getElementById("loading-animation").style.display = "flex";</script>';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
        // Mostrar el mensaje de error usando JavaScript (¡CRUCIAL!)
        echo '<script>
                document.getElementById("error-message-container").innerHTML = \'<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>\';
                document.getElementById("loading-animation").style.display = "none";
              </script>';
    }
}

include '../includes/footer.php';
?>