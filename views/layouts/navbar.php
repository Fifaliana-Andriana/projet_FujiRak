<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-2">

    <div class="container-fluid d-flex justify-content-between h100prc" id="navContainer">
        <!-- Partie gauche -->
        <div class="container w-50 h100prc d-flex align-items-center justify-content-start">
            <!-- Bouton Sidebar -->
            <div class="toogleContainer d-flex justify-content-center align-items-center" style="width:10%">
                <button class="btn btn-light border-0 me-3 text-center bg-transparent">
                    <i id="sidebarToggle" class="bi bi-layout-sidebar lh-5 light"></i>
                </button>
            </div>

            <!-- Logo -->
            <div class="logoContainer d-flex justify-content-start align-items-center">
                <img id="logoTogglelight" src="assets/images/logos/logolight.png" alt="Finixiias" width="160" class="">
                <img id="logoToggledark" src="assets/images/logos/logodark.png" alt="Finixiias" width="160" class="d-none">
            </div>
        </div>

        <!-- Partie droite -->
        <div class="ms-auto d-flex justify-content-end align-items-center gap-3 w-50">

            <!-- Bouton thème -->
            <button class="btn btn-light" id="themeToggle">
                <i class="bi bi-moon-fill fs-6"></i>
            </button>

            <!-- Profil -->
            <div class="dropdown">

                <?php
                $photo = $_SESSION['user_photo'] ?? 'default.png';
                $photoSrc = str_contains($photo, '/') ? $photo : 'assets/uploads/avatars/' . $photo;
                ?>
                <button class="profilUser btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown">

                    <img src="<?= htmlspecialchars($photoSrc) ?>" class="rounded-circle border" width="45" height="45"
                        style="object-fit:cover;" onerror="this.src='assets/images/icons/iconedark.png'">

                </button>

                <ul class="dropdown-menu dropdown-menu-end lineargradient">

                    <li class="dropdown-header text-white">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?>
                        <span class="d-block role-label">
                            <?= ($_SESSION['user_role'] ?? null) === 'admin' ? 'Admin' : 'Utilisateur' ?>
                        </span>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="">
                        <a class="text-white btnProfilStyle" href="index.php?route=user/profile">
                            <i class="bi bi-person"></i>
                            Mon profil
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="">
                        <a class="text-white btnProfilStyle" href="index.php?route=logout">
                            <i class="bi bi-box-arrow-right"></i>
                            Déconnexion
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>