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
                <div class="movie-card" data-trailer="<?php echo htmlspecialchars($movie['clip_url']); ?>">
                    <?php if (isset($movie['thumbnail']) && !empty($movie['thumbnail'])): ?>
                        <?php
                        $imgPath = 'assets/images/' . $movie['thumbnail'];
                        $imgWebpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $imgPath);

                        if (file_exists($imgWebpPath)) {
                            echo '<img src="' . htmlspecialchars($imgWebpPath) . '" alt="' . htmlspecialchars($movie['title']) . '" loading="lazy">';
                        } elseif (file_exists($imgPath)) {
                            echo '<img src="' . htmlspecialchars($imgPath) . '" alt="' . htmlspecialchars($movie['title']) . '" loading="lazy">';
                        } else {
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

<div id="clip-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <video id="movie-clip" width="100%" height="500" controls></video>
    </div>
</div>

<style>
    /* Estilos del modal del tráiler */
    .modal {
        display: none;
        position: fixed;
        z-index: 101;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .modal-content {
        position: relative;
        margin: auto;
        padding: 20px;
        width: 80%;
        max-width: 800px;
        top: 50%;
        transform: translateY(-50%);
    }

    .close {
        color: white;
        position: absolute;
        top: -40px;
        right: 0;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal del clip
        const modal = document.getElementById('clip-modal');
        const movieClip = document.getElementById('movie-clip');
        const closeBtn = document.querySelector('.close');

        // Función para manejar el clic en las películas
        function handleMovieClick(event) {
            const clipUrl = this.getAttribute('data-trailer');
            if (clipUrl) {
                movieClip.src = clipUrl;
                modal.style.display = 'block';
                movieClip.load();
                movieClip.play();
            }
        }

        // Mostrar clip al hacer clic en las películas de la búsqueda
        const movieCards = document.querySelectorAll('.movie-card');
        movieCards.forEach(card => {
            card.addEventListener('click', handleMovieClick);
        });

        // Cerrar modal
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            movieClip.pause();
            movieClip.currentTime = 0;
            movieClip.src = '';
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                movieClip.pause();
                movieClip.currentTime = 0;
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>