<?php
<<<<<<< HEAD:views/auth/login.php
=======
$images = [
    '/assets/images/image2.jpg',
    '/assets/images/image3.jpg',
    '/assets/images/image4.jpg'
];
>>>>>>> 4335f0f059ecbd2058ddbedf0251660817d376e5:views/auth/login_ok.php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FujiRak Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-incons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #lala2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-itmes: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
<<<<<<< HEAD:views/auth/login.php
=======

        .login-form-side {
            padding: 40px;
            background: white;
        }

        .carousel-side {
            padding: 0;
            background: #f8f9fa;
        }

        .carousel-inner img {
            height: 500px;
            object-fit: cover;
        }

        .logocontainer {
            height: auto;
        }

>>>>>>> 4335f0f059ecbd2058ddbedf0251660817d376e5:views/auth/login_ok.php
        .logo {
            text-align: center;
            font-size: 50px;
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
        }
        .subtile {
            text-align: center;
            color: #888;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 3px rgba(15, 52, 96, 0.1);
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 52, 96, 0.4);
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
<<<<<<< HEAD:views/auth/login.php
=======

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            color: #888;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .carousel-side {
                display: none;
            }

            .login-form-side {
                padding: 30px 20px;
            }
        }
>>>>>>> 4335f0f059ecbd2058ddbedf0251660817d376e5:views/auth/login_ok.php
    </style>
</head>

<body>
<<<<<<< HEAD:views/auth/login.php
    <div class="login-card">
        <div class="logo">🔐</div>
        <h2>FujiRak Dashboard</h2>
        <p class="subtitle">Connectez-vous à votre espace</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <i class="bi bi-exclamation-triangle"></i>
                <?php echo $_SESSION['error']; uset($_SESSION['error']); ?>
=======
    <div class="container">
        <div class="row login-container mx-auto" style="max-width: 1000px; height: 100vh">
            <div class="col-md-6 login-form-side">
                <div class="logocontainer d-flex align-items-start">
                    <img src="assets/images/logolight.png" class="logo" alt="FujiRak Logo">
                </div>

                <h2 class="title" style="color: #04a59d">Connexion</h2>
                <p class="subtitle">Connectez-vous avec votre e-mail et votre mot de passe</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>

                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success">
                        <i class="bi bi-check-circle"></i>
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?route=login-submit" method="POST">
                    <div class="input-group-custom">
                        <label for="username">E-mail :</label>
                        <input type="text" id="email" name="email" placeholder="votreemail@gmail.com" required
                            autocomplete="email">
                    </div>

                    <div class="input-group-custom">
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            autocomplete="current-password">
                    </div>
                    <div class="w-100 d-flex justify-content-center align-items-center">
                        <button type="submit"
                            class="btn btn-primary w-75 py-3 d-flex justify-content-center align-items-center"
                            style="background: #04a59d; height: 50px; border: none; border-radius: 10px; font-size: 16px; font-weight: bold; transition: all 0.3s;">
                            <i class="bi bi-box-arrow-in-right" style="width: 20%"></i> Se connecter
                        </button>
                    </div>
                </form>
>>>>>>> 4335f0f059ecbd2058ddbedf0251660817d376e5:views/auth/login_ok.php
            </div>
        <?php endif; ?>

<<<<<<< HEAD:views/auth/login.php
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                <i class="bi bi-check-circle"></i>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
=======
            <div class="col-md-6 carousel-side" style="height:100vh">
                <div id="carouselLogin" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                <img src="<?= $image ?>" class="d-block w-100" alt="Image"
                                    style="height:100vh; object-fit:cover;">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselLogin"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselLogin"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>

                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="0"
                            class="active"></button>
                        <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="2"></button>
                    </div>
                </div>
>>>>>>> 4335f0f059ecbd2058ddbedf0251660817d376e5:views/auth/login_ok.php
            </div>
        <?php endif; ?>


        <form action="index.php?route=login" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="votre@email.com" required autocomplete="email">
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button class="btn-login" type="submit">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>