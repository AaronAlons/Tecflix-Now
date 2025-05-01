<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$mostrar_busqueda = true;
include 'includes/header.php';

// Datos de las películas (¡hardcoded! - ¡AJUSTA ESTO!)
$movies_by_genre = [
    'Aventura' => [
        ['title' => 'El viaje de chihiro', 'thumbnail' => 'thumbnails/chihiro.jpg', 'clip_url' => 'assets/clips/CHIHIRO.mp4'],
        ['title' => 'Avatar the way of water', 'thumbnail' => 'thumbnails/avatar.webp', 'clip_url' => 'assets/clips/avatar.mp4'],
        ['title' => 'Thor love and thunder', 'thumbnail' => 'thumbnails/thor.jpg', 'clip_url' => 'assets/clips/Thor.mp4'],
        ['title' => 'Star Wars la venganza de los sith', 'thumbnail' => 'thumbnails/StarwarsIII.webp', 'clip_url' => 'assets/clips/stawars.mp4'],
        ['title' => 'Jurassic Park', 'thumbnail' => 'thumbnails/jurassic.webp', 'clip_url' => 'assets/clips/jurassic.mp4'],
        
        // ... más películas de romance
    ],
    'horror' => [
        ['title' => 'The Conjuring', 'thumbnail' => 'thumbnails/the_conjuring.jpg', 'clip_url' => 'clips/the_conjuring_clip.mp4'],
        ['title' => 'Hereditary', 'thumbnail' => 'thumbnails/hereditary.jpg', 'clip_url' => 'clips/hereditary_clip.mp4'],
        // ... más películas de terror
    ],
    // ... más géneros
];
?>

<div class="hero">
    <h1>Bienvenido a Tecflix</h1>
    <p>Disfruta de las mejores películas en línea</p>
    <img src="assets/images/bienvenida.jpg" alt="">
</div>

<div class="categories">
    <?php foreach ($movies_by_genre as $genre => $movies): ?>
        <section class="movie-section">
            <h2><?php echo ucfirst($genre); ?></h2>
            <div class="movie-row">
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-card" data-clip-url="<?php echo htmlspecialchars($movie['clip_url']); ?>">
                        <img src="assets/images/<?php echo htmlspecialchars($movie['thumbnail']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<div id="clip-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <video id="movie-clip" width="100%" height="500" controls></video>
    </div>
</div>

<?php include 'includes/footer.php'; ?>