<nav class="md:hidden flex justify-between items-center w-full px-margin-mobile h-16 sticky top-0 z-50 bg-surface border-b border-outline-variant transition-colors duration-300">
    <div class="flex items-center gap-stack-sm">
        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary font-brand-text text-xl">E</div>
        <span class="font-brand-text text-brand-text text-primary">EstateAdmin</span>
    </div>
    <div class="flex gap-stack-sm text-primary">
        <button id="theme-toggle-mobile" class="hover:bg-surface-container-low transition-colors rounded-full p-2">
            <span class="material-symbols-outlined" id="theme-icon-mobile">dark_mode</span>
        </button>
        <button class="hover:bg-surface-container-low transition-colors rounded-full p-2">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </div>
</nav>

<nav class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 py-stack-md bg-surface-container-low border-r border-outline-variant transition-colors duration-300 z-40">
    <div class="px-margin-desktop mb-stack-lg flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-on-primary font-brand-text text-3xl mb-stack-sm shadow-sm">E</div>
        <h1 class="font-brand-text text-brand-text text-primary text-center">App Name</h1>
        <p class="font-caption text-caption text-on-surface-variant mt-unit text-center">Admin Dashboard</p>
        <p class="font-caption text-caption text-on-surface-variant text-center">Marketplace Control</p>
    </div>
    
    <div class="flex-1 overflow-y-auto flex flex-col gap-unit pb-4 custom-scrollbar">
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/dashboard')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/dashboard') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/dashboard')) ? 'icon-fill' : '' ?>">dashboard</span>
            <span class="font-label-md text-label-md">Dashboard Overview</span>
        </a>
        
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/users')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/users') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/users')) ? 'icon-fill' : '' ?>">group</span>
            <span class="font-label-md text-label-md">User Management</span>
        </a>
        
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/moderation')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/moderation') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/moderation')) ? 'icon-fill' : '' ?>">verified_user</span>
            <span class="font-label-md text-label-md">Property Moderation</span>
        </a>
        
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/leads')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/leads') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/leads')) ? 'icon-fill' : '' ?>">leaderboard</span>
            <span class="font-label-md text-label-md">Lead Management</span>
        </a>

        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/verifications')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/verifications') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/verifications')) ? 'icon-fill' : '' ?>">fact_check</span>
            <span class="font-label-md text-label-md">Verification Center</span>
        </a>

        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/subscriptions')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/subscriptions') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/subscriptions')) ? 'icon-fill' : '' ?>">subscriptions</span>
            <span class="font-label-md text-label-md">Subscription Management</span>
        </a>
    </div>
    
    <div class="px-margin-desktop mt-auto flex flex-col gap-unit pt-stack-md border-t border-outline-variant mx-4">
        <button class="w-full bg-primary-container text-on-primary-container rounded font-label-md text-label-md py-2 mb-stack-sm hover:opacity-90 transition-opacity">Generate Report</button>
        <a class="flex items-center gap-stack-sm py-2 px-2 text-on-surface-variant hover:bg-surface-container-high hover:text-primary rounded-lg transition-all" href="#">
            <span class="material-symbols-outlined">help</span>
            <span class="font-label-md text-label-md">Support</span>
        </a>
        <a class="flex items-center gap-stack-sm py-2 px-2 text-error hover:bg-error-container hover:text-on-error-container rounded-lg transition-all" href="<?= base_url('logout') ?>">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-label-md text-label-md">Sign Out</span>
        </a>
    </div>
</nav>