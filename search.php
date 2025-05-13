<?php
include 'includes/config.php';

if (isset($_GET['movie_query']) && !empty($_GET['movie_query'])) {
    $query = $_GET['movie_query'];
    //  Sanitizar la entrada del usuario!!!
    $query = htmlspecialchars($query);
    $query = filter_var($query, FILTER_SANITIZE_STRING);

    try {
        // Modifica esta consulta para buscar en tu tabla de películas
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE title LIKE ?");
        $stmt->execute(["%$query%"]);
        $movies = $stmt->fetchAll();

        include 'includes/header.php'; // Incluir el encabezado para mantener la consistencia de la página
        ?>
        <div class="profile-selection-container">
            <h2>Resultados de la búsqueda para "<?php echo htmlspecialchars($query); ?>"</h2>
            <div class="profiles">
                <?php if (count($movies) > 0) : ?>
                    <?php foreach ($movies as $movie) : ?>
                        <div class="profile-container">
                            <form method="POST" action="index.php">
                                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                <button type="submit" name="select_movie" class="profile">
                                    <img src="assets/images/<?php echo htmlspecialchars($movie['thumbnail'] ?? 'default_movie_icon.png'); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                    <p><?php echo htmlspecialchars($movie['title']); ?></p>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No se encontraron películas que coincidan con su búsqueda.</p>
                <?php endif; ?>
            </div>
            <a href="index.php" class="back-to-index-button">Volver al inicio</a>
        </div>
        <style>
            .back-to-index-button {
                margin-top: 20px;
                background-color: #4CAF50;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                text-decoration: none;
            }

            .back-to-index-button:hover {
                background-color: #367c39;
            }
        </style>
        <?php
        include 'includes/footer.php'; // Incluir el pie de página
    } catch (PDOException $e) {
        echo "Error al realizar la búsqueda: " . $e->getMessage();
        error_log("Error al realizar la búsqueda: " . $e->getMessage());
        // Manejar el error (mostrar mensaje al usuario, redirigir, etc.)
    }
} else {
    // Redirigir si se accede a search.php sin una consulta
    header('Location: index.php');
    exit;
}
?>