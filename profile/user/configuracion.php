<?php 
include '../../controladores/cont_config_user.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - NexusPlay</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/profile_user.css">
    <link rel="stylesheet" href="../../css/user_config.css">
    <link rel="stylesheet" href="../../assests/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    
    <main class="main-content">
        <div class="user-profile-layout">
            <div class="profile-container">
                <!-- Panel Principal del Usuario -->
                <div class="main-panel">
                    <div class="user-info-container">
                        <div class="avatar-section">
                            <div class="user-avatar">
                                <img src="<?php echo htmlspecialchars($perfil_img); ?>" alt="Mi Perfil" class="avatar-img">
                            </div>
                        </div>
                        
                        <div class="user-info">
                            <h1 class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
                            <span class="user-type">Usuario</span>
                            <p class="user-email"><?php echo htmlspecialchars($user_data['email']); ?></p>
                            <p class="join-date">
                                Miembro desde: <?php echo date('d F Y', strtotime($user_data['fecha_registro'])); ?>
                            </p>
                        </div>
                        
                        <!-- Stats del usuario -->
                        <div class="user-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo $stats['total_pedidos'] ?? 0; ?></div>
                                <div class="stat-label">Pedidos</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">$<?php echo number_format($stats['total_gastado'] ?? 0, 0); ?></div>
                                <div class="stat-label">Gastado</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">$<?php echo number_format($saldo_cartera, 0); ?></div>
                                <div class="stat-label">Cartera</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs de Navegación Desktop -->
                <div class="user-tabs desktop-tabs">
                    <a href="user.php" class="tab-btn">
                        <i class="fas fa-chart-line"></i> Resumen
                    </a>
                    <a href="mis_pedidos.php" class="tab-btn">
                        <i class="fas fa-box"></i> Mis Pedidos
                    </a>
                    <a href="mi_cartera.php" class="tab-btn">
                        <i class="fas fa-wallet"></i> Mi Cartera
                    </a>
                    <a href="mis_tarjetas.php" class="tab-btn">
                        <i class="fas fa-credit-card"></i> Mis Tarjetas
                    </a>
                    <a href="mis_resenas.php" class="tab-btn">
                        <i class="fas fa-star"></i> Mis Reseñas
                    </a>
                    <a href="configuracion.php" class="tab-btn active">
                        <i class="fa-solid fa-gear"></i> Configuraciones
                    </a>
                </div>

                <!-- Selector Móvil -->
                <div class="mobile-selector">
                    <select id="section-select" class="mobile-select" onchange="navigateToSection(this.value)">
                        <option value="user.php">📊 Resumen</option>
                        <option value="mis_pedidos.php">📦 Mis Pedidos</option>
                        <option value="mi_cartera.php">💰 Mi Cartera</option>
                        <option value="mis_tarjetas.php">💳 Mis Tarjetas</option>
                        <option value="mis_resenas.php">⭐ Mis Reseñas</option>
                        <option value="configuracion.php" selected>⚙️ Configuraciones</option>
                    </select>
                </div>

                <!-- Contenido de Configuraciones -->
                <div id="configuraciones" class="tab-content active">
                    <h2 class="section-title">Configuraciones de Cuenta</h2>
                    
                    <div class="config-container">
                        <!-- Sección Actualizar Perfil -->
                        <div class="config-section">
                            <div class="config-header">
                                <h3><i class="fas fa-user-edit"></i> Información Personal</h3>
                                <p>Actualiza tus datos personales</p>
                            </div>
                            
                            <?php if (!empty($profile_message)): ?>
                                <div class="message <?php echo $profile_message_type; ?>">
                                    <i class="fas <?php echo $profile_message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                    <?php echo htmlspecialchars($profile_message); ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" class="config-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="username">
                                            <i class="fas fa-user"></i> Nombre de Usuario
                                        </label>
                                        <input type="text" id="username" name="username" 
                                               value="<?php echo htmlspecialchars($user_data['username']); ?>" 
                                               placeholder="Ingresa tu nombre de usuario" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">
                                            <i class="fas fa-envelope"></i> Correo Electrónico
                                        </label>
                                        <input type="email" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($user_data['email']); ?>" 
                                               placeholder="Ingresa tu correo electrónico" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="nombre">
                                            <i class="fas fa-id-badge"></i> Nombre
                                        </label>
                                        <input type="text" id="nombre" name="nombre" 
                                               value="<?php echo htmlspecialchars($user_data['nombre']); ?>" 
                                               placeholder="Ingresa tu nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellido">
                                            <i class="fas fa-id-badge"></i> Apellido
                                        </label>
                                        <input type="text" id="apellido" name="apellido" 
                                               value="<?php echo htmlspecialchars($user_data['apellido']); ?>" 
                                               placeholder="Ingresa tu apellido" required>
                                    </div>
                                </div>
                                
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Información
                                </button>
                            </form>
                        </div>

                        <!-- Sección Cambiar Contraseña -->
                        <div class="config-section">
                            <div class="config-header">
                                <h3><i class="fas fa-lock"></i> Seguridad de la Cuenta</h3>
                                <p>Cambia tu contraseña para mantener tu cuenta segura</p>
                            </div>
                            
                            <?php if (!empty($password_message)): ?>
                                <div class="message <?php echo $password_message_type; ?>">
                                    <i class="fas <?php echo $password_message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                    <?php echo htmlspecialchars($password_message); ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" class="config-form">
                                <div class="form-group">
                                    <label for="current_password">
                                        <i class="fas fa-key"></i> Contraseña Actual
                                    </label>
                                    <div class="password-input">
                                        <input type="password" id="current_password" name="current_password" 
                                               placeholder="Ingresa tu contraseña actual" required>
                                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye" id="current_password_icon"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="new_password">
                                            <i class="fas fa-lock"></i> Nueva Contraseña
                                        </label>
                                        <div class="password-input">
                                            <input type="password" id="new_password" name="new_password" 
                                                   placeholder="Ingresa tu nueva contraseña" required>
                                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                                <i class="fas fa-eye" id="new_password_icon"></i>
                                            </button>
                                        </div>
                                        <small class="password-help">Mínimo 6 caracteres</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">
                                            <i class="fas fa-lock"></i> Confirmar Contraseña
                                        </label>
                                        <div class="password-input">
                                            <input type="password" id="confirm_password" name="confirm_password" 
                                                   placeholder="Confirma tu nueva contraseña" required>
                                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                                <i class="fas fa-eye" id="confirm_password_icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" name="change_password" class="btn btn-secondary">
                                    <i class="fas fa-shield-alt"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../../includes/footer.php'; ?>

    <script>
        function navigateToSection(url) {
            if (url) {
                window.location.href = url;
            }
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && newPassword !== confirmPassword) {
                this.setCustomValidity('Las contraseñas no coinciden');
                this.classList.add('error');
            } else {
                this.setCustomValidity('');
                this.classList.remove('error');
            }
        });

        document.getElementById('new_password').addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 6) {
                this.setCustomValidity('La contraseña debe tener al menos 6 caracteres');
                this.classList.add('error');
            } else {
                this.setCustomValidity('');
                this.classList.remove('error');
            }
        });
    </script>
</body>
</html>