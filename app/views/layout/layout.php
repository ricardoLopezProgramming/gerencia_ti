<style>

</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/styles.css">
    <script>
        var URL_PATH = '<?= URL_PATH ?>';
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- <link rel="shortcut icon" href="../public/assets/images/web/favicon.ico" type="image/x-icon"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="<?= URL_PATH ?>/assets/js/index.js" type="text/JSX"></script>
    <link rel="stylesheet" href="/../../../public/assets/css/styles.css">
</head>

<body>
    <!-- Contenedor principal con Bootstrap row -->
    <div class="container-fluid h-100">
        <!-- Dashboard-->
        <div class="d-flex">

            <?php require_once __DIR__ . '/../sidebar.php'; ?>
            <main class="main-content">
                <div class="bg-primary text-light">div1</div>
                <div class="bg-light d-flex align-items-center">
                    <?php echo $content ?>
                </div>
                <div class="bg-success">div3</div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>