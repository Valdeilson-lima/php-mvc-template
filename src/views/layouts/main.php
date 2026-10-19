<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($title ?? env('APP_NAME', 'Delivery MVC')) ?></title>
    
    <!-- Tipografia Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Folha de Estilos Principal (public/assets/css/style.css) -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <header>
        <a href="<?= base_url() ?>" class="brand">🚀 <?= e(env('APP_NAME', 'Delivery MVC')) ?></a>
        <nav>
            <a href="<?= base_url() ?>">Início</a>
            <a href="<?= base_url('/sobre') ?>">Sobre</a>
            <a href="<?= base_url('/api/info') ?>">API Info</a>
        </nav>
    </header>

    <main>
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>

        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-error"><?= e($msg) ?></div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> <?= e(env('APP_NAME', 'Delivery MVC')) ?>. Todos os direitos reservados.</p>
    </footer>

    <!-- Script JavaScript Principal (public/assets/js/app.js) -->
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
