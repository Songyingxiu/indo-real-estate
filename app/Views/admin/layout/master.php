<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'EstateAdmin Pro' ?></title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css') ?>">
    <script src="<?= base_url('assets/js/tailwind-config.js') ?>"></script>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-background text-on-surface font-body-md min-h-screen flex text-body-md transition-colors duration-300">
    
    <?= $this->include('admin/layout/sidebar') ?>

    <div class="flex-1 md:ml-64 min-h-screen flex flex-col">
        <?= $this->include('admin/layout/header') ?>

        <main class="flex-1 p-margin-mobile md:p-margin-desktop w-full max-w-container-max mx-auto overflow-y-auto">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</body>
</html>