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
    <link rel="stylesheet" href="/prueba5/assets/fontawesome/css/all.min.css">
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
                                    Registro: <?php echo date('F Y', strtotime($admin_data['fecha_registro'])); ?>
                                </p>
                            </div>
                            
                            <div class="admin-actions">
                                <a href="/prueba5/admin/dashboard.php" class="btn-admin-panel">
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
                    <h2>Acciones Personales</h2>
                    <div class="actions-grid">
                        <div class="action-item"><i class="fas fa-user-edit"></i><span>Editar Perfil</span></div>
                        <div class="action-item"><i class="fas fa-bell"></i><span>Mis Notificaciones</span></div>
                        <div class="action-item"><i class="fas fa-history"></i><span>Actividad Reciente</span></div>
                        <div class="action-item"><i class="fas fa-shield-alt"></i><span>Seguridad</span></div>
                        <div class="action-item"><i class="fas fa-download"></i><span>Mis Descargas</span></div>
                        <div class="action-item"><i class="fas fa-cog"></i><span>Configuración</span></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>