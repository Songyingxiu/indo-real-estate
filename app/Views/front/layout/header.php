<?php
    $isLoggedIn = session()->get('id') ? true : false;
    $roleId = session()->get('role_id');
    $firstName = session()->get('first_name') ?? 'User';
    $lastName = session()->get('last_name') ?? '';
    $email = session()->get('email') ?? '';
    $fullName = trim($firstName . ' ' . $lastName);
    $initial = strtoupper(substr($firstName, 0, 1));
    
    $notifications = $GLOBALS['global_notifications'] ?? [];
    $unreadCount = $GLOBALS['unread_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'en' ?>">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <?php 
        $seoModel = new \App\Models\SeoModel();
        $globalSeo = $seoModel->where('target_page', 'Homepage')->first();
        
        $metaTitle = $title ?? $globalSeo['meta_title'] ?? 'HuniKita - Real Estate Platform';
        $metaDesc = $globalSeo['meta_description'] ?? 'Discover the most exclusive real estate listings. Buy, sell, and rent properties with trusted verified agents.';
        $keywords = $globalSeo['focus_keywords'] ?? 'real estate, buy house, verified agents, property listing';
    ?>
    <title><?= esc($metaTitle) ?></title>
    <meta name="description" content="<?= esc($metaDesc) ?>">
    <meta name="keywords" content="<?= esc($keywords) ?>">
    
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-high": "#e5e9eb",
                        "primary-container": "#1a365d",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#74777f",
                        "background": "#f7fafc",
                        "on-surface-variant": "#43474e",
                        "on-surface": "#181c1e",
                        "surface": "#f7fafc",
                        "on-background": "#181c1e",
                        "on-primary": "#ffffff",
                        "primary": "#002045",
                        "error": "#ba1a1a"
                    },
                    fontFamily: {
                        "headline-xl": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg-mobile": ["Plus Jakarta Sans", "sans-serif"],
                        "brand-text": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Inter", "sans-serif"],
                        "body-md": ["Inter", "sans-serif"],
                        "label-md": ["Inter", "sans-serif"],
                        "caption": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
        .property-card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
        .property-card:hover { box-shadow: 0px 4px 20px rgba(26, 54, 93, 0.08); transform: translateY(-2px); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f7fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c4c6cf; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #74777f; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

<header x-data="{ mobileMenuOpen: false }" class="bg-surface border-b border-outline-variant w-full z-50 sticky top-0 relative">
    
    <!-- Main Navbar Container -->
    <div class="flex justify-between items-center h-20 px-4 md:px-10 w-full max-w-[1280px] mx-auto bg-surface relative z-50">
        
        <a href="<?= base_url() ?>" class="flex items-center gap-2">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="HuniKita Logo" class="w-10 h-10 rounded-full object-cover border border-outline-variant shadow-sm bg-white" onerror="this.outerHTML='<div class=\'w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold\'>H</div>'">
            <span class="font-brand-text text-[24px] font-bold tracking-tight text-primary">HuniKita</span>
        </a>
        
        <!-- Desktop Nav Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('search/sale') ?>"><?= lang('Front.nav_buy') ?></a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('search/rent') ?>"><?= lang('Front.nav_rent') ?></a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('page/about-us') ?>"><?= lang('Front.nav_about') ?></a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('news') ?>"><?= lang('Front.nav_news') ?></a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('page/privacy-policy') ?>"><?= lang('Front.nav_privacy') ?></a>
        </nav>
        
        <!-- Desktop Auth & Actions -->
        <div class="hidden md:flex items-center gap-4">
            <!-- Allow ALL logged-in users to post properties -->
            <?php if($isLoggedIn): ?>
                <a href="<?= base_url('admin/properties/create') ?>" class="font-label-md text-[14px] font-semibold text-primary bg-transparent border border-primary px-4 py-2 rounded hover:bg-surface-container-high transition-colors">
                    <?= lang('Front.btn_post_listing') ?>
                </a>
            <?php endif; ?>

            <?php if($isLoggedIn): ?>
                <a href="<?= base_url('user/saved-properties') ?>" class="relative text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer" title="Saved Properties">
                    <span class="material-symbols-outlined">favorite</span>
                </a>

                <!-- Desktop Notifications -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
                        <?php endif; ?>
                    </button>
                    
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-80 bg-surface rounded-lg shadow-lg border border-outline-variant overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                            <span class="font-label-md text-label-md text-on-surface font-bold"><?= lang('Front.lbl_activity') ?></span>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <?php if (empty($notifications)): ?>
                                <div class="px-4 py-6 text-center text-on-surface-variant">
                                    <p class="font-caption text-caption"><?= lang('Front.lbl_no_activity') ?></p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <a href="<?= base_url('admin/inquiries') ?>" class="block px-4 py-3 hover:bg-surface-container transition-colors border-b border-outline-variant/50 relative">
                                        <p class="font-label-md text-[13px] text-on-surface font-bold truncate">
                                            <?= esc($notif->property_title) ?>
                                        </p>
                                        <p class="font-caption text-caption text-on-surface-variant">
                                            <?= 'New inquiry received.' ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Desktop Profile Dropdown -->
                <div x-data="{ open: false }" class="relative ml-2">
                    <button @click="open = !open" @click.outside="open = false" class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm cursor-pointer border-2 border-outline-variant hover:border-primary transition-colors">
                        <?= esc($initial) ?>
                    </button>
                    
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-56 bg-surface rounded-lg shadow-lg border border-outline-variant overflow-hidden z-50 py-1">
                        <div class="px-4 py-3 border-b border-outline-variant mb-1">
                            <p class="font-label-md text-label-md text-on-surface truncate font-bold"><?= esc($fullName) ?></p>
                            <p class="font-caption text-caption text-on-surface-variant truncate"><?= esc($email) ?></p>
                        </div>
                        
                        <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">dashboard</span>
                            <span class="font-body-sm text-body-sm"><?= lang('Front.menu_dashboard') ?></span>
                        </a>

                        <a href="<?= base_url($roleId == 1 ? 'user/profile' : 'admin/profile') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                            <span class="font-body-sm text-body-sm"><?= lang('Front.menu_profile') ?></span>
                        </a>

                        <div class="border-t border-outline-variant my-1"></div>

                        <a href="<?= base_url('admin/properties') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                            <span class="font-body-sm text-body-sm">My Properties</span>
                        </a>

                        <a href="<?= base_url('admin/inquiries') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">inbox</span>
                            <span class="font-body-sm text-body-sm">Received Leads</span>
                        </a>

                        <a href="<?= base_url('user/inbox') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            <span class="font-body-sm text-body-sm">Sent Inquiries</span>
                        </a>

                        <div class="border-t border-outline-variant mt-1 pt-1">
                            <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 px-4 py-2 text-error hover:bg-error-container transition-colors">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span class="font-label-md text-label-md"><?= lang('Front.btn_sign_out') ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="font-label-md text-[14px] font-semibold text-on-primary bg-primary px-4 py-2 rounded hover:bg-primary-container transition-colors">
                    <?= lang('Front.btn_sign_in') ?>
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Mobile Menu Hamburger Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden flex items-center justify-center w-10 h-10 text-primary hover:bg-surface-container-low transition-colors rounded-full">
            <span class="material-symbols-outlined" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
        </button>
    </div>

    <!-- Mobile Navigation Dropdown Menu -->
    <div x-show="mobileMenuOpen" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @click.outside="mobileMenuOpen = false"
         class="md:hidden absolute top-full left-0 w-full bg-surface border-b border-outline-variant shadow-xl z-40 max-h-[calc(100vh-80px)] overflow-y-auto">
        
        <nav class="flex flex-col px-4 py-4 gap-1 border-b border-outline-variant">
            <a class="font-label-md text-[16px] text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded transition-colors" href="<?= base_url('search/sale') ?>"><?= lang('Front.nav_buy') ?></a>
            <a class="font-label-md text-[16px] text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded transition-colors" href="<?= base_url('search/rent') ?>"><?= lang('Front.nav_rent') ?></a>
            <a class="font-label-md text-[16px] text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded transition-colors" href="<?= base_url('page/about-us') ?>"><?= lang('Front.nav_about') ?></a>
            <a class="font-label-md text-[16px] text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded transition-colors" href="<?= base_url('news') ?>"><?= lang('Front.nav_news') ?></a>
            <a class="font-label-md text-[16px] text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded transition-colors" href="<?= base_url('page/privacy-policy') ?>"><?= lang('Front.nav_privacy') ?></a>
        </nav>

        <div class="flex flex-col gap-4 px-6 py-6">
            <?php if($isLoggedIn): ?>
                <a href="<?= base_url('admin/properties/create') ?>" class="w-full text-center font-label-md text-[15px] font-semibold text-primary bg-transparent border border-primary px-4 py-3 rounded-lg hover:bg-surface-container-high transition-colors">
                    <?= lang('Front.btn_post_listing') ?>
                </a>

                <div class="grid grid-cols-4 gap-2 border border-outline-variant rounded-xl p-2 bg-surface-container-lowest">
                     <a href="<?= base_url('user/saved-properties') ?>" class="flex flex-col items-center justify-center gap-1 text-on-surface-variant p-2 rounded-lg hover:text-primary hover:bg-surface-container-low transition-colors text-center">
                         <span class="material-symbols-outlined text-[22px]">favorite</span>
                         <span class="text-[10px] font-bold mt-1">Saved</span>
                     </a>
                     <a href="<?= base_url('admin/properties') ?>" class="flex flex-col items-center justify-center gap-1 text-on-surface-variant p-2 rounded-lg hover:text-primary hover:bg-surface-container-low transition-colors text-center">
                         <span class="material-symbols-outlined text-[22px]">list_alt</span>
                         <span class="text-[10px] font-bold mt-1">Listings</span>
                     </a>
                     <a href="<?= base_url('admin/inquiries') ?>" class="relative flex flex-col items-center justify-center gap-1 text-on-surface-variant p-2 rounded-lg hover:text-primary hover:bg-surface-container-low transition-colors text-center">
                         <span class="material-symbols-outlined text-[22px]">inbox</span>
                         <?php if ($unreadCount > 0): ?>
                             <span class="absolute top-1 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface-container-lowest"></span>
                         <?php endif; ?>
                         <span class="text-[10px] font-bold mt-1">Leads</span>
                     </a>
                     <a href="<?= base_url('user/inbox') ?>" class="flex flex-col items-center justify-center gap-1 text-on-surface-variant p-2 rounded-lg hover:text-primary hover:bg-surface-container-low transition-colors text-center">
                         <span class="material-symbols-outlined text-[22px]">send</span>
                         <span class="text-[10px] font-bold mt-1">Sent</span>
                     </a>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-1">
                     <a href="<?= base_url('admin/dashboard') ?>" class="w-full text-center font-label-md text-[14px] font-semibold text-on-surface-variant bg-surface-container-low px-4 py-2.5 rounded-lg hover:bg-surface-container-high transition-colors">
                         Dashboard
                     </a>
                     <a href="<?= base_url($roleId == 1 ? 'user/profile' : 'admin/profile') ?>" class="w-full text-center font-label-md text-[14px] font-semibold text-on-surface-variant bg-surface-container-low px-4 py-2.5 rounded-lg hover:bg-surface-container-high transition-colors">
                         Profile
                     </a>
                </div>

                <a href="<?= base_url('logout') ?>" class="w-full flex justify-center items-center gap-2 font-label-md text-[15px] font-semibold text-error bg-error-container/10 px-4 py-3 rounded-lg hover:bg-error-container/20 transition-colors mt-2">
                    <span class="material-symbols-outlined text-[20px]">logout</span> <?= lang('Front.btn_sign_out') ?>
                </a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="w-full text-center font-label-md text-[15px] font-bold text-on-primary bg-primary px-4 py-3 rounded-lg hover:bg-primary-container transition-colors shadow-sm">
                    <?= lang('Front.btn_sign_in') ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<?= $this->renderSection('content') ?>

</body>
</html>