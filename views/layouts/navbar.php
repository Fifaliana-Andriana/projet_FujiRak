<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-2">

    <div class="container-fluid">

        <!-- Bouton Sidebar -->
        <button id="sidebarToggle" class="btn btn-light border me-3">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="assets/images/icons/icone.png" alt="FujiRak" width="200" class="me-2">
        </a>

        <!-- Partie droite -->
        <div class="ms-auto d-flex align-items-center gap-3">

            <!-- Bouton thème -->
            <button class="btn btn-light" id="themeToggle">
                <i class="bi bi-moon-fill fs-6"></i>
            </button>

            <!-- Profil -->
            <div class="dropdown">

                <button class="btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown">

                    <img src="assets/uploads/avatars/default.png" class="rounded-circle border" width="45" height="45"
                        style="object-fit:cover;">

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li class="dropdown-header">
                        <?= $_SESSION['username'] ?? 'Utilisateur'; ?>
                    </li>

                    <li>
                        <a class="dropdown-item" href="index.php?route=user/profile">
                            <i class="bi bi-person"></i>
                            Mon profil
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item text-danger" href="index.php?route=logout">

                            <i class="bi bi-box-arrow-right"></i>
                            Déconnexion

                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>