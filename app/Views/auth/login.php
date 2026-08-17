<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Authentication - HuniKita</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-container": "#1a365d",
                        "on-surface": "#181c1e",
                        "surface-dim": "#d7dadc",
                        "surface-container": "#ebeef0",
                        "outline": "#74777f",
                        "surface": "#f7fafc",
                        "surface-container-lowest": "#ffffff",
                        "on-background": "#181c1e",
                        "on-surface-variant": "#43474e",
                        "primary": "#002045",
                        "background": "#f7fafc",
                        "on-primary": "#ffffff",
                        "primary-fixed-dim": "#adc7f7",
                        "surface-container-low": "#f1f4f6",
                        "outline-variant": "#c4c6cf"
                    },
                    fontFamily: {
                        "label-md": ["inter", "sans-serif"],
                        "caption": ["inter", "sans-serif"],
                        "body-lg": ["inter", "sans-serif"],
                        "body-md": ["inter", "sans-serif"],
                        "headline-lg-mobile": ["plusJakartaSans", "sans-serif"],
                        "headline-xl": ["plusJakartaSans", "sans-serif"],
                        "headline-lg": ["plusJakartaSans", "sans-serif"],
                        "brand-text": ["plusJakartaSans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .tab-content { display: none; }
        #tab-login:checked ~ .form-container #content-login { display: block; }
        #tab-register:checked ~ .form-container #content-register { display: block; }
        #tab-login:checked ~ .tab-headers label[for="tab-login"],
        #tab-register:checked ~ .tab-headers label[for="tab-register"] {
            @apply text-primary-container border-b-2 border-primary-container;
        }
        .role-radio:checked + div { @apply border-primary-container bg-surface-container-low ring-1 ring-primary-container shadow-sm; }
        .role-radio:checked + div .material-symbols-outlined { @apply text-primary-container; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col font-body-md text-body-md antialiased overflow-x-hidden">
    
    <header class="absolute top-0 left-0 w-full p-6 md:p-10 z-20 flex items-center gap-3 pointer-events-none">
        <a href="<?= base_url() ?>" class="pointer-events-auto flex items-center gap-3">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="HuniKita Logo" class="w-10 h-10 rounded-full object-cover border border-outline-variant shadow-sm bg-white" onerror="this.outerHTML='<div class=\'w-10 h-10 bg-primary rounded-full flex items-center justify-center\'><span class=\'material-symbols-outlined text-on-primary text-[20px]\'>real_estate_agent</span></div>'">
            <span class="font-brand-text text-[24px] font-bold text-primary-container drop-shadow-sm">HuniKita</span>
        </a>
    </header>
    
    <main class="flex-grow flex flex-col md:flex-row min-h-screen">
        
        <div class="hidden md:block md:w-1/2 lg:w-3/5 relative overflow-hidden bg-primary">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCxkaxuisAGiRlVRunMKjftQGbWxA2W6tkh1Zk8-GmooA7xzGG1g8O7IugHPpFNuc3h_xHzGjLUcVVoSTXe1IwU3YW949BOgWlMYBdT0uRcfDBKp23_Db0m1WqGZStEBBbYsbcBwu6x0jQC0y3qXfLV4JSgRMMbEoBdvKThSy6sQg7CCE9WzgP5TzYUbARIYw5rWVDWKxu2txfv77MQoPvv4cnpxXYscJLAgcCyxWMf43Jhd-WrRpkmSZRoJC9SE0YApT_K2PuK2d9p');">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/30 to-transparent opacity-80"></div>
            </div>
            <div class="absolute bottom-12 left-12 right-12 text-on-primary z-10 pointer-events-none">
                <h1 class="font-headline-xl text-[48px] font-bold mb-4 text-white drop-shadow-md leading-tight">Unlock Exceptional Properties</h1>
                <p class="font-body-lg text-[18px] text-white/90 max-w-lg drop-shadow">Join HuniKita's exclusive network of discerning buyers, distinguished sellers, and verified industry professionals.</p>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-2/5 bg-surface-container-lowest h-screen overflow-y-auto z-10 relative shadow-[-4px_0_24px_rgba(26,54,93,0.03)]">
            
            <div class="min-h-full flex flex-col pt-16 pb-12 px-6 md:pt-15 md:pb-16 md:px-12 lg:px-16">
                
                <?php if (session()->getFlashdata('error') && !session()->has('errors')) : ?>
                    <div class="bg-[#ffdad6] text-[#410002] p-4 rounded mb-4 font-semibold text-sm"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-4 font-semibold text-sm"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php 
                    $isRegistering = old('first_name') ? true : false; 
                ?>

                <div class="max-w-md w-full mx-auto relative mt-8">
                    
                    <input type="radio" name="auth-tabs" id="tab-login" class="hidden" <?= !$isRegistering ? 'checked' : '' ?>>
                    <input type="radio" name="auth-tabs" id="tab-register" class="hidden" <?= $isRegistering ? 'checked' : '' ?>>
                    
                    <div class="tab-headers flex border-b border-outline-variant mb-8 w-full relative z-20">
                        <label for="tab-login" class="flex-1 text-center py-4 font-label-md text-[14px] font-semibold text-on-surface-variant border-b-2 border-transparent hover:text-primary-container transition-colors cursor-pointer block">Sign In</label>
                        <label for="tab-register" class="flex-1 text-center py-4 font-label-md text-[14px] font-semibold text-on-surface-variant border-b-2 border-transparent hover:text-primary-container transition-colors cursor-pointer block">Create Account</label>
                    </div>

                    <div class="form-container relative z-10">
                        
                        <!-- SIGN IN FORM -->
                        <div id="content-login" class="tab-content fade-in pb-8">
                            <h2 class="font-headline-lg text-[32px] font-bold text-on-surface mb-2">Welcome Back</h2>
                            <p class="font-body-md text-[16px] text-on-surface-variant mb-8">Enter your credentials to access your account.</p>
                            
                            <form action="<?= base_url('login') ?>" class="space-y-4" method="POST" novalidate>
                                
                                <?php if (session('errors.email') || session('errors.password')): ?>
                                    <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 rounded shadow-sm mb-4">
                                        <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Email Address <span class="text-[#c9302c]">*</span></label>
                                    <?php $err = session('errors.email'); ?>
                                    <input class="w-full px-4 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" name="email" value="<?= old('email') ?>" placeholder="name@example.com" required type="email">
                                    <?php if ($err): ?>
                                        <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                            <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block font-label-md text-[14px] font-semibold text-on-surface">Password <span class="text-[#c9302c]">*</span></label>
                                        <a href="<?= base_url('forgot-password') ?>" class="font-label-md text-[14px] font-semibold text-primary-container hover:underline transition-all cursor-pointer">Forgot Password?</a>
                                    </div>
                                    <div class="relative">
                                        <?php $err = session('errors.password'); ?>
                                        <input class="w-full pl-4 pr-10 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" name="password" placeholder="••••••••" required type="password">
                                        <button onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors" type="button">
                                            <span class="material-symbols-outlined">visibility_off</span>
                                        </button>
                                    </div>
                                    <?php if ($err): ?>
                                        <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                            <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-2 pt-2 pb-4">
                                    <input class="w-4 h-4 rounded border-outline-variant text-primary-container focus:ring-primary-container cursor-pointer" type="checkbox" name="remember">
                                    <label class="font-body-md text-[14px] text-on-surface-variant cursor-pointer">Keep me signed in</label>
                                </div>
                                <button class="w-full bg-primary-container text-on-primary font-label-md text-[15px] font-semibold py-3 rounded hover:bg-primary transition-colors flex items-center justify-center gap-2 relative z-30" type="submit">Sign In</button>
                            </form>

                            <div class="mt-8">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-outline-variant"></div></div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-surface-container-lowest font-caption text-[12px] text-on-surface-variant">Or continue with</span>
                                    </div>
                                </div>
                                <div class="mt-6 grid grid-cols-1 gap-4">
                                    <button onclick="event.preventDefault(); signInWithGoogle();" class="w-full bg-transparent border border-outline-variant text-on-surface-variant font-label-md text-[14px] font-semibold py-3 rounded hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2 relative z-30" type="button">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path></svg>
                                        Google
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- CREATE ACCOUNT FORM -->
                        <div id="content-register" class="tab-content fade-in pb-8">
                            <h2 class="font-headline-lg text-[32px] font-bold text-on-surface mb-2">Create Account</h2>
                            <p class="font-body-md text-[16px] text-on-surface-variant mb-6">Select your primary intent to personalize your experience.</p>
                            
                            <form action="<?= base_url('register') ?>" class="space-y-4" method="POST" novalidate>
                                
                                <?php if ($isRegistering && session()->has('errors')): ?>
                                    <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 rounded shadow-sm mb-4">
                                        <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                                    </div>
                                <?php endif; ?>

                                <?php $oldRole = old('role') ?: 'buyer'; ?>
                                <div class="grid grid-cols-3 gap-3 mb-2 relative z-30">
                                    <label class="cursor-pointer relative group">
                                        <input <?= $oldRole == 'buyer' ? 'checked' : '' ?> class="role-radio absolute opacity-0 w-0 h-0" name="role" type="radio" value="buyer" id="role-buyer">
                                        <div class="border border-outline-variant rounded p-3 flex flex-col items-center justify-center text-center hover:bg-surface-container-low transition-colors h-full">
                                            <span class="material-symbols-outlined text-outline mb-1 text-[24px]">person_search</span>
                                            <span class="font-label-md text-[13px] font-semibold text-on-surface">Buyer</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer relative group">
                                        <input <?= $oldRole == 'owner' ? 'checked' : '' ?> class="role-radio absolute opacity-0 w-0 h-0" name="role" type="radio" value="owner" id="role-owner">
                                        <div class="border border-outline-variant rounded p-3 flex flex-col items-center justify-center text-center hover:bg-surface-container-low transition-colors h-full">
                                            <span class="material-symbols-outlined text-outline mb-1 text-[24px]">real_estate_agent</span>
                                            <span class="font-label-md text-[13px] font-semibold text-on-surface">Owner</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer relative group">
                                        <input <?= $oldRole == 'agent' ? 'checked' : '' ?> class="role-radio absolute opacity-0 w-0 h-0" name="role" type="radio" value="agent" id="role-agent">
                                        <div class="border border-outline-variant rounded p-3 flex flex-col items-center justify-center text-center hover:bg-surface-container-low transition-colors h-full">
                                            <span class="material-symbols-outlined text-outline mb-1 text-[24px]">verified_user</span>
                                            <span class="font-label-md text-[13px] font-semibold text-on-surface">Agent</span>
                                        </div>
                                    </label>
                                </div>
                                <?php if ($err = session('errors.role')): ?>
                                    <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                        <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">First Name <span class="text-[#c9302c]">*</span></label>
                                        <?php $err = session('errors.first_name'); ?>
                                        <input name="first_name" value="<?= old('first_name') ?>" class="w-full px-4 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" placeholder="John" required type="text">
                                        <?php if ($err): ?>
                                            <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Last Name <span class="text-[#c9302c]">*</span></label>
                                        <?php $err = session('errors.last_name'); ?>
                                        <input name="last_name" value="<?= old('last_name') ?>" class="w-full px-4 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" placeholder="Doe" required type="text">
                                        <?php if ($err): ?>
                                            <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Email Address <span class="text-[#c9302c]">*</span></label>
                                    <?php $err = session('errors.email'); ?>
                                    <input name="email" value="<?= old('email') ?>" class="w-full px-4 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" placeholder="john@example.com" required type="email">
                                    <?php if ($err): ?>
                                        <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                            <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Phone Number</label>
                                    <?php $err = session('errors.phone_number'); ?>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-body-md text-outline font-semibold">+62</span>
                                        <input name="phone_number" value="<?= old('phone_number') ?>" class="w-full pl-14 pr-4 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" placeholder="812-3456-7890" required type="tel">
                                    </div>
                                    <?php if ($err): ?>
                                        <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                            <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Password <span class="text-[#c9302c]">*</span></label>
                                        <div class="relative">
                                            <?php $err = session('errors.password'); ?>
                                            <input name="password" class="w-full pl-4 pr-10 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" placeholder="••••••••" required type="password">
                                            <button onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors" type="button">
                                                <span class="material-symbols-outlined">visibility_off</span>
                                            </button>
                                        </div>
                                        <?php if ($err): ?>
                                            <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Confirm <span class="text-[#c9302c]">*</span></label>
                                        <div class="relative">
                                            <?php $err = session('errors.password_confirm'); ?>
                                            <input name="password_confirm" class="w-full pl-4 pr-10 py-3 border rounded text-on-surface font-body-md text-[14px] outline-none focus:ring-1 transition-all <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'bg-surface-container-lowest border-outline-variant focus:border-primary-container focus:ring-primary-fixed-dim' ?>" placeholder="••••••••" required type="password">
                                            <button onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors" type="button">
                                                <span class="material-symbols-outlined">visibility_off</span>
                                            </button>
                                        </div>
                                        <?php if ($err): ?>
                                            <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-2 pt-2 pb-2 relative z-30">
                                    <input class="mt-0.5 w-4 h-4 rounded border-outline-variant text-primary-container focus:ring-primary-container cursor-pointer" type="checkbox" required>
                                    <label class="font-caption text-[13px] text-on-surface-variant cursor-pointer leading-tight">
                                        I agree to the <a class="text-primary-container font-semibold hover:underline" href="#">Terms of Service</a> and <a class="text-primary-container font-semibold hover:underline" href="#">Privacy Policy</a>.
                                    </label>
                                </div>
                                
                                <button class="w-full bg-primary-container text-on-primary font-label-md text-[15px] font-semibold py-3 rounded hover:bg-primary transition-colors flex items-center justify-center gap-2 mt-2 relative z-30" type="submit">Create Account</button>

                                <div class="relative flex items-center py-4">
                                    <div class="flex-grow border-t border-outline-variant"></div>
                                    <span class="flex-shrink mx-4 font-caption text-[12px] text-outline uppercase tracking-wider">OR</span>
                                    <div class="flex-grow border-t border-outline-variant"></div>
                                </div>
                                
                                <button onclick="event.preventDefault(); signInWithGoogle();" class="w-full bg-transparent border border-outline-variant text-on-surface-variant font-label-md text-[14px] font-semibold py-3 rounded hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2 relative z-30" type="button">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path></svg>
                                    Sign up with Google
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function togglePassword(button) {
            const input = button.previousElementSibling;
            const icon = button.querySelector('span');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>

    <!-- Firebase Integration -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";
        
        const firebaseConfig = {
            apiKey: "<?= getenv('FIREBASE_API_KEY') ?>",
            authDomain: "<?= getenv('FIREBASE_AUTH_DOMAIN') ?>",
            projectId: "<?= getenv('FIREBASE_PROJECT_ID') ?>",
            storageBucket: "<?= getenv('FIREBASE_STORAGE_BUCKET') ?>",
            messagingSenderId: "<?= getenv('FIREBASE_MESSAGING_SENDER_ID') ?>",
            appId: "<?= getenv('FIREBASE_APP_ID') ?>",
            measurementId: "<?= getenv('FIREBASE_MEASUREMENT_ID') ?>"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.signInWithGoogle = function() {
            const isRegistering = document.getElementById('tab-register').checked;
            let selectedRole = 'buyer';
            
            if (isRegistering) {
                const roleRadio = document.querySelector('input[name="role"]:checked');
                if (roleRadio) selectedRole = roleRadio.value;
            }

            signInWithPopup(auth, provider)
                .then((result) => {
                    const user = result.user;
                    
                    fetch('/auth/google-login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            uid: user.uid,
                            email: user.email,
                            displayName: user.displayName,
                            role: selectedRole
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || 'Login failed.');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        alert('A server error occurred during Google authentication.');
                    });
                }).catch((error) => {
                    console.error('Firebase Auth Error:', error);
                });
        }
    </script>
</body>
</html>