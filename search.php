<?php
include 'includes/config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$results = [];
if (!empty($query)) {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE title LIKE ? OR genre LIKE ?");
    $stmt->execute(["%$query%", "%$query%"]);
    $results = $stmt->fetchAll();
}
?>

<?php include 'includes/header.php'; ?>

<div class="search-results">
    <h2>Resultados de búsqueda para: "<?php echo htmlspecialchars($query); ?>"</h2>
    
    <?php if(empty($results)): ?>
        <p>No se encontraron resultados.</p>
    <?php else: ?>
        <div class="movie-grid">
            <?php foreach ($results as $movie): ?>
                <div class="movie-card" data-trailer="<?php echo htmlspecialchars($movie['trailer_url']); ?>">
                    <img src="assets/images/<?php echo htmlspecialchars($movie['thumbnail']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    <p class="genre"><?php echo ucfirst($movie['genre']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>