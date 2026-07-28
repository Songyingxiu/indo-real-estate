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

    <div class="flex-1 md:ml-64 min-h-screen flex flex-col relative">
        <?= $this->include('admin/layout/header') ?>

        <!-- GLOBAL BEAUTIFUL TOAST ALERTS -->
        <div class="fixed top-20 right-8 z-[999] flex flex-col gap-3 pointer-events-none">
            <?php if (session()->getFlashdata('success')) : ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="bg-surface-container-lowest border-l-4 border-primary shadow-xl rounded p-4 flex items-start gap-3 pointer-events-auto max-w-sm">
                    <span class="material-symbols-outlined text-primary">check_circle</span>
                    <div>
                        <h4 class="font-bold text-sm text-on-surface">Success</h4>
                        <p class="text-xs text-on-surface-variant mt-0.5"><?= session()->getFlashdata('success') ?></p>
                    </div>
                    <button @click="show = false" class="ml-auto text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="bg-surface-container-lowest border-l-4 border-error shadow-xl rounded p-4 flex items-start gap-3 pointer-events-auto max-w-sm">
                    <span class="material-symbols-outlined text-error">error</span>
                    <div>
                        <h4 class="font-bold text-sm text-on-surface">Action Failed</h4>
                        <p class="text-xs text-on-surface-variant mt-0.5"><?= session()->getFlashdata('error') ?></p>
                    </div>
                    <button @click="show = false" class="ml-auto text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </div>
            <?php endif; ?>
        </div>

        <main class="flex-1 p-margin-mobile md:p-margin-desktop w-full max-w-container-max mx-auto overflow-y-auto">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</body>
</html>