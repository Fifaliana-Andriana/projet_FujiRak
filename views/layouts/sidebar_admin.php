<?php $currentRoute = $_GET['route'] ?? ''; ?>

<aside class="sidebar" id="sidebar">

    <a href="index.php?route=admin/dashboard"
       class="sidebar-link <?= $currentRoute === 'admin/dashboard' ? 'active' : 'bolder' ?>">
        <i class="bi bi-house-door"></i>
        <span>Accueil</span>
    </a>

    <a href="index.php?route=admin/statistics"
       class="sidebar-link <?= $currentRoute === 'admin/statistics' ? 'active' : 'bolder' ?>">
        <i class="bi bi-graph-up"></i>
        <span>Statistique</span>
    </a>

    <a href="index.php?route=admin/users"
       class="sidebar-link <?= in_array($currentRoute, ['admin/users', 'admin/create-user', 'admin/edit-user']) ? 'active' : 'bolder' ?>">
        <i class="bi bi-people"></i>
        <span>Utilisateurs</span>
    </a>

    <a href="index.php?route=admin/factures"
       class="sidebar-link <?= $currentRoute === 'admin/factures' ? 'active' : 'bolder' ?>">
        <i class="bi bi-file-earmark-arrow-up"></i>
        <span>Facturation</span>
    </a>

    <a href="index.php?route=logout" class="sidebar-link sidebar-link-danger">
        <i class="bi bi-box-arrow-right"></i>
        <span class="bolder">Déconnexion</span>
    </a>

</aside>
