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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <?php 
        $seoModel = new \App\Models\SeoModel();
        $globalSeo = $seoModel->where('target_page', 'Homepage')->first();
        
        // If a specific controller passed a $title, use it. Otherwise, fallback to the DB SEO settings.
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

<header class="bg-surface border-b border-outline-variant w-full z-50 sticky top-0">
    <div class="flex justify-between items-center h-20 px-4 md:px-10 w-full max-w-[1280px] mx-auto">
        
        <a href="<?= base_url() ?>" class="flex items-center gap-2">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="HuniKita Logo" class="w-10 h-10 rounded-full object-cover border border-outline-variant shadow-sm bg-white" onerror="this.outerHTML='<div class=\'w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold\'>H</div>'">
            <span class="font-brand-text text-[24px] font-bold tracking-tight text-primary">HuniKita</span>
        </a>
        
        <nav class="hidden md:flex items-center gap-8">
            <a class="font-body-md text-[16px] text-primary hover:text-primary transition-colors duration-200" href="<?= base_url('search?listing_type=Sale') ?>">Buy</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('search?listing_type=Rent') ?>">Rent</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('page/about-us') ?>">About Us</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('news') ?>">News & Updates</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('page/privacy-policy') ?>">Privacy</a>
        </nav>
        
        <div class="hidden md:flex items-center gap-4">
            
            <?php if($roleId != 1): ?>
                <a href="<?= base_url('admin/properties/create') ?>" class="font-label-md text-[14px] font-semibold text-primary bg-transparent border border-primary px-4 py-2 rounded hover:bg-surface-container-high transition-colors">
                    Post Listing
                </a>
            <?php endif; ?>

            <?php if($isLoggedIn): ?>
                
                <a href="<?= base_url('user/saved-properties') ?>" class="relative text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer" title="Saved Properties">
                    <span class="material-symbols-outlined">favorite</span>
                </a>

                <a href="<?= base_url($roleId == 1 ? 'user/inbox' : 'admin/inquiries') ?>" class="relative text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer" title="Inbox">
                    <span class="material-symbols-outlined">mail</span>
                    <?php if ($unreadCount > 0): ?>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
                    <?php endif; ?>
                </a>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
                        <?php endif; ?>
                    </button>
                    
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-80 bg-surface rounded-lg shadow-lg border border-outline-variant overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                            <span class="font-label-md text-label-md text-on-surface font-bold">Activity</span>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <?php if (empty($notifications)): ?>
                                <div class="px-4 py-6 text-center text-on-surface-variant">
                                    <p class="font-caption text-caption">No recent activity.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <a href="<?= base_url($roleId == 1 ? 'user/inbox' : 'admin/inquiries') ?>" class="block px-4 py-3 hover:bg-surface-container transition-colors border-b border-outline-variant/50 relative">
                                        <p class="font-label-md text-[13px] text-on-surface font-bold truncate">
                                            <?= esc($notif->property_title) ?>
                                        </p>
                                        <p class="font-caption text-caption text-on-surface-variant">
                                            <?= $roleId == 1 ? 'Status updated to: ' . esc($notif->status) : 'New inquiry received.' ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative ml-2">
                    <button @click="open = !open" @click.outside="open = false" class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm cursor-pointer border-2 border-outline-variant hover:border-primary transition-colors">
                        <?= esc($initial) ?>
                    </button>
                    
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-56 bg-surface rounded-lg shadow-lg border border-outline-variant overflow-hidden z-50 py-1">
                        <div class="px-4 py-3 border-b border-outline-variant mb-1">
                            <p class="font-label-md text-label-md text-on-surface truncate font-bold"><?= esc($fullName) ?></p>
                            <p class="font-caption text-caption text-on-surface-variant truncate"><?= esc($email) ?></p>
                        </div>
                        
                        <a href="<?= base_url($roleId == 1 ? 'user/profile' : 'admin/profile') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                            <span class="font-body-sm text-body-sm">My Profile</span>
                        </a>

                        <?php if($roleId != 1): ?>
                            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                                <span class="font-body-sm text-body-sm">Admin Dashboard</span>
                            </a>
                        <?php endif; ?>

                        <div class="border-t border-outline-variant mt-1 pt-1">
                            <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 px-4 py-2 text-error hover:bg-error-container transition-colors">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span class="font-label-md text-label-md">Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="font-label-md text-[14px] font-semibold text-on-primary bg-primary px-4 py-2 rounded hover:bg-primary-container transition-colors">
                    Sign In
                </a>
            <?php endif; ?>
        </div>
        
        <button class="md:hidden flex items-center justify-center w-10 h-10 text-primary">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</header>

<?= $this->renderSection('content') ?>

</body>
</html>