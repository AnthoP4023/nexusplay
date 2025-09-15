<?php 
include '../controladores/cont_user_profile.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="/nexusplay/assests/fontawesome/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main class="main-content">
        <div class="admin-profile-layout" id="ig-profile-main-panel">
            <div class="profile-container">
                <div class="main-panel">
                    <div class="avatar-card">
                        <div class="admin-avatar">

                        </div>
                        <div class="admin-link">

                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0; 
}

.profile-container {
    --separator: 80px;
    align-self: center;
    display: flex;
    flex-direction: column;
    gap: var(--separator);
    margin-top: 190px;
    max-width: 1200px;
    width: 100%;
}

.admin-profile-layout {
    display: flex;
    flex-direction: column;
}

.profile-container .main-panel {
    align-items: center;
    display: flex;
    justify-content: space-between;
    margin-right: 0 !important;
    margin-top: 0 !important;
    position: relative;
}

.profile-container .main-panel .avatar-card .admin-avatar {
    position: relative;
    width: 120px;
}

.profile-container .main-panel .avatar-card .admin-links {
    --gap-small: 15px;
    display: flex;
    flex-direction: column;
    gap: var(--gap-small);
    margin-bottom: 0 !important;
}