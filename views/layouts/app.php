<?php require 'views/layouts/header.php'; ?>

<?php
if ($_SESSION['user_role'] == 'admin') {
    require 'views/layouts/sidebar_admin.php';
} else {
    require 'views/layouts/sidebar_user.php';
}
?>

<div class="main">

    <main class="content">

        <?php require $page; ?>

    </main>

</div>

<?php require 'views/layouts/footer.php'; ?>