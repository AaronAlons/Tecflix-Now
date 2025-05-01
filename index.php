<?php
include 'includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$mostrar_busqueda = true;
include 'includes/header.php';
?>

<div class="hero">
    <h1>Bienvenido a Tecflix</h1>
    <p>Disfruta de las mejores películas en línea</p>
</div>

<div class="categories">
    <?php
    $genres = ['romance', 'horror', 'action', 'scifi', 'fantasy'];
    foreach ($genres as $genre) {
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE genre = ? LIMIT 5");
        $stmt->execute([$genre]);
        $movies = $stmt->fetchAll();

        if (!empty($movies)) {
            echo '<section class="movie-section">';
            echo '<h2>' . ucfirst($genre) . '</h2>';
            echo '<div class="movie-row">';

            foreach ($movies as $movie) {
                echo '<div class="movie-card" data-trailer="' . htmlspecialchars($movie['trailer_url']) . '">';
                echo '<img src="assets/images/' . htmlspecialchars($movie['thumbnail']) . '" alt="' . htmlspecialchars($movie['title']) . '">';
                echo '<h3>' . htmlspecialchars($movie['title']) . '</h3>';
                echo '</div>';
            }

            echo '</div></section>';
        }
    }
    ?>
</div>

<div id="trailer-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <iframe id="trailer-iframe" width="100%" height="500" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<?php include 'includes/footer.php'; ?>