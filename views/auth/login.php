<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$images = [
    '/assets/images/image2.jpg',
    '/assets/images/image3.jpg',
    '/assets/images/image4.jpg'
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FujiRak Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-teal: #04a59d;
            --dark-accent: #171717;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            width: 90%;
            max-width: 1000px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .login-form-side {
            padding: 40px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 140px;
            height: auto;
        }

        h2.title-teal {
            color: var(--primary-teal);
            font-weight: 700;
        }

        .subtitle-dark {
            color: var(--dark-accent);
            font-size: 14px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 3px rgba(4, 165, 157, 0.15);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-accent) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(4, 165, 157, 0.3);
            color: white;
        }

        .error {
            background: #ffe6e6;
            color: #cc0000;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success {
            background: #e6ffe6;
            color: #008000;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .carousel-img {
            height: 550px;
            object-fit: cover;
        }

        a.link-teal {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 600;
        }

        a.link-teal:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="row g-0 d-flex align-items-center text-start">
            
            <!-- Section Formulaire -->
            <div class="col-md-6 login-form-side">
                
                <!-- Logo centré proprement -->
                <div class="text-start mb-4">
                    <img src="../assets/images/logolight.png" alt="FujiRak Logo" class="logo-img">
                </div>

                <h2 class="text-center title-teal mb-1">Connexion</h2>
                <p class="text-center subtitle-dark mb-4">Connectez-vous à votre espace privé</p>

                <!-- Alerts -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?route=login" method="POST" class="d-flex row justify-content-center">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Adresse Email</label>
                        <input type="email" name="email" class="form-control" placeholder="votre@email.com" required autocomplete="email">
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">Mot de passe</label>
                            <a href="index.php?route=edit-password" class="small link-teal">Oublié ?</a>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-login mb-3 w-50">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                    </button>
                </form>

                <!-- <div class="text-center mt-3">
                    <span class="text-muted small">Vous n'avez pas de compte ?</span>
                    <a href="index.php?route=signup" class="small link-teal ms-1">Créer un compte</a>
                </div> -->
            </div>

            <!-- Section Carrousel -->
            <div class="col-md-6 d-none d-md-block">
                <div id="carouselLogin" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach ($images as $index => $image): ?>
                            <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
                        <?php endforeach; ?>
                    </div>

                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= $image ?>" class="d-block w-100 carousel-img" alt="Illustration FujiRak">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselLogin" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselLogin" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>