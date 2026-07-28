<?php $currentRoute = $_GET['route'] ?? ''; ?>

<aside class="sidebar" id="sidebar">

    <a href="index.php?route=user/dashboard"
       class="sidebar-link <?= $currentRoute === 'user/dashboard' ? 'active' : 'bolder' ?>">
        <i class="bi bi-house-door"></i>
        <span>Accueil</span>
    </a>

    <a href="index.php?route=user/profile"
       class="sidebar-link <?= $currentRoute === 'user/profile' ? 'active' : 'bolder' ?>">
        <i class="bi bi-person-circle"></i>
        <span>Profil</span>
    </a>

    <a href="index.php?route=user/history"
       class="sidebar-link <?= $currentRoute === 'user/history' ? 'active' : 'bolder' ?>">
        <i class="bi bi-clock-history"></i>
        <span>Historique</span>
    </a>

    <a href="index.php?route=user/documents"
       class="sidebar-link <?= $currentRoute === 'user/documents' ? 'active' : 'bolder' ?>">
        <i class="bi bi-file-earmark-arrow-down"></i>
        <span>Mes documents</span>
    </a>

    <a href="index.php?route=logout" class="sidebar-link sidebar-link-danger">
        <i class="bi bi-box-arrow-right"></i>
        <span class="bolder">Déconnexion</span>
    </a>

</aside>
