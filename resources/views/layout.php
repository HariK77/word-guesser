<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc_attr(config('app.description')) ?>">
    <title><?= esc_html(config('app.name')) ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a href="<?= base_url() ?>" class="navbar-brand d-flex align-items-center">
                <span style="font-size: 2rem; margin-right: 10px;">🎮</span>
                <div>
                    <h1 class="mb-0" style="font-size: 1.5rem;"><?= esc_html(config('app.name')) ?></h1>
                    <small class="text-muted">v<?= config('app.version') ?></small>
                </div>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="bg-light" style="min-height: 70vh;">
        <?php if (ob_get_level()): ?>
            <?= ob_get_clean(); ?>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-light border-top mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        © <?= date('Y') ?> <?= esc_html(config('app.name')) ?>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        Environment: <strong><?= config('app.env') ?></strong>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
