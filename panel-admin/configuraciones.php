<?php
session_start();
require_once '../config_db/database.php';
require_once 'controlador_panel/cont_configuraciones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuraciones - Panel Admin</title>
    <link rel="stylesheet" href="/nexusplay/assests/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css_panel/header.css">
    <link rel="stylesheet" href="css_panel/configuraciones.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="config-container">
            <div class="config-header">
                <h1 class="config-title">
                    <i class="fas fa-cog"></i>
                    Configuraciones
                </h1>
                <p class="config-subtitle">Administra la configuración de tu cuenta</p>
            </div>

            <div class="config-sections">
                <div class="config-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-edit"></i> Información Personal</h3>
                    </div>
                    
                    <?php if (isset($_SESSION['config_message'])): ?>
                        <div class="message <?php echo $_SESSION['config_type']; ?>">
                            <?php echo $_SESSION['config_message']; unset($_SESSION['config_message'], $_SESSION['config_type']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="config-form">
                        <div class="form-group">
                            <label for="username">Usuario</label>
                            <input type="text" id="username" name="username" value="<?php echo isset($admin_data['username']) ? htmlspecialchars($admin_data['username']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="email" id="email" name="email" value="<?php echo isset($admin_data['email']) ? htmlspecialchars($admin_data['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>
                        
                        <button type="submit" name="update_info" class="btn-primary">
                            <i class="fas fa-save"></i> Actualizar Información
                        </button>
                    </form>
                </div>

                <div class="config-card">
                    <div class="card-header">
                        <h3><i class="fas fa-camera"></i> Foto de Perfil</h3>
                    </div>
                    
                    <div class="photo-section">
                        <div class="current-photo">
                            <img src="<?php echo isset($admin_avatar) ? htmlspecialchars($admin_avatar) : '/nexusplay/images/users/default-avatar.png'; ?>" alt="Foto de perfil">
                        </div>
                        
                        <form method="POST" enctype="multipart/form-data" class="photo-form">
                            <div class="file-upload">
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
                                <label for="profile_photo" class="file-label">
                                    <i class="fas fa-upload"></i> Seleccionar Foto
                                </label>
                            </div>
                            <button type="submit" name="update_photo" class="btn-secondary">
                                <i class="fas fa-check"></i> Cambiar Foto
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>