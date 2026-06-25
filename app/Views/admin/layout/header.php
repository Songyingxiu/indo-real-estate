<?php
    $firstName = session()->get('first_name') ?? 'User';
    $lastName = session()->get('last_name') ?? '';
    $email = session()->get('email') ?? 'user@example.com';
    $fullName = trim($firstName . ' ' . $lastName);
    $initial = strtoupper(substr($firstName, 0, 1));
    
    // Fetch the global notifications in BaseController
    $notifications = $GLOBALS['global_notifications'] ?? [];
    $unreadCount = count($notifications);
?>
<header class="hidden md:flex w-full h-16 justify-between items-center px-margin-desktop sticky top-0 z-50 bg-surface border-b border-outline-variant transition-colors duration-300">
    
    <div class="flex items-center gap-stack-md">
        <div class="flex items-center gap-stack-sm cursor-pointer active:opacity-80 transition-opacity">
            <span class="font-brand-text text-brand-text text-primary">EstateAdmin Pro</span>
        </div>
    </div>
    
    <div class="flex items-center gap-stack-md relative">
        
        <button 
            x-data="{ isDark: document.documentElement.classList.contains('dark') }" 
            @click="
                isDark = !isDark;
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            " 
            class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer relative"
            title="Toggle Theme"
        >
            <span class="material-symbols-outlined" x-text="isDark ? 'light_mode' : 'dark_mode'"></span>
        </button>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false" class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer relative">
                <span class="material-symbols-outlined">notifications</span>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
                <?php endif; ?>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-surface rounded-lg shadow-lg border border-outline-variant overflow-hidden z-50"
                 style="display: none;">
                
                <div class="px-4 py-3 border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <span class="font-label-md text-label-md text-on-surface font-bold">Notifications</span>
                    <a href="#" class="text-primary font-caption text-caption hover:underline">Mark all as read</a>
                </div>
                
                <div class="max-h-96 overflow-y-auto">
                    <?php if (empty($notifications)): ?>
                        <div class="px-4 py-6 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[32px] mb-2 opacity-50">notifications_off</span>
                            <p class="font-caption text-caption">No new notifications.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <a href="<?= base_url('admin/leads') ?>" class="block px-4 py-3 hover:bg-surface-container transition-colors border-b border-outline-variant/50 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-label-md text-label-md text-on-surface">New Lead Received</span>
                                    <span class="font-caption text-caption text-primary font-medium">
                                        <?= date('M d', strtotime($notif->created_date)) ?>
                                    </span>
                                </div>
                                <p class="font-caption text-caption text-on-surface-variant line-clamp-2">
                                    <?= esc($notif->first_name . ' ' . $notif->last_name) ?> inquired about <?= esc($notif->property_title) ?>.
                                </p>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="px-4 py-2 border-t border-outline-variant bg-surface-container-lowest text-center">
                    <a href="<?= base_url('admin/leads') ?>" class="text-primary font-label-md text-label-md hover:underline inline-block w-full py-1">View All Activity</a>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" class="relative ml-2">
            <button @click="open = !open" @click.outside="open = false" class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm cursor-pointer border-2 border-outline-variant hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <?= esc($initial) ?>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-surface rounded-lg shadow-lg border border-outline-variant overflow-hidden z-50 py-1"
                 style="display: none;">
                
                <div class="px-4 py-3 border-b border-outline-variant mb-1">
                    <p class="font-label-md text-label-md text-on-surface truncate font-bold"><?= esc($fullName) ?></p>
                    <p class="font-caption text-caption text-on-surface-variant truncate"><?= esc($email) ?></p>
                </div>
                
                <a href="<?= base_url('admin/profile') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">person</span>
                    <span class="font-body-sm text-body-sm">My Profile</span>
                </a>
                <a href="<?= base_url('admin/profile#settings') ?>" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                    <span class="font-body-sm text-body-sm">Account Settings</span>
                </a>
                
                <div class="border-t border-outline-variant mt-1 pt-1">
                    <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 px-4 py-2 text-error hover:bg-error-container transition-colors">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span class="font-label-md text-label-md">Sign Out</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>