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
    <link rel="stylesheet" href="/nexusplay/assests/fontawesome/css/all.min.css">
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
                                    <i class="fas fa-key dashboard"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

               <ul class="admin-profile-tabs">
                    <li>
                        <a href="/nexusplay/profile/control_panel.php" class="user-link">
                            <div class="fa-solid fa-gauge">
                                <span>Panel de control</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/nexusplay/profile/mis_pedidos.php" class="user-link">Mis Pedidos</a>
                    </li>
                    <li>
                        <a href="/nexusplay/profile/mis_resenas.php" class="user-link">Mis Reseñas</a>
                    </li>
                </ul> 
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>