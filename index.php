<?php 
include 'controladores/cont_index.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusPlay - Tienda de Videojuegos</title>
    <link rel="stylesheet" href="css/head/buscador.css">
    <link rel="stylesheet" href="css/head/cont_head.css">
    <link rel="stylesheet" href="css/head/icono_naveg.css">
    <link rel="stylesheet" href="css/head/logo.css">
    <link rel="stylesheet" href="css/head/resp_hf.css">
    <link rel="stylesheet" href="css/foot/cont_foot.css">
    <link rel="stylesheet" href="css/index.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
    <body>
        <?php include 'includes/header.php'; ?>
        
            <main class="main-content">
                <!-- CARRUSEL DE NOTICIAS -->
                <section class="news-carousel-section">
                    <div class="container">
                        <div class="carousel-container">
                            <div class="carousel-track" id="newsCarousel">
                                <?php if ($noticias_result && $noticias_result->num_rows > 0): ?>
                                    <?php while ($noticia = $noticias_result->fetch_assoc()): ?>
                                        <div class="carousel-slide" style="background-image: url('images/noticias/<?php echo $noticia['imagen'] ?: 'default-news.jpg'; ?>');">
                                            <div class="slide-overlay"></div>
                                            <div class="slide-content">
                                                <div class="slide-text">
                                                    <span class="slide-category">NOTICIAS</span>
                                                    <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                                                    <p><?php echo htmlspecialchars(substr($noticia['contenido'], 0, 150)) . '...'; ?></p>
                                                    <a href="news.php" class="btn btn-primary">Leer más</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                            <button class="carousel-btn carousel-btn-prev" onclick="moveCarousel(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-btn carousel-btn-next" onclick="moveCarousel(1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <div class="carousel-indicators" id="carouselIndicators"></div>
                        </div>
                    </div>
                </section>
            </main> 

        <?php include 'includes/footer.php'; ?>

        <script>
        // Carrusel de noticias
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            const carousel = document.getElementById('newsCarousel');
            currentSlide = (index + totalSlides) % totalSlides;
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateIndicators();
        }

        function moveCarousel(direction) {
            showSlide(currentSlide + direction);
        }

        function updateIndicators() {
            const indicators = document.getElementById('carouselIndicators');
            indicators.innerHTML = '';
            for (let i = 0; i < totalSlides; i++) {
                const indicator = document.createElement('button');
                indicator.className = `indicator ${i === currentSlide ? 'active' : ''}`;
                indicator.onclick = () => showSlide(i);
                indicators.appendChild(indicator);
            }
        }

        // Auto-play del carrusel
        if (totalSlides > 1) {
            updateIndicators();
            setInterval(() => {
                moveCarousel(1);
            }, 5000);
        }

        // Smooth scrolling para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    </body>
</html>

