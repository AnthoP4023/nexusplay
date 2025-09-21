<?php
require_once __DIR__ . '/controlador_panel/cont_header.php';
?>

<aside class="admin-sidebar">
    <div class="admin-profile-section">
        <div class="admin-avatar">
            <img src="<?php echo htmlspecialchars($admin_avatar); ?>" alt="Avatar Administrador" class="avatar-img">
        </div>
        <div class="admin-badge">Administrador</div>
        <h3 class="admin-name"><?php echo htmlspecialchars($admin_name); ?></h3>
    </div>

    <nav class="admin-navigation">
        <ul class="nav-menu">
            <li><a href="#" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a></li>
            
            <li><a href="#" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a></li>
            
            <li><a href="#" class="nav-item">
                <i class="fas fa-exchange-alt"></i>
                <span>Transacciones</span>
            </a></li>
            
            <li><a href="#" class="nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Pedidos</span>
            </a></li>
            
            <li><a href="#" class="nav-item">
                <i class="fas fa-gamepad"></i>
                <span>Productos</span>
            </a></li>
            
            <li class="nav-divider"></li>
            
            <li><a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a></li>
            
            <li><a href="panel_logout.php" class="nav-item logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a></li>
        </ul>
    </nav>
</aside>