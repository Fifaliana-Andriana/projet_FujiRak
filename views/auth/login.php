<?php
$images = [
    '/assets/images/image2.jpeg',
    '/assets/images/image3.jpeg',
    '/assets/images/image4.jpeg'
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body {
        margin: 0;
        padding: 0;
    }
</style>

<body>
    <div class="container mt-5 d-flex justify-content-evenly">
        <div class="row justify-content-center rounded hidden bg-primary-subtle mx-auto">
            <div class="d-flex justify-content-center logocontainer">
                <img src="../assets/images/logo1.jpeg" class="logo w-75" alt="Logo">
            </div>
            <div class="w-100">

                <form action="controllers/AuthController.php"
                    class="d-flex justify-content-center align-items-center" method="POST">


                    <div class="w-75 mb-3 d-flex justify-content-center align-items-center">
                        <label class="w-25 form-label">E-mail:</label>
                        <input type="email" name="email" class="form-control" placeholder="jeanpaul123@gmail.com"
                            required>
                        <button id="btntoverify" type="submit" name="send_code" class="btn">
                            <i class="bi bi-arrow-right-square"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="slide rounded hidden">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                            <img src="<?= $image ?>" class="d-block w-100" alt="Image"
                                style="height:400px; object-fit:cover;">
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const btntoverify = document.getElementById('btntoverify');
    btntoverify.addEventListener('click', () => {
        window.local.href = 'views/auth/verify.php'
    })
</script>

</html>