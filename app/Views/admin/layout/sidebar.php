<nav class="md:hidden flex justify-between items-center w-full px-margin-mobile h-16 sticky top-0 z-50 bg-surface border-b border-outline-variant transition-colors duration-300">
    <div class="flex items-center gap-stack-sm">
        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary font-brand-text text-xl">H</div>
        <span class="font-brand-text text-brand-text text-primary">HuniKita Admin</span>
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
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="HuniKita Logo" class="w-16 h-16 rounded-full object-cover shadow-sm bg-white mb-stack-sm" onerror="this.outerHTML='<div class=\'w-16 h-16 rounded-full bg-primary flex items-center justify-center text-on-primary font-brand-text text-3xl mb-stack-sm shadow-sm\'>H</div>'">
        <h1 class="font-brand-text text-brand-text text-primary text-center">HuniKita</h1>
        
        <p class="font-caption text-caption text-on-surface-variant mt-unit text-center font-bold">
            <?php 
                $role = session()->get('role_id');
                if($role == 4) echo "Administrator";
                elseif($role == 3) echo "Agent Portal";
                elseif($role == 2) echo "Owner Portal";
                else echo "User Portal";
            ?>
        </p>
    </div>
    
    <div class="flex-1 overflow-y-auto flex flex-col gap-unit pb-4 custom-scrollbar">
        
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/dashboard')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/dashboard') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/dashboard')) ? 'icon-fill' : '' ?>">dashboard</span>
            <span class="font-label-md text-label-md">Dashboard Overview</span>
        </a>

        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (strpos(uri_string(), 'admin/properties') === 0) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/properties') ?>">
            <span class="material-symbols-outlined <?= (strpos(uri_string(), 'admin/properties') === 0) ? 'icon-fill' : '' ?>">real_estate_agent</span>
            <span class="font-label-md text-label-md">My Listings</span>
        </a>
        
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/inquiries')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/inquiries') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/inquiries')) ? 'icon-fill' : '' ?>">forum</span>
            <span class="font-label-md text-label-md">Inquiries Inbox</span>
            <?php if (($GLOBALS['unread_count'] ?? 0) > 0): ?>
                <span class="ml-auto bg-error text-on-error text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $GLOBALS['unread_count'] ?></span>
            <?php endif; ?>
        </a>

        <?php if($role != 4): ?>
            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/pricing')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/pricing') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/pricing')) ? 'icon-fill' : '' ?>">workspace_premium</span>
                <span class="font-label-md text-label-md">Upgrade Plan</span>
            </a>
        <?php endif; ?>

        <?php if($role == 4): ?>
            <div class="px-6 py-3 mt-4">
                <p class="text-[11px] font-bold text-outline uppercase tracking-wider">Administration</p>
            </div>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (strpos(uri_string(), 'admin/email-templates') === 0) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/email-templates') ?>">
                <span class="material-symbols-outlined <?= (strpos(uri_string(), 'admin/email-templates') === 0) ? 'icon-fill' : '' ?>">mail</span>
                <span class="font-label-md text-label-md">Email Templates</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/moderation')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/moderation') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/moderation')) ? 'icon-fill' : '' ?>">rule</span>
                <span class="font-label-md text-label-md">Moderation Queue</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/users')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/users') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/users')) ? 'icon-fill' : '' ?>">group</span>
                <span class="font-label-md text-label-md">User Management</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/verifications')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/verifications') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/verifications')) ? 'icon-fill' : '' ?>">fact_check</span>
                <span class="font-label-md text-label-md">Verification Center</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/subscriptions')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/subscriptions') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/subscriptions')) ? 'icon-fill' : '' ?>">subscriptions</span>
                <span class="font-label-md text-label-md">Subscriptions</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/master-data')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/master-data') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/master-data')) ? 'icon-fill' : '' ?>">database</span>
                <span class="font-label-md text-label-md">Master Data & Features</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (strpos(uri_string(), 'admin/advertisements') === 0) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/advertisements') ?>">
                <span class="material-symbols-outlined <?= (strpos(uri_string(), 'admin/advertisements') === 0) ? 'icon-fill' : '' ?>">campaign</span>
                <span class="font-label-md text-label-md">Ad Management</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/cms')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/cms') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/cms')) ? 'icon-fill' : '' ?>">article</span>
                <span class="font-label-md text-label-md">CMS Management</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/seo')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/seo') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/seo')) ? 'icon-fill' : '' ?>">search_insights</span>
                <span class="font-label-md text-label-md">SEO Management</span>
            </a>
            
        <?php endif; ?>
    </div>
    
    <div class="px-margin-desktop mt-auto flex flex-col gap-unit pt-stack-md border-t border-outline-variant mx-4">
        
        <?php if($role == 4): ?>
            <a href="<?= base_url('admin/reports/export') ?>" class="w-full bg-primary-container text-on-primary-container rounded font-label-md text-label-md py-2 mb-stack-sm hover:opacity-90 transition-opacity text-center block shadow-sm">
                Generate Report
            </a>
        <?php endif; ?>

        <a class="flex items-center gap-stack-sm py-2 px-2 <?= (current_url() == base_url('admin/support')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all" href="<?= base_url('admin/support') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/support')) ? 'icon-fill' : '' ?>">help</span>
            <span class="font-label-md text-label-md">Support</span>
        </a>
        
        <a class="flex items-center gap-stack-sm py-2 px-2 text-error hover:bg-error-container hover:text-on-error-container rounded-lg transition-all" href="<?= base_url('logout') ?>">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-label-md text-label-md">Sign Out</span>
        </a>
    </div>
</nav>