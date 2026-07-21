<?php


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FujiRak Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

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
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-custom input {
            width: 100%;
            padding: 14px 50px 14px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .input-group-custom input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-send {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-send:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .error {
            background: #ffe6e6;
            color: #cc0000;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #cc0000;
            font-size: 14px;
        }

        .success {
            background: #e6ffe6;
            color: #008000;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #008000;
            font-size: 14px;
        }

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


    </style>
</head>
<body>
    <div class="container">
        <div class="row login-container mx-auto" style="max-width: 1000px;">
            <div class="col-md-6 login-form-side">
                <div class="logocontainer">
                    <img src="assets/images/logo1.jpeg" class="logo" alt="FujiRak Logo">
                </div>

                <h2 class="title">Connexion</h2>
                <p class="subtitle">Connectez-vous avec votre nom d'utilisateur et votre mot de passe</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>

                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success">
                        <i class="bi bi-check-circle"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?route=login-submit" method="POST">
                    <div class="input-group-custom">
                        <label for="username">Nom d'utilisateur :</label>
                        <input type="text" id="username" name="username" placeholder="votre.nom.utilisateur" required autocomplete="username">
                    </div>

                    <div class="input-group-custom">
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; font-size: 16px; font-weight: bold; transition: all 0.3s;">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p>Pas encore de compte ? <a href="index.php?route=register">S'inscrire</a></p>
                </div>
            </div>

            <div class="col-md-6 carousel-side">
                <div id="carouselLogin" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/images/image2.jpeg" class="d-block w-100" alt="Dashboard">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/image3.jpeg" class="d-block w-100" alt="Statistiques">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/image4.jpeg" class="d-block w-100" alt="Graphiques">
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselLogin" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselLogin" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>

                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carouselLogin" data-bs-slide-to="2"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>