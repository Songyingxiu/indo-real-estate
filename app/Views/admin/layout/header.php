<header class="hidden md:flex w-full h-16 justify-between items-center px-margin-desktop sticky top-0 z-50 bg-surface border-b border-outline-variant transition-colors duration-300">
    
    <div class="flex items-center gap-stack-md">
        <div class="flex items-center gap-stack-sm cursor-pointer active:opacity-80 transition-opacity">
            <span class="font-brand-text text-brand-text text-primary">EstateAdmin Pro</span>
        </div>
    </div>
    
    <div class="flex items-center gap-stack-md relative">
        
        <button id="theme-toggle-desktop" class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer">
            <span class="material-symbols-outlined" id="theme-icon-desktop">dark_mode</span>
        </button>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false" class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
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
                    <a href="#" class="block px-4 py-3 hover:bg-surface-container transition-colors border-b border-outline-variant/50 relative">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary"></div>
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-label-md text-label-md text-on-surface">New User Registration</span>
                            <span class="font-caption text-caption text-primary font-medium">Just now</span>
                        </div>
                        <p class="font-caption text-caption text-on-surface-variant line-clamp-2">Budi Santoso just registered as a Property Owner.</p>
                    </a>
                    
                    <a href="#" class="block px-4 py-3 hover:bg-surface-container transition-colors border-b border-outline-variant/50">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-label-md text-label-md text-on-surface">Property Flagged</span>
                            <span class="font-caption text-caption text-outline">2 hrs ago</span>
                        </div>
                        <p class="font-caption text-caption text-on-surface-variant line-clamp-2">Listing #MOD-8924A has been flagged by the automated system for review.</p>
                    </a>
                </div>
                
                <div class="px-4 py-2 border-t border-outline-variant bg-surface-container-lowest text-center">
                    <a href="#" class="text-primary font-label-md text-label-md hover:underline inline-block w-full py-1">View All Activity</a>
                </div>
            </div>
        </div>

        <button class="text-on-surface-variant hover:bg-surface-container-low transition-colors p-2 rounded-full cursor-pointer">
            <span class="material-symbols-outlined">settings</span>
        </button>

        <div x-data="{ open: false }" class="relative ml-2">
            <button @click="open = !open" @click.outside="open = false" class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm cursor-pointer border-2 border-outline-variant hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                A
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
                    <p class="font-label-md text-label-md text-on-surface truncate">Administrator</p>
                    <p class="font-caption text-caption text-on-surface-variant truncate">admin@estatepro.id</p>
                </div>
                
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">person</span>
                    <span class="font-body-sm text-body-sm">My Profile</span>
                </a>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                    <span class="font-body-sm text-body-sm">Account Settings</span>
                </a>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">security</span>
                    <span class="font-label-md text-label-md">Privacy & Security</span>
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

<script>
    const themeToggles = [document.getElementById('theme-toggle-desktop'), document.getElementById('theme-toggle-mobile')];
    const themeIcons = [document.getElementById('theme-icon-desktop'), document.getElementById('theme-icon-mobile')];
    const htmlEl = document.documentElement;

    function updateIcons(isDark) {
        themeIcons.forEach(icon => {
            if(icon) icon.textContent = isDark ? 'light_mode' : 'dark_mode';
        });
    }

    // Set initial icon state based on class added in head
    updateIcons(htmlEl.classList.contains('dark'));

    themeToggles.forEach(btn => {
        if(!btn) return;
        btn.addEventListener('click', () => {
            const isDark = htmlEl.classList.toggle('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
            updateIcons(isDark);
        });
    });
</script>