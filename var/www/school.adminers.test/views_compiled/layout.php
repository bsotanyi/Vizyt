<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $__sections['title'] ?? '' ?> • <?= e( $_CONFIG['app_name'] ) ?></title>
    <link rel="stylesheet" href="assets/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/lib/aos/aos.css">
    <link rel="stylesheet" href="assets/css/main.min.css?<?= e( microtime(true) ) ?>">
</head>
<body>
    <?php view('components/header'); ?>
<?php view('components/sidenav'); ?>

    <main id="content">
        <?= $__sections['content'] ?? '' ?>
    </main>

    <?php view('components/footer'); ?>

    <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/lucide/lucide.min.js"></script>
    <script src="assets/lib/aos/aos.js"></script>
    <script src="assets/lib/PristineJS/pristine.js"></script>
    <script src="assets/js/main.js?<?= e( microtime(true) ) ?>"></script>
</body>
</html>