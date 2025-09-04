<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir funciones si existe el archivo
if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
} elseif (file_exists('../includes/functions.php')) {
    require_once '../includes/functions.php';
}
?>

<header class="header">
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
</header>

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
