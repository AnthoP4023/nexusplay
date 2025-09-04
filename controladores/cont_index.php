<?php
session_start();
require_once 'config_db/database.php';

// Obtener noticias recientes para el carrusel
$noticias_query = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC LIMIT 5";
$noticias_result = $conn->query($noticias_query);

// Obtener juegos en tendencia
$tendencias_query = "SELECT j.*, p.nombre as plataforma_nombre FROM juegos j 
                    LEFT JOIN plataformas p ON j.plataforma_id = p.id 
                    ORDER BY j.fecha_agregado DESC LIMIT 6";
$tendencias_result = $conn->query($tendencias_query);

// Obtener juegos recomendados (los mejor valorados)
$recomendados_query = "SELECT j.*, p.nombre as plataforma_nombre, 
                      COALESCE(AVG(r.puntuacion), 0) as promedio_rating
                      FROM juegos j 
                      LEFT JOIN plataformas p ON j.plataforma_id = p.id
                      LEFT JOIN resenas r ON j.id = r.juego_id
                      GROUP BY j.id 
                      ORDER BY promedio_rating DESC, j.fecha_agregado DESC 
                      LIMIT 6";
$recomendados_result = $conn->query($recomendados_query);

// Obtener reseñas recientes
$resenas_query = "SELECT r.*, j.titulo as juego_titulo, u.username, j.imagen
                 FROM resenas r 
                 JOIN juegos j ON r.juego_id = j.id 
                 JOIN usuarios u ON r.usuario_id = u.id 
                 ORDER BY r.fecha_resena DESC 
                 LIMIT 6";
$resenas_result = $conn->query($resenas_query);

// Obtener más vendidos (basado en detalles de pedido)
$mas_vendidos_query = "SELECT j.*, p.nombre as plataforma_nombre, 
                      COUNT(dp.juego_id) as total_vendidos
                      FROM juegos j 
                      LEFT JOIN plataformas p ON j.plataforma_id = p.id
                      LEFT JOIN detalles_pedido dp ON j.id = dp.juego_id
                      LEFT JOIN pedidos pe ON dp.pedido_id = pe.id
                      WHERE pe.estado = 'completado' OR pe.estado IS NULL
                      GROUP BY j.id 
                      ORDER BY total_vendidos DESC, j.fecha_agregado DESC 
                      LIMIT 6";
$mas_vendidos_result = $conn->query($mas_vendidos_query);

// Obtener categorías
$categorias_query = "SELECT c.*, COUNT(j.id) as total_juegos 
                    FROM categorias c 
                    LEFT JOIN juegos j ON c.id = j.categoria_id 
                    GROUP BY c.id 
                    ORDER BY total_juegos DESC";
$categorias_result = $conn->query($categorias_query);
?>