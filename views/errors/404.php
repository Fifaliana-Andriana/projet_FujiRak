<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page introuvable</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body{
            margin:0;
            padding:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#667eea,#764ba2);
            font-family:Arial, Helvetica, sans-serif;
        }

        .card-404{
            background:#fff;
            padding:50px;
            border-radius:20px;
            text-align:center;
            box-shadow:0 15px 40px rgba(0,0,0,.2);
            max-width:500px;
            width:90%;
        }

        h1{
            font-size:90px;
            font-weight:bold;
            color:#667eea;
            margin-bottom:10px;
        }

        h3{
            color:#333;
            margin-bottom:15px;
        }

        p{
            color:#777;
            margin-bottom:30px;
        }

        .btn-home{
            background:#667eea;
            color:white;
            padding:12px 30px;
            border-radius:10px;
            text-decoration:none;
            transition:.3s;
        }

        .btn-home:hover{
            background:#5a67d8;
            color:white;
        }

        .icon{
            font-size:60px;
            color:#dc3545;
            margin-bottom:20px;
        }
    </style>

</head>
<body>

    <div class="card-404">

        <div class="icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>

        <h1>404</h1>

        <h3>Page introuvable</h3>

        <p>
            La page que vous recherchez n'existe pas ou a été déplacée.
        </p>

        <a href="index.php?route=login" class="btn-home">
            <i class="bi bi-house-door-fill"></i>
            Retour à la connexion
        </a>

    </div>

</body>
</html>