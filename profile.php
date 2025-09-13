<?php
session_start();
require_once 'config_db/database.php';
require_once 'functions/fun_auth.php';

// Verificar si el usuario está logueado
if (!isLoggedIn()) {
    header('Location: auth/login.php');
    exit();
}

// Incluir el controlador del perfil
require_once 'controladores/cont_profile.php';

$page_title = "Mi Perfil - NexusPlay";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="profile-main">
        <div class="profile-container">
            <!-- Sidebar de navegación del perfil -->
            <div class="profile-sidebar">
                <div class="profile-user-info">
                    <div class="profile-avatar">
                        <img src="images/users/<?php echo htmlspecialchars($user_data['imagen_perfil']); ?>" 
                             alt="Avatar de <?php echo htmlspecialchars($user_data['username']); ?>"
                             onerror="this.src='images/users/default-avatar.png'">
                    </div>
                    <h2 class="profile-username"><?php echo htmlspecialchars($user_data['username']); ?></h2>
                    <p class="profile-user-type">
                        <?php if ($user_data['tipo_usuario'] === 'admin'): ?>
                            <i class="fas fa-crown"></i> Administrador
                        <?php else: ?>
                            <i class="fas fa-user"></i> Usuario
                        <?php endif; ?>
                    </p>
                </div>

                <nav class="profile-nav">
                    <a href="#info-personal" class="profile-nav-item active" data-tab="info-personal">
                        <i class="fas fa-user-edit"></i> Información Personal
                    </a>
                    
                    <?php if ($user_data['tipo_usuario'] !== 'admin'): ?>
                        <a href="#pedidos" class="profile-nav-item" data-tab="pedidos">
                            <i class="fas fa-box"></i> Mis Pedidos
                        </a>
                        <a href="#tarjetas" class="profile-nav-item" data-tab="tarjetas">
                            <i class="fas fa-credit-card"></i> Métodos de Pago
                        </a>
                        <a href="#reseñas" class="profile-nav-item" data-tab="reseñas">
                            <i class="fas fa-star"></i> Mis Reseñas
                        </a>
                    <?php else: ?>
                        <a href="#estadisticas" class="profile-nav-item" data-tab="estadisticas">
                            <i class="fas fa-chart-bar"></i> Estadísticas
                        </a>
                        <a href="#usuarios" class="profile-nav-item" data-tab="usuarios">
                            <i class="fas fa-users"></i> Gestión Usuarios
                        </a>
                        <a href="#juegos" class="profile-nav-item" data-tab="juegos">
                            <i class="fas fa-gamepad"></i> Gestión Juegos
                        </a>
                    <?php endif; ?>
                    
                    <a href="#configuracion" class="profile-nav-item" data-tab="configuracion">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                </nav>
            </div>

            <!-- Contenido principal del perfil -->
            <div class="profile-content">
                <!-- Información Personal -->
                <div id="info-personal" class="profile-tab-content active">
                    <div class="profile-section">
                        <div class="section-header">
                            <h3><i class="fas fa-user-edit"></i> Información Personal</h3>
                            <button class="btn-edit" id="edit-info-btn">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>

                        <form id="personal-info-form" method="POST" enctype="multipart/form-data">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="username">Nombre de Usuario</label>
                                    <input type="text" id="username" name="username" 
                                           value="<?php echo htmlspecialchars($user_data['username']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($user_data['nombre']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="apellido">Apellido</label>
                                    <input type="text" id="apellido" name="apellido" 
                                           value="<?php echo htmlspecialchars($user_data['apellido']); ?>" readonly>
                                </div>

                                <div class="form-group full-width">
                                    <label for="imagen_perfil">Imagen de Perfil</label>
                                    <input type="file" id="imagen_perfil" name="imagen_perfil" 
                                           accept="image/*" disabled>
                                    <small>Formatos permitidos: JPG, PNG, GIF (máximo 2MB)</small>
                                </div>

                                <div class="form-group full-width">
                                    <label>Miembro desde</label>
                                    <p class="readonly-info">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y', strtotime($user_data['fecha_registro'])); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-actions" style="display: none;">
                                <button type="submit" name="update_info" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <button type="button" class="btn btn-secondary" id="cancel-edit-btn">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($user_data['tipo_usuario'] !== 'admin'): ?>
                    <!-- Pedidos -->
                    <div id="pedidos" class="profile-tab-content">
                        <div class="profile-section">
                            <div class="section-header">
                                <h3><i class="fas fa-box"></i> Mis Pedidos</h3>
                                <div class="filter-buttons">
                                    <button class="filter-btn active" data-filter="all">Todos</button>
                                    <button class="filter-btn" data-filter="completado">Completados</button>
                                    <button class="filter-btn" data-filter="pendiente">Pendientes</button>
                                    <button class="filter-btn" data-filter="cancelado">Cancelados</button>
                                </div>
                            </div>

                            <div class="orders-list">
                                <?php if (empty($user_orders)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-box-open"></i>
                                        <p>No tienes pedidos aún</p>
                                        <a href="index.php" class="btn btn-primary">Explorar Juegos</a>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($user_orders as $order): ?>
                                        <div class="order-card" data-status="<?php echo $order['estado']; ?>">
                                            <div class="order-header">
                                                <div class="order-info">
                                                    <h4>Pedido #<?php echo $order['id']; ?></h4>
                                                    <span class="order-date">
                                                        <i class="fas fa-calendar"></i>
                                                        <?php echo date('d/m/Y H:i', strtotime($order['fecha_pedido'])); ?>
                                                    </span>
                                                </div>
                                                <div class="order-status">
                                                    <span class="status-badge status-<?php echo $order['estado']; ?>">
                                                        <?php
                                                        $status_icons = [
                                                            'pendiente' => 'fas fa-clock',
                                                            'completado' => 'fas fa-check-circle',
                                                            'cancelado' => 'fas fa-times-circle'
                                                        ];
                                                        ?>
                                                        <i class="<?php echo $status_icons[$order['estado']]; ?>"></i>
                                                        <?php echo ucfirst($order['estado']); ?>
                                                    </span>
                                                    <span class="order-total">$<?php echo number_format($order['total'], 2); ?></span>
                                                </div>
                                            </div>

                                            <div class="order-games">
                                                <?php
                                                $order_details = getOrderDetails($conn, $order['id']);
                                                foreach ($order_details as $detail):
                                                ?>
                                                    <div class="order-game">
                                                        <img src="images/juegos/<?php echo htmlspecialchars($detail['imagen']); ?>" 
                                                             alt="<?php echo htmlspecialchars($detail['titulo']); ?>">
                                                        <div class="game-info">
                                                            <h5><?php echo htmlspecialchars($detail['titulo']); ?></h5>
                                                            <p>Cantidad: <?php echo $detail['cantidad']; ?></p>
                                                            <p>Precio: $<?php echo number_format($detail['precio_unitario'], 2); ?></p>
                                                            <?php if ($order['estado'] === 'completado' && !empty($detail['codigo_entregado'])): ?>
                                                                <div class="game-code">
                                                                    <strong>Código: </strong>
                                                                    <code><?php echo htmlspecialchars($detail['codigo_entregado']); ?></code>
                                                                    <button class="copy-code" data-code="<?php echo htmlspecialchars($detail['codigo_entregado']); ?>">
                                                                        <i class="fas fa-copy"></i>
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjetas -->
                    <div id="tarjetas" class="profile-tab-content">
                        <div class="profile-section">
                            <div class="section-header">
                                <h3><i class="fas fa-credit-card"></i> Métodos de Pago</h3>
                                <button class="btn btn-primary" id="add-card-btn">
                                    <i class="fas fa-plus"></i> Agregar Tarjeta
                                </button>
                            </div>

                            <div class="cards-grid">
                                <?php if (empty($user_cards)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-credit-card"></i>
                                        <p>No tienes tarjetas guardadas</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($user_cards as $card): ?>
                                        <div class="payment-card">
                                            <div class="card-header">
                                                <div class="card-type">
                                                    <?php
                                                    $card_number = decryptCardNumber($card['numero_tarjeta']);
                                                    $card_brand = getCardBrand($card_number);
                                                    ?>
                                                    <i class="fab fa-cc-<?php echo strtolower($card_brand); ?>"></i>
                                                    <span><?php echo $card_brand; ?></span>
                                                </div>
                                                <button class="delete-card" data-card-id="<?php echo $card['id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="card-number">
                                                **** **** **** <?php echo substr($card_number, -4); ?>
                                            </div>
                                            <div class="card-details">
                                                <div class="card-expiry">
                                                    <small>Expira</small>
                                                    <span><?php echo htmlspecialchars($card['fecha_expiracion']); ?></span>
                                                </div>
                                                <div class="card-alias">
                                                    <strong><?php echo htmlspecialchars($card['alias'] ?: 'Mi tarjeta'); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Reseñas -->
                    <div id="reseñas" class="profile-tab-content">
                        <div class="profile-section">
                            <div class="section-header">
                                <h3><i class="fas fa-star"></i> Mis Reseñas</h3>
                                <div class="stats-cards">
                                    <div class="stat-card">
                                        <div class="stat-number"><?php echo count($user_reviews); ?></div>
                                        <div class="stat-label">Reseñas</div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-number">
                                            <?php 
                                            $avg_rating = count($user_reviews) > 0 ? 
                                                array_sum(array_column($user_reviews, 'puntuacion')) / count($user_reviews) : 0;
                                            echo number_format($avg_rating, 1);
                                            ?>
                                        </div>
                                        <div class="stat-label">Promedio</div>
                                    </div>
                                </div>
                            </div>

                            <div class="reviews-list">
                                <?php if (empty($user_reviews)): ?>
                                    <div class="no-data">
                                        <i class="fas fa-star-half-alt"></i>
                                        <p>No has escrito reseñas aún</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($user_reviews as $review): ?>
                                        <div class="review-card">
                                            <div class="review-game-info">
                                                <img src="images/juegos/<?php echo htmlspecialchars($review['imagen']); ?>" 
                                                     alt="<?php echo htmlspecialchars($review['titulo']); ?>">
                                                <div class="game-details">
                                                    <h4><?php echo htmlspecialchars($review['titulo']); ?></h4>
                                                    <div class="rating">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?php echo $i <= $review['puntuacion'] ? 'active' : ''; ?>"></i>
                                                        <?php endfor; ?>
                                                        <span class="rating-number"><?php echo $review['puntuacion']; ?>/5</span>
                                                    </div>
                                                    <div class="review-date">
                                                        <i class="fas fa-calendar"></i>
                                                        <?php echo date('d/m/Y', strtotime($review['fecha_resena'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <p><?php echo htmlspecialchars($review['comentario']); ?></p>
                                            </div>
                                            <div class="review-actions">
                                                <button class="btn-edit-review" data-review-id="<?php echo $review['id']; ?>">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                                <button class="btn-delete-review" data-review-id="<?php echo $review['id']; ?>">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Panel de Administrador -->
                    <!-- Estadísticas -->
                    <div id="estadisticas" class="profile-tab-content">
                        <div class="profile-section">
                            <div class="section-header">
                                <h3><i class="fas fa-chart-bar"></i> Estadísticas del Sistema</h3>
                            </div>

                            <div class="admin-stats-grid">
                                <div class="admin-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $admin_stats['total_usuarios']; ?></div>
                                        <div class="stat-label">Usuarios Registrados</div>
                                    </div>
                                </div>

                                <div class="admin-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-gamepad"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $admin_stats['total_juegos']; ?></div>
                                        <div class="stat-label">Juegos en Catálogo</div>
                                    </div>
                                </div>

                                <div class="admin-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $admin_stats['total_pedidos']; ?></div>
                                        <div class="stat-label">Pedidos Totales</div>
                                    </div>
                                </div>

                                <div class="admin-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number">$<?php echo number_format($admin_stats['ingresos_totales'], 2); ?></div>
                                        <div class="stat-label">Ingresos Totales</div>
                                    </div>
                                </div>

                                <div class="admin-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $admin_stats['pedidos_pendientes']; ?></div>
                                        <div class="stat-label">Pedidos Pendientes</div>
                                    </div>
                                </div>

                                <div class="admin-stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="stat-info">
                                        <div class="stat-number"><?php echo $admin_stats['total_reseñas']; ?></div>
                                        <div class="stat-label">Reseñas Escritas</div>
                                    </div>
                                </div>
                            </div>

                            <div class="recent-activity">
                                <h4>Actividad Reciente</h4>
                                <div class="activity-list">
                                    <?php foreach ($admin_stats['actividad_reciente'] as $activity): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <i class="<?php echo $activity['icon']; ?>"></i>
                                            </div>
                                            <div class="activity-content">
                                                <p><?php echo $activity['descripcion']; ?></p>
                                                <small><?php echo date('d/m/Y H:i', strtotime($activity['fecha'])); ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Usuarios -->
                    <div id="usuarios" class="profile-tab-content">
                        <div class="profile-section">
                            <div class="section-header">
                                <h3><i class="fas fa-users"></i> Gestión de Usuarios</h3>
                                <div class="admin-search">
                                    <input type="text" id="search-users" placeholder="Buscar usuarios...">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>

                            <div class="admin-table-container">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Registro</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-table-body">
                                        <?php foreach ($all_users as $user): ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td>
                                                    <div class="user-info">
                                                        <img src="images/users/<?php echo htmlspecialchars($user['imagen_perfil']); ?>" 
                                                             alt="Avatar" class="mini-avatar">
                                                        <?php echo htmlspecialchars($user['username']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></td>
                                                <td>
                                                    <span class="user-type-badge type-<?php echo $user['tipo_usuario']; ?>">
                                                        <?php echo $user['tipo_usuario']; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn-action view-user" data-user-id="<?php echo $user['id']; ?>" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <button class="btn-action edit-user" data-user-id="<?php echo $user['id']; ?>" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn-action delete-user" data-user-id="<?php echo $user['id']; ?>" title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Juegos -->
                    <div id="juegos" class="profile-tab-content">
                        <div class="profile-section">
                            <div class="section-header">
                                <h3><i class="fas fa-gamepad"></i> Gestión de Juegos</h3>
                                <button class="btn btn-primary" id="add-game-btn">
                                    <i class="fas fa-plus"></i> Agregar Juego
                                </button>
                            </div>

                            <div class="admin-filters">
                                <select id="platform-filter">
                                    <option value="">Todas las plataformas</option>
                                    <option value="1">PC</option>
                                    <option value="2">PlayStation</option>
                                    <option value="3">Xbox</option>
                                </select>
                                <select id="category-filter">
                                    <option value="">Todas las categorías</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" id="search-games" placeholder="Buscar juegos...">
                            </div>

                            <div class="games-admin-grid">
                                <?php foreach ($all_games as $game): ?>
                                    <div class="game-admin-card">
                                        <div class="game-image">
                                            <img src="images/juegos/<?php echo htmlspecialchars($game['imagen']); ?>" 
                                                 alt="<?php echo htmlspecialchars($game['titulo']); ?>">
                                            <div class="game-stock <?php echo $game['stock'] < 10 ? 'low-stock' : ''; ?>">
                                                Stock: <?php echo $game['stock']; ?>
                                            </div>
                                        </div>
                                        <div class="game-info">
                                            <h4><?php echo htmlspecialchars($game['titulo']); ?></h4>
                                            <p class="game-price">$<?php echo number_format($game['precio'], 2); ?></p>
                                            <p class="game-platform"><?php echo htmlspecialchars($game['plataforma']); ?></p>
                                            <p class="game-category"><?php echo htmlspecialchars($game['categoria']); ?></p>
                                        </div>
                                        <div class="game-actions">
                                            <button class="btn-action edit-game" data-game-id="<?php echo $game['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-action manage-codes" data-game-id="<?php echo $game['id']; ?>">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <button class="btn-action delete-game" data-game-id="<?php echo $game['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Configuración -->
                <div id="configuracion" class="profile-tab-content">
                    <div class="profile-section">
                        <div class="section-header">
                            <h3><i class="fas fa-cog"></i> Configuración</h3>
                        </div>

                        <div class="config-section">
                            <h4><i class="fas fa-key"></i> Cambiar Contraseña</h4>
                            <form id="change-password-form" method="POST">
                                <div class="form-group">
                                    <label for="current_password">Contraseña Actual</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Nueva Contraseña</label>
                                    <input type="password" id="new_password" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirmar Nueva Contraseña</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" name="change_password" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>

                        <div class="config-section danger-zone">
                            <h4><i class="fas fa-exclamation-triangle"></i> Zona Peligrosa</h4>
                            <p>Estas acciones son irreversibles. Procede con precaución.</p>
                            <?php if ($user_data['tipo_usuario'] !== 'admin'): ?>
                                <button class="btn btn-danger" id="delete-account-btn">
                                    <i class="fas fa-user-times"></i> Eliminar Mi Cuenta
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modales -->
    <!-- Modal para agregar tarjeta -->
    <div id="add-card-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-credit-card"></i> Agregar Nueva Tarjeta</h3>
                <span class="close">&times;</span>
            </div>
            <form id="add-card-form" method="POST">
                <div class="form-group">
                    <label for="card_number">Número de Tarjeta</label>
                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="card_expiry">Fecha de Expiración</label>
                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" required>
                    </div>
                    <div class="form-group">
                        <label for="card_cvv">CVV</label>
                        <input type="text" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_alias">Alias (Opcional)</label>
                    <input type="text" id="card_alias" name="card_alias" placeholder="Mi tarjeta principal">
                </div>
                <div class="modal-actions">
                    <button type="submit" name="add_card" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Tarjeta
                    </button>
                    <button type="button" class="btn btn-secondary close-modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar cuenta -->
    <div id="delete-account-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Eliminar Cuenta</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar tu cuenta? Esta acción es irreversible y perderás:</p>
                <ul>
                    <li>Todos tus pedidos y códigos</li>
                    <li>Tus reseñas y puntuaciones</li>
                    <li>Tus métodos de pago guardados</li>
                    <li>Todo el historial de tu cuenta</li>
                </ul>
                <p><strong>Para confirmar, escribe "ELIMINAR" en el campo de abajo:</strong></p>
                <input type="text" id="delete_confirmation" placeholder="Escribe ELIMINAR">
            </div>
            <div class="modal-actions">
                <button id="confirm-delete-account" class="btn btn-danger" disabled>
                    <i class="fas fa-trash"></i> Eliminar Definitivamente
                </button>
                <button type="button" class="btn btn-secondary close-modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Toast para notificaciones -->
    <div id="toast" class="toast">
        <div class="toast-content">
            <i class="toast-icon"></i>
            <span class="toast-message"></span>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="js/profile.js"></script>
</body>
</html>