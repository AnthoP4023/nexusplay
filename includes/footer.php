<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>NexusPlay</h4>
                <p>Tu tienda de videojuegos favorita. Encuentra los mejores juegos para todas las plataformas.</p>
            </div>
            
            <div class="footer-section">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="games.php">Juegos</a></li>
                    <li><a href="news.php">Noticias</a></li>
                    <li><a href="platform.php">Plataformas</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php">Mi Perfil</a></li>
                        <li><a href="orders.php">Mis Pedidos</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Plataformas</h4>
                <ul>
                    <li><a href="platform.php?platform=pc">PC Gaming</a></li>
                    <li><a href="platform.php?platform=playstation">PlayStation</a></li>
                    <li><a href="platform.php?platform=xbox">Xbox</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Síguenos</h4>
                <div class="social-icons">
                    <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 NexusPlay. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

</body>
</html>