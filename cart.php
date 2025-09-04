<div class="nav-container">
        <!-- LOGO -->
        <div class="logo">
            <a href="index.php" class="logo-link">
                <img src="images/Logo/img_logo.png" alt="NexusPlay Logo" class="logo-img">
                <span class="logo-text">NexusPlay</span>
            </a>
        </div>
        

        <!-- BUSCADOR CON PLATAFORMAS DENTRO -->
        <div class="search-with-platforms">

            <!-- Checkbox oculto para controlar expansión -->
            <input type="checkbox" id="toggleSearch" class="search-toggle">

            <!-- Íconos de plataformas -->
            <div class="platforms-inside">
                <a href="platform.php?platform=pc" class="platform-icon">
                    <i class="fas fa-desktop"></i>
                    <span>PC</span>
                </a>
                <a href="platform.php?platform=playstation" class="platform-icon">
                    <i class="fab fa-playstation"></i>
                    <span>PlayStation</span>
                </a>
                <a href="platform.php?platform=xbox" class="platform-icon">
                    <i class="fab fa-xbox"></i>
                    <span>Xbox</span>
                </a>
            </div>

            <!-- Botón lupa (solo para abrir/cerrar buscador) -->
            <input type="checkbox" id="toggleSearch" class="search-toggle">
            <label for="toggleSearch" class="search-trigger-btn">
                <i class="fas fa-search"></i>
            </label>

            <!-- Input de búsqueda -->
            <form class="search-input-form" action="search.php" method="GET">
                <input type="text" name="q" placeholder="Buscar juegos..." class="search-input-field">
                <button type="submit" class="search-submit-icon">
                    <i class="fas fa-search"></i>
                </button>
                <button type="button" class="search-cancel-btn" onclick="document.getElementById('toggleSearch').checked=false;">
                    <i class="fas fa-times"></i>
                </button>
            </form>
        </div>

        <!-- BUSCADOR RESPONSIVE SOLO LUPA -->
<div class="search-responsive">
    <input type="checkbox" id="toggleSearchMobile" class="search-toggle">
    
    <!-- Botón lupa (solo aparece cuando está cerrado) -->
    <label for="toggleSearchMobile" class="search-trigger-btn">
        <i class="fas fa-search"></i>
    </label>

    <!-- Input degradado + botón X -->
    <form class="search-input-form" action="search.php" method="GET">
        <input type="text" name="q" placeholder="Minecraft, RPG, multijugador..." class="search-input-field">
        <button type="button" class="search-cancel-btn" onclick="document.getElementById('toggleSearchMobile').checked=false;">
            <i class="fas fa-times"></i>
        </button>
    </form>
</div>



        <!-- ICONOS NAVEGACIÓN -->
        <div class="nav-icons">
            <!-- Noticias -->
            <a href="news.php" class="nav-icon" title="Noticias">
                <i class="fas fa-newspaper"></i>
                <span>Noticias</span>
            </a>

            <!-- Carrito -->
            <a href="cart.php" class="nav-icon" title="Carrito">
                <i class="fas fa-shopping-cart"></i>
                <span>Carrito</span>
            </a>

            <!-- Login/Perfil -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Mostrar panel de administración si es admin -->
                <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
                    <a href="admin/index.php" class="nav-icon" title="Panel Admin">
                        <i class="fas fa-cogs"></i>
                        <span>Admin</span>
                    </a>
                <?php endif; ?>
                
                <a href="profile.php" class="nav-icon" title="Perfil">
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
                
                <!-- Cerrar Sesión -->
                <a href="auth/logout.php" class="nav-icon logout" title="Cerrar Sesión">
                    <i class="fas fa-door-open"></i>
                    <span>Salir</span>
                </a>
            <?php else: ?>
                <a href="auth/login.php" class="nav-icon" title="Iniciar Sesión">
                    <i class="fas fa-user"></i>
                    <span>Login</span>
                </a>
            <?php endif; ?>
        </div>
    </div>


    <!-- BARRA DE PLATAFORMAS MÓVIL (estática, sin JS) -->
<div class="mobile-platforms-bar">
    <div class="mobile-platforms-content">
        <a href="platform.php?platform=pc" class="mobile-platform-icon">
            <i class="fas fa-desktop"></i>
            <span>PC</span>
        </a>
        <a href="platform.php?platform=playstation" class="mobile-platform-icon">
            <i class="fab fa-playstation"></i>
            <span>PlayStation</span>
        </a>
        <a href="platform.php?platform=xbox" class="mobile-platform-icon">
            <i class="fab fa-xbox"></i>
            <span>Xbox</span>
        </a>
    </div>
</div>































<footer>
    <div class="container">
            <link rel="stylesheet" href="css/footer.css">

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
                    <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
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