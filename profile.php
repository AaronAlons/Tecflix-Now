<?php
session_start();
include 'includes/config.php';

// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Manejar la creación de un nuevo perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_profile'])) {
    $profile_name = $_POST['profile_name'];
    // ¡VALIDAR!  Sanitizar y validar $profile_name

    // Opción 1: Selección de imagen predefinida
    $profile_icon = $_POST['profile_icon'] ?? 'default_profile_icon.png';  // Usa el valor seleccionado o el predeterminado

    /*
    // Opción 2: Carga de imagen (¡MÁS COMPLEJO!)
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $upload_dir = 'assets/images/';
        $file_name = uniqid() . '_' . basename($_FILES['profile_image']['name']);
        $upload_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
            $profile_icon = $file_name;
        } else {
            $profile_icon = 'default_profile_icon.png';
            // ¡Manejar el error!
        }
    } else {
        $profile_icon = 'default_profile_icon.png';
    }
    */

    $stmt = $pdo->prepare("INSERT INTO profiles (user_id, profile_name, profile_icon) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $profile_name, $profile_icon]);

    header('Location: profile.php');
    exit;
}

// Manejar la selección de un perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_profile'])) {
    $profile_id = $_POST['profile_id'];
    $_SESSION['profile_id'] = $profile_id;
    header('Location: index.php');
    exit;
}

// Obtener los perfiles del usuario desde la base de datos
try {
    $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profiles = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error al obtener los perfiles: " . $e->getMessage();
    error_log("Error al obtener los perfiles: " . $e->getMessage());
    $profiles = []; // Asegurar que $profiles está definido para evitar errores posteriores
}

include 'includes/header.php';
?>

<div class="profile-selection-container">
    <h2>¿Quién está mirando?</h2>
    <div class="profiles">
        <?php 
        if (count($profiles) > 0): // Verificar si hay perfiles antes de iterar
            foreach ($profiles as $profile): ?>
                <form method="POST">
                    <input type="hidden" name="profile_id" value="<?php echo $profile['id']; ?>">
                    <button type="submit" name="select_profile" class="profile">
                        <img src="assets/images/<?php echo htmlspecialchars($profile['profile_icon'] ?? 'default_profile_icon.png'); ?>" alt="<?php echo htmlspecialchars($profile['profile_name']); ?>">
                        <p><?php echo htmlspecialchars($profile['profile_name']); ?></p>
                    </button>
                </form>
            <?php 
            endforeach;
        else:
            echo "<p>No hay perfiles disponibles para este usuario.</p>"; // Mostrar mensaje si no hay perfiles
        endif;
        ?>
        <button onclick="document.getElementById('addProfileForm').style.display='block'" class="add-profile">
            <img src="assets/images/add_profile.jpg" alt="Agregar perfil">
            <p>Agregar perfil</p>
        </button>
    </div>

    <div id="addProfileForm" style="display:none;">
        <h3>Agregar nuevo perfil</h3>
        <form method="POST" 
              <?php // Opción 2:  Agrega esto para la carga de archivos:  echo 'enctype="multipart/form-data"'; ?>>
            <input type="text" name="profile_name" placeholder="Nombre del perfil" required>

            <?php // Opción 1: Selección de imagen predefinida ?>
            <select name="profile_icon">
                <option value="profile_icon_1.png">Icono 1</option>
                <option value="profile_icon_2.png">Icono 2</option>
                <option value="profile_icon_3.jpg">Icono 3</option>
                <option value="default_profile_icon.jpg" selected>Predeterminado</option>
            </select>

            <?php // Opción 2: Carga de imagen (¡MÁS COMPLEJO!) ?>
            <?php //  echo '<input type="file" name="profile_image" accept="image/*">';  ?>

            <button type="submit" name="create_profile">Guardar perfil</button>
            <button type="button" onclick="document.getElementById('addProfileForm').style.display='none'">Cancelar</button>
        </form>
    </div>
</div>

<style>
    /* (El mismo CSS que antes) */
    .profile-selection-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 80vh;
    }

    .profiles {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .profile, .add-profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: none;
        border: none;
        cursor: pointer;
        color: white;
    }

    .profile img, .add-profile img {
        width: 100px; /* Ajusta el tamaño según necesites */
        height: 100px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>

<?php include 'includes/footer.php'; ?>
