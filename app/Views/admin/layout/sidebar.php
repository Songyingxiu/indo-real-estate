<?php
$role_sidebar = session()->get('role_id');
$user_sidebar = session()->get('id') ?? session()->get('user_id');
$db = \Config\Database::connect();

$badge_inquiries = $GLOBALS['unread_count'] ?? 0;
$badge_moderation = 0;
$badge_verifs = 0;
$badge_dashboard = 0;

$allow_messages = false;

if ($role_sidebar == 4) {
    $allow_messages = true;
    try { $badge_moderation = $db->table('properties')->where('approval_status', 'Pending Review')->countAllResults(); } catch(\Exception $e) {}
    
    try {
        $p_agents = $db->table('agent_verifications')->groupStart()->whereIn('approval_status', ['Pending', 'Under Review'])->orWhere('approval_status IS NULL')->orWhere('approval_status', '')->groupEnd()->countAllResults();
        $p_props = $db->table('property_verifications')->groupStart()->whereIn('approval_status', ['Pending', 'Pending Verification', 'Under Review'])->orWhere('approval_status IS NULL')->orWhere('approval_status', '')->groupEnd()->countAllResults();
        $badge_verifs = $p_agents + $p_props;
    } catch(\Exception $e) {}

    $badge_dashboard = $badge_moderation + $badge_verifs + $badge_inquiries;
} else {
    // Check if the user's current plan allows messaging
    try {
        $plan_id = session()->get('plan_id') ?? 1;
        $plan = $db->table('subscription_plans')->where('id', $plan_id)->get()->getRow();
        $allow_messages = $plan ? $plan->allow_messages : false;
    } catch (\Exception $e) {}

    try { $badge_moderation = $db->table('properties')->where('owner_id', $user_sidebar)->whereIn('approval_status', ['Rejected', 'Changes Requested'])->countAllResults(); } catch(\Exception $e) {}
    $badge_dashboard = $badge_inquiries + $badge_moderation;
}
?>

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
            <?php if ($badge_dashboard > 0): ?>
                <span class="ml-auto bg-[#c9302c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $badge_dashboard ?></span>
            <?php endif; ?>
        </a>

        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (strpos(uri_string(), 'admin/properties') === 0) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/properties') ?>">
            <span class="material-symbols-outlined <?= (strpos(uri_string(), 'admin/properties') === 0) ? 'icon-fill' : '' ?>">real_estate_agent</span>
            <span class="font-label-md text-label-md">My Listings</span>
            <?php if ($role != 4 && $badge_moderation > 0): ?>
                <span class="ml-auto bg-[#c9302c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full" title="Action Required on Listings"><?= $badge_moderation ?></span>
            <?php endif; ?>
        </a>

        <!-- Dynamically locked Inquiries Inbox -->
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/inquiries')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" 
           href="<?= $allow_messages ? base_url('admin/inquiries') : base_url('admin/pricing') ?>" 
           <?= !$allow_messages ? 'title="Upgrade to a Premium plan to access direct messaging"' : '' ?>>
            <span class="material-symbols-outlined <?= (current_url() == base_url('admin/inquiries')) ? 'icon-fill' : '' ?>">forum</span>
            <span class="font-label-md text-label-md">Inquiries Inbox</span>
            
            <?php if (!$allow_messages): ?>
                <span class="ml-auto material-symbols-outlined text-[16px] opacity-70">lock</span>
            <?php elseif ($badge_inquiries > 0): ?>
                <span class="ml-auto bg-[#c9302c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $badge_inquiries ?></span>
            <?php endif; ?>
        </a>

        <!-- Sent Inquiries (Available to everyone for properties they want to buy) -->
        <?php if($role != 4): ?>
        <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('user/inbox')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('user/inbox') ?>">
            <span class="material-symbols-outlined <?= (current_url() == base_url('user/inbox')) ? 'icon-fill' : '' ?>">send</span>
            <span class="font-label-md text-label-md">Sent Inquiries</span>
        </a>
        <?php endif; ?>

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
                <?php if ($badge_moderation > 0): ?>
                    <span class="ml-auto bg-[#c9302c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $badge_moderation ?></span>
                <?php endif; ?>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/users')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/users') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/users')) ? 'icon-fill' : '' ?>">group</span>
                <span class="font-label-md text-label-md">User Management</span>
            </a>

            <a class="flex items-center gap-stack-sm py-2 px-4 mx-2 <?= (current_url() == base_url('admin/verifications')) ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-primary' ?> rounded-lg transition-all scale-98 duration-150" href="<?= base_url('admin/verifications') ?>">
                <span class="material-symbols-outlined <?= (current_url() == base_url('admin/verifications')) ? 'icon-fill' : '' ?>">fact_check</span>
                <span class="font-label-md text-label-md">Verification Center</span>
                <?php if ($badge_verifs > 0): ?>
                    <span class="ml-auto bg-[#c9302c] text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $badge_verifs ?></span>
                <?php endif; ?>
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

        <?php endif; ?>
    </div>

    <div class="px-margin-desktop mt-auto flex flex-col gap-unit pt-stack-md border-t border-outline-variant mx-4">

        <?php if($role == 4): ?>
            <a href="<?= base_url('admin/reports/export') ?>" class="w-full bg-primary-container text-on-primary-container rounded font-label-md text-label-md py-2 mb-stack-sm hover:opacity-90 transition-opacity text-center block shadow-sm">
                Generate Report
            </a>
        <?php endif; ?>

        <!-- Back to Homepage Escape Hatch -->
        <a class="flex items-center gap-stack-sm py-2 px-2 text-on-surface-variant hover:bg-surface-container-high hover:text-primary rounded-lg transition-all" href="<?= base_url() ?>">
            <span class="material-symbols-outlined">home</span>
            <span class="font-label-md text-label-md">Back to Homepage</span>
        </a>

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