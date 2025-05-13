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

// Manejar la eliminación de un perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_profile'])) {
    $profile_id = $_POST['profile_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM profiles WHERE id = ? AND user_id = ?");
        $stmt->execute([$profile_id, $user_id]);
        // Podrías agregar una confirmación de que se eliminó el perfil
    } catch (PDOException $e) {
        echo "Error al eliminar el perfil: " . $e->getMessage();
        error_log("Error al eliminar el perfil: " . $e->getMessage());
        // ¡Manejar el error adecuadamente!  Mostrar un mensaje al usuario, hacer rollback de la transacción, etc.
    }
    header('Location: profile.php'); // Redirigir después de eliminar
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
        $editing = isset($_GET['edit']); // Comprobar si estamos en modo de edición
        if (count($profiles) > 0) :
            foreach ($profiles as $profile) :
        ?>
                <div class="profile-container">
                    <form method="POST">
                        <input type="hidden" name="profile_id" value="<?php echo $profile['id']; ?>">
                        <button type="submit" name="select_profile" class="profile" <?php if ($editing) echo 'disabled'; // Deshabilitar selección en modo de edición ?>>
                            <img src="assets/images/<?php echo htmlspecialchars($profile['profile_icon'] ?? 'default_profile_icon.png'); ?>" alt="<?php echo htmlspecialchars($profile['profile_name']); ?>">
                            <p><?php echo htmlspecialchars($profile['profile_name']); ?></p>
                        </button>
                    </form>
                    <!-- Botón para eliminar el perfil (modo edicion)-->
                    <?php if ($editing) : ?>
                        <form method="POST" style="margin-top: 10px;">
                            <input type="hidden" name="profile_id" value="<?php echo $profile['id']; ?>">
                            <button type="submit" name="delete_profile" class="delete-profile-button">
                                Eliminar
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
        <?php
            endforeach;
        else :
            echo "<p>No hay perfiles disponibles para este usuario.</p>"; // Mostrar mensaje si no hay perfiles
        endif;
        ?>
        <div class="add-profile-container">
            <button onclick="document.getElementById('addProfileForm').style.display='block'" class="add-profile" <?php if ($editing) echo 'disabled'; ?>>
                <img src="assets/images/add_profile.jpg" alt="Agregar perfil">
                <p>Agregar perfil</p>
            </button>
            <?php if (!$editing) : ?>
                <a href="?edit=true" class="edit-profiles-button">Editar perfiles</a>
            <?php else : ?>
                <a href="profile.php" class="edit-profiles-button">Cancelar</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="addProfileForm" style="display:none;">
        <h3>Agregar nuevo perfil</h3>
        <form method="POST" <?php // Opción 2:  Agrega esto para la carga de archivos:  echo 'enctype="multipart/form-data"'; ?>>
            <input type="text" name="profile_name" placeholder="Nombre del perfil" required>

            <?php // Opción 1: Selección de imagen predefinida 
            ?>
            <select name="profile_icon">
                <option value="profile_icon_1.png">Icono 1</option>
                <option value="profile_icon_2.png">Icono 2</option>
                <option value="profile_icon_3.jpg">Icono 3</option>
                <option value="default_profile_icon.jpg" selected>Predeterminado</option>
            </select>

            <?php // Opción 2: Carga de imagen (¡MÁS COMPLEJO!) 
            ?>
            <?php //  echo '<input type="file" name="profile_image" accept="image/*">';  
            ?>

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
        flex-wrap: wrap;
        justify-content: center;
    }

    .profile-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .profile,
    .add-profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: none;
        border: none;
        cursor: pointer;
        color: white;
        padding: 0;
    }

    .profile img,
    .add-profile img {
        width: 100px;
        /* Ajusta el tamaño según necesites */
        height: 100px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .profile p,
    .add-profile p {
        margin-bottom: 0;
    }

    .add-profile-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .edit-profiles-button {
        margin-top: 10px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .edit-profiles-button:hover {
        background-color: #367c39;
    }

    .delete-profile-button {
        background-color: #e53e3e;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
    }

    .delete-profile-button:hover {
        background-color: #c53030;
    }
</style>

<?php include 'includes/footer.php'; ?>
