<?php

$userRole = $_SESSION['user_role'] ?? null;

?>
<!DOCTYPE html>

<html lang="fr">

<head>

    <?php require __DIR__ . '/header.php'; ?>

</head>

<body>

<div class="app">

    <?php if ($userRole === 'admin'): ?>

        <?php require __DIR__ . '/sidebar_admin.php'; ?>

    <?php elseif ($userRole === 'user'): ?>

        <?php require __DIR__ . '/sidebar_user.php'; ?>

    <?php endif; ?>

    <div class="main">

        <?php require __DIR__ . '/navbar.php'; ?>

        <main class="content">

            <?php

            if (isset($page) && file_exists($page)) {

                require $page;

            } else {

                require __DIR__ . '/../errors/404.php';

            }

            ?>

        </main>

        <?php require __DIR__ . '/footer.php'; ?>

    </div>

</div>

</body>

</html>