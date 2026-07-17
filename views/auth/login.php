<?php
$images = [
    '/assets/images/image1.jpeg',
    '/assets/images/image2.jpeg',
    '/assets/images/image3.jpeg'
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container w-100 mt-5 d-flex">
        <div class="row justify-content-center w-50">
            <div class="d-flex justify-content-center">
                <img src="../assets/images/logo1.jpeg" class="w-50" alt="">
            </div>
            <div class="w-100 card-body">

                <form action="controllers/AuthController.php" class="d-flex justify-content-center align-items-center" method="POST">

                    <div class="w-75 mb-3 d-flex">
                        <label class="w-25 form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" placeholder="jeanpaul123@gmail.com"
                            required>
                    </div>

                    <button id="btntoverify" type="submit" name="send_code" class="btn">
                        <i class="bi bi-arrow-right-square"></i>
                    </button>
                </form>

            </div>
        </div>
        <div class="slide w-50">
            <img src="../assets/images/image1.jpeg" alt="">
        </div>
    </div>

</body>

<script>
    const btntoverify = document.getElementById('btntoverify');
    btntoverify.addEventListener('click', ()=>{
        window.local.href= 'views/auth/verify.php'
    })
</script>

</html>