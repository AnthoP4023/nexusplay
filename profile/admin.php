<?php 
include '../controladores/cont_profile.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrador</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="/nexusplay/assets/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="main-content">
        <div class="admin-profile-layout">
            <div class="profile-container">
                <div class="main-panel">
                    <div class="admin-info-container">
                        <div class="avatar-section">
                            <div class="admin-avatar">
                                <img src="<?php echo htmlspecialchars($perfil_img); ?>" alt="Perfil Admin" class="avatar-img">
                            </div>
                        </div>
                        
                        <div class="info-and-button">
                            <div class="user-info">
                                <h1 class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                                <p class="user-type">Administrador</p>
                                <p class="user-email"><?php echo htmlspecialchars($admin_data['email']); ?></p>
                                <p class="join-date">
                                    Miembro desde: <?php echo date('d F Y', strtotime($admin_data['fecha_registro'])); ?>
                                </p>
                            </div>
                            
                            <div class="admin-actions">
                                <a href="/panel-control/login.php" class="btn-admin-panel" target="_blank">
                                    <i class="fas fa-cogs"></i> Panel de Administrador
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="password-container">
                        <div class="password-change">
                            <h3>Cambiar Contraseña</h3>
                            
                            <?php if (!empty($password_message)): ?>
                                <div class="password-message <?php echo $password_message_type; ?>">
                                    <?php echo htmlspecialchars($password_message); ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="change_password.php" class="password-form">
                                <div class="password-inputs">
                                    <input type="password" name="current_password" placeholder="Contraseña actual" required>
                                    <input type="password" name="new_password" placeholder="Nueva contraseña" required>
                                    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                                </div>
                                <button type="submit" class="btn-change-password">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="personal-actions">
    <h2>Privilegios de Administrador</h2>
    <div class="actions-grid">
        <!-- Moderar reseñas directamente desde la aplicación -->
        <a href="/nexusplay/admin/moderate-reviews.php" class="action-item">
            <i class="fas fa-gavel"></i>
            <span>Moderar Reseñas</span>
        </a>
        
        <!-- Ver perfiles de todos los usuarios -->
        <a href="/nexusplay/admin/view-users.php" class="action-item">
            <i class="fas fa-users"></i>
            <span>Ver Usuarios</span>
        </a>
        
        <!-- Ver todos los pedidos del sistema -->
        <a href="/nexusplay/admin/all-orders.php" class="action-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Todos los Pedidos</span>
        </a>
        
        <!-- Cambiar estado de pedidos (completar, cancelar) -->
        <a href="/nexusplay/admin/manage-orders.php" class="action-item">
            <i class="fas fa-edit"></i>
            <span>Gestionar Pedidos</span>
        </a>
        
        <!-- Ver reportes y estadísticas del sitio -->
        <a href="/nexusplay/admin/reports.php" class="action-item">
            <i class="fas fa-chart-pie"></i>
            <span>Reportes</span>
        </a>
        
        <!-- Gestionar códigos de juegos -->
        <a href="/nexusplay/admin/game-codes.php" class="action-item">
            <i class="fas fa-key"></i>
            <span>Códigos de Juegos</span>
        </a>
        
        <!-- Suspender/activar usuarios -->
        <a href="/nexusplay/admin/user-status.php" class="action-item">
            <i class="fas fa-user-slash"></i>
            <span>Estado de Usuarios</span>
        </a>
        
        <!-- Ver logs de actividad del sistema -->
        <a href="/nexusplay/admin/activity-logs.php" class="action-item">
            <i class="fas fa-file-alt"></i>
            <span>Logs del Sistema</span>
        </a>
        
        <!-- Gestionar contenido destacado -->
        <a href="/nexusplay/admin/featured-content.php" class="action-item">
            <i class="fas fa-star"></i>
            <span>Contenido Destacado</span>
        </a>
    </div>
</div>
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>