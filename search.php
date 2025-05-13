<?php
include 'includes/config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$results = [];
if (!empty($query)) {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE title LIKE ? OR genre LIKE ?");
        $stmt->execute(["%$query%", "%$query%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error de base de datos: " . $e->getMessage();
        die();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="search-results">
    <h2>Resultados de búsqueda para: "<?php echo htmlspecialchars($query); ?>"</h2>
    
    <?php if (empty($results)): ?>
        <p>No se encontraron resultados.</p>
    <?php else: ?>
        <div class="movie-grid">
            <?php foreach ($results as $movie): ?>
                <div class="movie-card" data-trailer="<?php echo htmlspecialchars('assets/clips/' . $movie['clip_url']); ?>">
                    <?php if (isset($movie['thumbnail']) && !empty($movie['thumbnail'])): ?>
                        <?php
                        $imgPath = 'assets/images/' . $movie['thumbnail'];
                        $imgWebpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $imgPath);
                        
                        // Verificar si existe la versión WebP
                        if (file_exists($imgWebpPath)) {
                            echo '<img src="' . htmlspecialchars($imgWebpPath) . '" alt="' . htmlspecialchars($movie['title']) . '" loading="lazy">';
                        } 
                        // Verificar si existe la versión original (jpg, jpeg, png)
                        elseif (file_exists($imgPath)) {
                            echo '<img src="' . htmlspecialchars($imgPath) . '" alt="' . htmlspecialchars($movie['title']) . '" loading="lazy">';
                        } 
                        else {
                            // Si no existe ninguna, mostrar la imagen por defecto
                            echo '<img src="assets/images/default_thumbnail.jpg" alt="' . htmlspecialchars($movie['title']) . '" loading="lazy">';
                        }
                        ?>
                    <?php else: ?>
                        <img src="assets/images/default_thumbnail.jpg" alt="<?php echo htmlspecialchars($movie['title']); ?>" loading="lazy">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    <p class="genre"><?php echo ucfirst($movie['genre']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
