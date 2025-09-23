<?php
function getGameImagePath($imagen) {
    if (empty($imagen) || $imagen === 'default.jpg') {
        return '/nexusplay/images/juegos/default.jpg';
    }
    
    $ruta = '/nexusplay/images/juegos/' . $imagen;
    $ruta_fisica = $_SERVER['DOCUMENT_ROOT'] . $ruta;
    
    if (file_exists($ruta_fisica)) {
        return $ruta;
    }
    
    return '/nexusplay/images/juegos/default.jpg';
}
?>