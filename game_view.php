<?php 
include 'controladores/cont_game_view.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['titulo']); ?> - NexusPlay</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/game_view.css">
    <link rel="stylesheet" href="/nexusplay/assests/fontawesome/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
        <?php if (!empty($message)): ?>
            <div class="game-message <?php echo $message_type; ?>">
                <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Hero del Juego -->
        <section class="game-hero">
            <div class="game-hero-container">
                <div class="game-image">
                    <img src="images/juegos/<?php echo htmlspecialchars($game['imagen'] ?: 'default.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($game['titulo']); ?>">
                </div>
                
                <div class="game-details">
                    <div class="game-header">
                        <h1><?php echo htmlspecialchars($game['titulo']); ?></h1>
                        <p class="game-developer"><?php echo htmlspecialchars($game['desarrollador'] ?: 'Desarrollador no especificado'); ?></p>
                    </div>
                    
                    <div class="game-meta">
                        <span class="game-platform">
                            <i class="fas fa-gamepad"></i> 
                            <?php echo htmlspecialchars($game['plataforma_nombre'] ?: 'Multiplataforma'); ?>
                        </span>
                        <span class="game-category">
                            <i class="fas fa-tag"></i> 
                            <?php echo htmlspecialchars($game['categoria_nombre'] ?: 'Sin categoría'); ?>
                        </span>
                        <?php if ($game['fecha_lanzamiento']): ?>
                        <span class="game-release">
                            <i class="fas fa-calendar"></i> 
                            <?php echo date('d/m/Y', strtotime($game['fecha_lanzamiento'])); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="game-rating">
                        <div class="stars">
                            <?php echo generateStars(round($game['promedio_rating'], 1)); ?>
                        </div>
                        <span class="rating-text">
                            <?php echo round($game['promedio_rating'], 1); ?>/5 (<?php echo $game['total_resenas']; ?> reseñas)
                        </span>
                    </div>
                    
                    <div class="game-price-section">
                        <span class="game-price">$<?php echo number_format($game['precio'], 2); ?></span>
                        <form method="POST" action="controladores/carrito_add.php" class="add-to-cart-form">
                            <input type="hidden" name="juego_id" value="<?php echo $game['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-add-cart">
                                <i class="fas fa-shopping-cart"></i> Añadir al Carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Descripción del Juego -->
        <section class="game-description-section">
            <div class="container">
                <h2>Descripción</h2>
                <div class="game-description">
                    <?php echo nl2br(htmlspecialchars($game['descripcion'] ?: 'No hay descripción disponible para este juego.')); ?>
                </div>
            </div>
        </section>

        <!-- Sección de Reseñas -->
        <section class="reviews-section">
            <div class="container">
                <div class="reviews-header">
                    <h2>Reseñas de Usuarios</h2>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-secondary" id="toggleReviewForm">
                            <i class="fas fa-edit"></i> 
                            <?php echo $user_review ? 'Editar mi reseña' : 'Escribir reseña'; ?>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Estadísticas de Rating -->
                <?php if ($game['total_resenas'] > 0): ?>
                <div class="rating-stats">
                    <div class="rating-overview">
                        <div class="overall-rating">
                            <div class="overall-score"><?php echo round($game['promedio_rating'], 1); ?></div>
                            <div class="overall-stars">
                                <?php echo generateStars(round($game['promedio_rating'], 1)); ?>
                            </div>
                            <div class="total-reviews-text"><?php echo $game['total_resenas']; ?> reseñas</div>
                        </div>
                        
                        <div class="rating-breakdown">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <div class="rating-bar">
                                <span class="rating-label"><?php echo $i; ?> estrellas</span>
                                <div class="rating-progress">
                                    <?php 
                                    $count = isset($rating_stats[$i]) ? $rating_stats[$i]['cantidad'] : 0;
                                    $percentage = $game['total_resenas'] > 0 ? ($count / $game['total_resenas']) * 100 : 0;
                                    ?>
                                    <div class="rating-fill" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <span class="rating-count"><?php echo $count; ?></span>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Formulario de Reseña -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="review-form-container" id="reviewFormContainer" style="display: none;">
                    <form method="POST" class="review-form">
                        <h3><?php echo $user_review ? 'Editar mi reseña' : 'Escribir una reseña'; ?></h3>
                        
                        <div class="rating-input">
                            <label>Calificación:</label>
                            <div class="star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="puntuacion" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" 
                                       <?php echo ($user_review && $user_review['puntuacion'] == $i) ? 'checked' : ''; ?>>
                                <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="comment-input">
                            <label for="comentario">Tu reseña:</label>
                            <textarea name="comentario" id="comentario" placeholder="Comparte tu experiencia con este juego..." required><?php echo $user_review ? htmlspecialchars($user_review['comentario']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="add_review" class="btn-submit-review">
                                <i class="fas fa-paper-plane"></i> 
                                <?php echo $user_review ? 'Actualizar reseña' : 'Publicar reseña'; ?>
                            </button>
                            <button type="button" class="btn-cancel" onclick="toggleReviewForm()">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 30px; background: rgba(255,255,255,0.05); border-radius: 10px; margin-bottom: 30px;">
                    <p style="color: #ccc; margin-bottom: 15px;">
                        <i class="fas fa-sign-in-alt"></i> 
                        Inicia sesión para escribir una reseña
                    </p>
                    <a href="auth/login.php" class="btn btn-primary">
                        <i class="fas fa-user"></i> Iniciar Sesión
                    </a>
                </div>
                <?php endif; ?>

                <!-- Lista de Reseñas -->
                <div class="reviews-list">
                    <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                        <?php while ($review = $reviews_result->fetch_assoc()): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="review-user">
                                        <img src="images/users/<?php echo htmlspecialchars($review['imagen_perfil'] ?: 'default-avatar.png'); ?>" 
                                             alt="<?php echo htmlspecialchars($review['username']); ?>" 
                                             class="review-avatar">
                                        <div class="review-user-info">
                                            <h4><?php echo htmlspecialchars($review['username']); ?></h4>
                                            <p class="review-date"><?php echo date('d/m/Y', strtotime($review['fecha_resena'])); ?></p>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <?php echo generateStars($review['puntuacion']); ?>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <?php echo nl2br(htmlspecialchars($review['comentario'])); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-reviews">
                            <i class="fas fa-comments"></i>
                            <h3>No hay reseñas todavía</h3>
                            <p>Sé el primero en compartir tu opinión sobre este juego</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Juegos Relacionados -->
        <?php if ($related_result && $related_result->num_rows > 0): ?>
        <section class="related-section">
            <div class="container">
                <h2>Juegos Relacionados</h2>
                <div class="related-games">
                    <?php while ($related_game = $related_result->fetch_assoc()): ?>
                        <a href="game_view.php?id=<?php echo $related_game['id']; ?>" class="related-game-card">
                            <div class="related-game-image">
                                <img src="images/juegos/<?php echo htmlspecialchars($related_game['imagen'] ?: 'default.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($related_game['titulo']); ?>">
                            </div>
                            <div class="related-game-info">
                                <h3><?php echo htmlspecialchars($related_game['titulo']); ?></h3>
                                <div class="related-game-rating">
                                    <div class="stars">
                                        <?php echo generateStars(round($related_game['promedio_rating'], 1)); ?>
                                    </div>
                                    <span><?php echo round($related_game['promedio_rating'], 1); ?>/5</span>
                                </div>
                                <div class="related-game-price">
                                    $<?php echo number_format($related_game['precio'], 2); ?>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>

    <script>
        // Toggle del formulario de reseña
        function toggleReviewForm() {
            const formContainer = document.getElementById('reviewFormContainer');
            const isVisible = formContainer.style.display !== 'none';
            formContainer.style.display = isVisible ? 'none' : 'block';
            
            if (!isVisible) {
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        document.getElementById('toggleReviewForm')?.addEventListener('click', toggleReviewForm);

        // Manejo de las estrellas del rating
        const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
        const starLabels = document.querySelectorAll('.star-rating label');

        starLabels.forEach((label, index) => {
            label.addEventListener('mouseenter', () => {
                starLabels.forEach((l, i) => {
                    if (i >= index) {
                        l.style.color = '#fbbf24';
                    } else {
                        l.style.color = '#666';
                    }
                });
            });

            label.addEventListener('mouseleave', () => {
                const checkedStar = document.querySelector('.star-rating input[type="radio"]:checked');
                if (checkedStar) {
                    const checkedIndex = Array.from(starInputs).indexOf(checkedStar);
                    starLabels.forEach((l, i) => {
                        l.style.color = i >= checkedIndex ? '#fbbf24' : '#666';
                    });
                } else {
                    starLabels.forEach(l => l.style.color = '#666');
                }
            });

            label.addEventListener('click', () => {
                setTimeout(() => {
                    const checkedStar = document.querySelector('.star-rating input[type="radio"]:checked');
                    if (checkedStar) {
                        const checkedIndex = Array.from(starInputs).indexOf(checkedStar);
                        starLabels.forEach((l, i) => {
                            l.style.color = i >= checkedIndex ? '#fbbf24' : '#666';
                        });
                    }
                }, 10);
            });
        });

        // Inicializar estrellas si hay una reseña existente
        document.addEventListener('DOMContentLoaded', () => {
            const checkedStar = document.querySelector('.star-rating input[type="radio"]:checked');
            if (checkedStar) {
                const checkedIndex = Array.from(starInputs).indexOf(checkedStar);
                starLabels.forEach((l, i) => {
                    l.style.color = i >= checkedIndex ? '#fbbf24' : '#666';
                });
            }
        });

        // Scroll suave para mensajes
        if (document.querySelector('.game-message')) {
            document.querySelector('.game-message').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    </script>
</body>
</html>