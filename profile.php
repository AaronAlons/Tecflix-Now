<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

//  ¡PERFILES ESTÁTICOS!  Definidos directamente en el código
$static_profiles = [
    [ 'id' => 1, 'name' => 'Enricote', 'icon' => 'Perfil 1.jpg' ],
    [ 'id' => 2, 'name' => 'Aaron', 'icon' => 'perfil 2.jpg' ],
    [ 'id' => 3, 'name' => 'Enrique', 'icon' => 'perfil 3.jpg' ],
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_profile'])) {
    $profile_id = $_POST['profile_id'];
    $_SESSION['profile_id'] = $profile_id;
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
?>

<div class="profile-selection-container">
    <h2>¿Quién está mirando?</h2>
    <div class="profiles">
        <?php foreach ($static_profiles as $profile): ?>
            <form method="POST">
                <input type="hidden" name="profile_id" value="<?php echo $profile['id']; ?>">
                <button type="submit" name="select_profile" class="profile">
                    <img src="assets/images/<?php echo $profile['icon']; ?>" alt="<?php echo $profile['name']; ?>">
                    <p><?php echo $profile['name']; ?></p>
                </button>
            </form>
        <?php endforeach; ?>
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