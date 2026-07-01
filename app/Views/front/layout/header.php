<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= esc($title ?? 'Lunera - Real Estate Platform') ?></title>
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Config -->
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
                        "primary": "#002045"
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
        .property-card {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .property-card:hover {
            box-shadow: 0px 4px 20px rgba(26, 54, 93, 0.08);
            transform: translateY(-2px);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f7fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c4c6cf; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #74777f; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

<!-- TopNavBar -->
<header class="bg-surface border-b border-outline-variant w-full z-50 sticky top-0">
    <div class="flex justify-between items-center h-20 px-4 md:px-10 w-full max-w-[1280px] mx-auto">
        <!-- Logo & Brand -->
        <a href="<?= base_url() ?>" class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-full bg-surface-variant flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-on-surface-variant">real_estate_agent</span>
            </div>
            <span class="font-brand-text text-[24px] font-bold tracking-tight text-primary">Lunera</span>
        </a>
        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a class="font-body-md text-[16px] text-primary border-b-2 border-primary pb-1 transition-colors duration-200" href="<?= base_url('search?listing_type=Sale') ?>">Buy</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="<?= base_url('search?listing_type=Rent') ?>">Rent</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">New Projects</a>
            <a class="font-body-md text-[16px] text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Agents</a>
        </nav>
        <!-- Trailing Actions -->
        <div class="hidden md:flex items-center gap-4">
            <a href="<?= base_url('admin/properties/create') ?>" class="font-label-md text-[14px] font-semibold text-primary bg-transparent border border-primary px-4 py-2 rounded hover:bg-surface-container-high transition-colors">
                Post Listing
            </a>
            <a href="<?= base_url('login') ?>" class="font-label-md text-[14px] font-semibold text-on-primary bg-primary px-4 py-2 rounded hover:bg-primary-container transition-colors">
                Sign In
            </a>
        </div>
        <!-- Mobile Menu Toggle -->
        <button class="md:hidden flex items-center justify-center w-10 h-10 text-primary">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</header>