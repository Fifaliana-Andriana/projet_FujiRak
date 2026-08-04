<!DOCTYPE html>
<html lang="fr">

<head>
    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('finixiias-theme');
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('theme-dark');
                }
            } catch (e) { }
        })();
    </script>

    <script>
        (function () {
            try {
                var collapsed = localStorage.getItem('sidebarCollapsed');
                if (collapsed === '1' || (collapsed === null && window.innerWidth <= 768)) {
                    document.documentElement.classList.add('sidebar-collapsed-init');
                }
            } catch (e) { }
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Finixiias</title>
    <link rel="icon" type="image/png" href="assets/images/icons/iconelight.png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS du projet -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

    <?php require 'views/layouts/navbar.php'; ?>