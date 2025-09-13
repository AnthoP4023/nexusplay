<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (file_exists('functions/fun_auth.php')) {
    require_once 'functions/fun_auth.php';
} elseif (file_exists('../functions/fun_auth.php')) {
    require_once '../functions/fun_auth.php';
}
?>

<header class="header">
    <div class="nav-container">
      <div class="logo">
        <a href="index.php" class="logo-link">
          <img src="images/Logo/img_logo.png" alt="NexusPlay Logo" class="logo-img">
          <span class="logo-text">NexusPlay</span>
        </a>
      </div>

      <div class="search-with-platforms">
        <input type="checkbox" id="toggleSearch" class="search-toggle">
        <div class="platforms-inside">
          <a href="search.php?plataforma=1&categoria=&precio=" class="platform-icon">
            <i class="fas fa-desktop"></i>
            <span>PC</span>
          </a>
          <a href="search.php?plataforma=2&categoria=&precio=" class="platform-icon">
            <i class="fab fa-playstation"></i>
            <span>PlayStation</span>
          </a>
          <a href="search.php?plataforma=3&categoria=&precio=" class="platform-icon">
            <i class="fab fa-xbox"></i>
            <span>Xbox</span>
          </a>
        </div>

        <label for="toggleSearch" class="search-trigger-btn">
            <i class="fas fa-search"></i>
        </label>

        <form class="search-input-form" action="search.php" method="GET">
            <input type="text" name="q" placeholder="Buscar juegos..." class="search-input-field" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
            <button type="submit" class="search-submit-icon">
              <i class="fas fa-search"></i>
             </button>
             <button type="button" class="search-cancel-btn" onclick="document.getElementById('toggleSearch').checked=false;">
              <i class="fas fa-times"></i>
             </button>
        </form>
      </div>

      <div class="search-responsive">
          <input type="checkbox" id="toggleSearchMobile" class="search-toggle" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
          <label for="toggleSearchMobile" class="search-trigger-btn">
              <i class="fas fa-search"></i>
          </label>
          <form class="search-input-form" action="search.php" method="GET">
              <input type="text" name="q" placeholder="Minecraft, RPG, multijugador..." class="search-input-field">
              <button type="button" class="search-cancel-btn" onclick="document.getElementById('toggleSearchMobile').checked=false;">
                  <i class="fas fa-times"></i>
              </button>
          </form>
      </div>
      
        <div class="nav-icons">
            <div class="header-cart">
                <a href="cart.php" class="cart-link nav-icon" title="Carrito" >
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count" class="cart-count">
                        <?php echo isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0; ?>
                    </span>
                  </a>
                </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="nav-profile">
                    <input type="checkbox" id="profileMenuToggle" class="profile-toggle">
                    <label for="profileMenuToggle" class="profile-btn nav-icon">
                        <i class="fas fa-user"></i>
                    </label>
                    
                    <div class="profile-menu">
                        <div class="profile-username">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </div>

                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                            <a href="profile.php"><i class="fas fa-id-card"></i> Perfil Admin</a>
                            <a href="index.php" class="admin" target="_blank"><i class="fas fa-cogs"></i> Panel Admin</a>
                        <?php else: ?>
                            <a href="profile.php"><i class="fas fa-id-card"></i> Mi Perfil</a>
                            <a href="orders.php" class="pedidos"><i class="fas fa-box"></i> Mis Pedidos</a>
                            <a href="reviews.php" class="reseña"><i class="fas fa-star"></i> Mis Reseñas</a>
                        <?php endif; ?>

                        <a href="auth/logout.php" class="logout"><i class="fas fa-door-open"></i> Salir</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="auth/login.php" class="nav-icon" title="Iniciar Sesión">
                    <i class="fas fa-user"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="mobile-platforms-bar">
    <div class="mobile-platforms-content">
        <a href="search.php?plataforma=1&categoria=&precio=" class="mobile-platform-icon">
            <i class="fas fa-desktop"></i>
            <span>PC</span>
        </a>
        <a href="search.php?plataforma=2&categoria=&precio=" class="mobile-platform-icon">
            <i class="fab fa-playstation"></i>
            <span>PlayStation</span>
        </a>
        <a href="search.php?plataforma=3&categoria=&precio=" class="mobile-platform-icon">
            <i class="fab fa-xbox"></i>
            <span>Xbox</span>
        </a>
    </div>
</div>


<script>
    document.addEventListener('click', function(event) {
    const profileToggle = document.getElementById('profileMenuToggle');
    const profileMenu = document.querySelector('.nav-profile');

    // Solo ejecutar si ambos elementos existen
    if (profileMenu && profileToggle) {
        if (!profileMenu.contains(event.target)) {
            profileToggle.checked = false;
        }
    }
});
</script>

<script>
    let lastScrollY = window.scrollY;
    const mobileBar = document.querySelector('.mobile-platforms-bar');
    window.addEventListener('scroll', () => {
    if (!mobileBar) return;
        if (window.scrollY > lastScrollY) {
            mobileBar.classList.add('hidden');
        } else {
            mobileBar.classList.remove('hidden');
        }  
    lastScrollY = window.scrollY;
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.search-input-field');
    const searchToggle = document.querySelector('.search-toggle');

    if (searchInput && searchToggle && searchInput.value.trim() !== '') {
        searchToggle.checked = true;
    }
});
</script>

