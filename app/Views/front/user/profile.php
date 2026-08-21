<?= $this->include('front/layout/header') ?>

<?php
$getErr = function($field) { return session('errors.' . $field); };
$errClass = function($err) { return $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'border-outline-variant focus:border-primary bg-surface'; };
$errBox = function($err) { return $err ? '<div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]"><span class="material-symbols-outlined text-[16px] mt-0.5">warning</span>'.esc($err).'</div>' : ''; };
?>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[70vh]">
    <div class="mb-8">
        <h1 class="font-headline-lg text-[32px] font-bold text-primary"><?= lang('Front.prof_title') ?></h1>
        <p class="text-on-surface-variant font-body-md"><?= lang('Front.prof_subtitle') ?></p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded-xl mb-6 border border-[#a8c7fa] flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error') && !session()->has('errors')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 border flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php $hasLocalPassword = !empty($user['password']); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Sidebar: Profile Summary -->
        <div class="lg:col-span-1">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 shadow-sm flex flex-col items-center text-center">
                <?php 
                    $initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'] ?? '', 0, 1));
                    $roleName = 'Standard User';
                ?>
                
                <div class="w-28 h-28 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-4xl font-bold mb-6 shadow-inner border-4 border-surface">
                    <?= esc($initials) ?>
                </div>
                
                <h2 class="text-2xl font-bold text-on-surface"><?= esc($user['first_name'] . ' ' . ($user['last_name'] ?? '')) ?></h2>
                <p class="text-primary font-bold text-sm tracking-widest uppercase mt-1 mb-6 px-3 py-1 bg-primary-fixed-dim/20 rounded-full inline-block"><?= esc($roleName) ?></p>
                
                <!-- Added Quick Management Links -->
                <div class="w-full flex flex-col gap-3">
                    <a href="<?= base_url('admin/dashboard') ?>" class="w-full py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span> Dashboard
                    </a>
                    <a href="<?= base_url('admin/properties') ?>" class="w-full py-2 bg-surface-container-low text-on-surface rounded font-semibold hover:bg-surface-container transition shadow-sm border border-outline-variant flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">list_alt</span> My Properties
                    </a>
                    <a href="<?= base_url('admin/inquiries') ?>" class="w-full py-2 bg-surface-container-low text-on-surface rounded font-semibold hover:bg-surface-container transition shadow-sm border border-outline-variant flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">inbox</span> Received Leads
                    </a>
                    <a href="<?= base_url('user/inbox') ?>" class="w-full py-2 bg-surface-container-low text-on-surface rounded font-semibold hover:bg-surface-container transition shadow-sm border border-outline-variant flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">send</span> Sent Inquiries
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Content: Information & Upgrades -->
        <div class="lg:col-span-2 space-y-8" x-data="{ showUpgradeModal: false }">
            
            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                <h2 class="font-label-md text-[18px] font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span> <?= lang('Front.prof_personal_info') ?>
                </h2>
                
                <form action="<?= base_url('user/update-profile') ?>" method="POST" novalidate class="flex flex-col gap-4">
                    
                    <?php if (session('errors.first_name') || session('errors.last_name') || session('errors.phone_number')): ?>
                        <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 rounded shadow-sm text-sm">
                            <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_first_name') ?> <span class="text-[#c9302c]">*</span></label>
                            <?php $err = $getErr('first_name'); ?>
                            <input type="text" name="first_name" value="<?= esc($user['first_name']) ?>" required class="w-full px-3 py-2 text-[16px] border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                            <?= $errBox($err) ?>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_last_name') ?> <span class="text-[#c9302c]">*</span></label>
                            <?php $err = $getErr('last_name'); ?>
                            <input type="text" name="last_name" value="<?= esc($user['last_name']) ?>" required class="w-full px-3 py-2 text-[16px] border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                            <?= $errBox($err) ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_email') ?></label>
                        <input type="email" value="<?= esc($user['email']) ?>" disabled class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] bg-surface-container-high text-on-surface-variant cursor-not-allowed outline-none" title="Email cannot be changed">
                        <p class="text-xs text-outline mt-1"><?= lang('Front.prof_email_notice') ?></p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_phone') ?> <span class="text-[#c9302c]">*</span></label>
                        <?php $err = $getErr('phone_number'); ?>
                        <input type="tel" name="phone_number" value="<?= esc($user['phone_number']) ?>" required class="w-full px-3 py-2 text-[16px] border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                        <?= $errBox($err) ?>
                    </div>

                    <div class="pt-4 mt-2 border-t border-outline-variant">
                        <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded font-bold text-[14px] hover:bg-primary-container transition-colors">
                            <?= lang('Front.prof_save') ?>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm h-fit">
                <h2 class="font-label-md text-[18px] font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">lock</span> <?= lang('Front.prof_change_pass') ?>
                </h2>
                
                <form action="<?= base_url('user/update-password') ?>" method="POST" novalidate class="flex flex-col gap-4">
                    
                    <?php if (session('errors.current_password') || session('errors.new_password') || session('errors.confirm_password')): ?>
                        <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 rounded shadow-sm text-sm">
                            <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                        </div>
                    <?php endif; ?>

                    <?php if($hasLocalPassword): ?>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_current_pass') ?> <span class="text-[#c9302c]">*</span></label>
                            <?php $err = $getErr('current_password'); ?>
                            <input type="password" name="current_password" required class="w-full px-3 py-2 text-[16px] border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                            <?= $errBox($err) ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-surface-container-low text-on-surface-variant p-3 rounded text-sm mb-2 border border-outline-variant">
                            You registered with Google. Set a new password below to enable email login.
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 gap-4 pt-2 border-t border-outline-variant/50">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_new_pass') ?> <span class="text-[#c9302c]">*</span></label>
                            <?php $err = $getErr('new_password'); ?>
                            <input type="password" name="new_password" required minlength="8" class="w-full px-3 py-2 text-[16px] border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                            <?= $errBox($err) ?>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_confirm_pass') ?> <span class="text-[#c9302c]">*</span></label>
                            <?php $err = $getErr('confirm_password'); ?>
                            <input type="password" name="confirm_password" required minlength="8" class="w-full px-3 py-2 text-[16px] border rounded focus:ring-1 outline-none <?= $errClass($err) ?>">
                            <?= $errBox($err) ?>
                        </div>
                    </div>

                    <div class="pt-4 mt-2 border-t border-outline-variant">
                        <button type="submit" class="bg-surface-container-highest text-on-surface px-6 py-2.5 border border-outline-variant rounded font-bold text-[14px] hover:bg-surface-container transition-colors">
                            <?= lang('Front.prof_update_pass') ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Agent Upgrade / Verification Block -->
            <?php 
                $approvalStatus = '';
                if (isset($agentVerification) && $agentVerification) {
                    $approvalStatus = is_object($agentVerification) ? $agentVerification->approval_status : $agentVerification['approval_status'];
                }
            ?>
            
            <?php if (session()->get('role_id') == 1 || $approvalStatus !== ''): ?>
            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm md:col-span-2 mt-4">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-outline-variant">
                    <h2 class="font-label-md text-[18px] font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">verified_user</span> <?= session()->get('role_id') == 1 ? 'Upgrade to Verified Agent' : lang('Front.prof_verify_id') ?>
                    </h2>
                    <?php if ($approvalStatus == 'Verified'): ?>
                        <span class="bg-[#d3e3fd] text-[#041e49] px-3 py-1 rounded-full text-xs font-semibold">Verified Agent</span>
                    <?php elseif ($approvalStatus == 'Pending Verification'): ?>
                        <span class="bg-[#fef7e0] text-[#b06000] px-3 py-1 rounded-full text-xs font-semibold">Pending Approval</span>
                    <?php else: ?>
                        <span class="bg-surface-container-high text-on-surface-variant px-3 py-1 rounded-full text-xs font-semibold">Standard User</span>
                    <?php endif; ?>
                </div>
                
                <?php if ($approvalStatus == 'Verified'): ?>
                    <p class="text-sm text-on-surface-variant mb-6">Your identity has been fully verified. You now have access to advanced agent privileges and priority support.</p>
                <?php elseif ($approvalStatus == 'Pending Verification'): ?>
                    <p class="text-sm text-on-surface-variant mb-6">Your identity documents are currently under review by our moderation team. You will be notified once approved.</p>
                <?php else: ?>
                    <p class="text-sm text-on-surface-variant mb-6">Standard users (Buyers/Owners) can post properties for free. To unlock advanced agent limits, unlimited custom POIs, and dedicated support, please submit your formal identification documents below to upgrade your account status to Agent.</p>

                    <form action="<?= base_url('user/upload-agent-docs') ?>" method="POST" enctype="multipart/form-data" novalidate class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="md:col-span-3">
                            <?php if (session('errors.ktp_document')): ?>
                                <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 rounded shadow-sm text-sm">
                                    <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2">Upload KTP (National ID) <span class="text-[#c9302c]">*</span></label>
                            <?php $err = $getErr('ktp_document'); ?>
                            <input type="file" name="ktp_document" accept="image/*,.pdf" required class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold cursor-pointer border rounded p-1 <?= $err ? 'border-[#c9302c] bg-[#fff8f8] file:bg-error-container file:text-error' : 'border-outline-variant bg-surface file:bg-primary-fixed-dim file:text-primary hover:file:bg-primary-fixed' ?>">
                            <?= $errBox($err) ?>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2"><?= lang('Front.prof_npwp') ?> (Optional)</label>
                            <input type="file" name="npwp" accept="image/*,.pdf" class="block w-full text-sm text-on-surface-variant border border-outline-variant rounded p-1 bg-surface file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-surface-container-high file:text-on-surface-variant hover:file:bg-surface-container cursor-pointer">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-2">Business License / SIUP (Optional)</label>
                            <input type="file" name="business_license" accept="image/*,.pdf" class="block w-full text-sm text-on-surface-variant border border-outline-variant rounded p-1 bg-surface file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-surface-container-high file:text-on-surface-variant hover:file:bg-surface-container cursor-pointer">
                        </div>

                        <div class="md:col-span-3 pt-4 border-t border-outline-variant flex justify-end">
                            <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded font-bold text-[14px] hover:bg-primary-container transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">upgrade</span> Submit Agent Request
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Danger Zone for Account Deletion -->
            <div class="mt-8 pt-8 border-t border-outline-variant md:col-span-2">
                <h3 class="font-headline-lg text-[20px] font-bold text-[#ba1a1a] mb-2"><?= lang('Front.prof_danger_zone') ?></h3>
                <p class="font-body-md text-[14px] text-on-surface-variant mb-4">
                    <?= lang('Front.prof_danger_desc') ?> <strong><?= lang('Front.prof_danger_bold') ?></strong>
                </p>
                
                <button type="button" onclick="document.getElementById('deleteModal').classList.remove('hidden')" class="bg-[#ba1a1a] text-white font-label-md text-[14px] font-semibold py-2 px-4 rounded hover:bg-[#93000a] transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">person_remove</span>
                    <?= lang('Front.prof_del_btn') ?>
                </button>
            </div>

        </div>
    </div>
</main>

<div id="deleteModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity px-4">
    <div class="bg-white p-6 rounded-lg max-w-md w-full shadow-xl">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-[#ba1a1a] text-3xl">warning</span>
            <h3 class="text-xl font-bold text-gray-900"><?= lang('Front.prof_modal_title') ?></h3>
        </div>
        <p class="text-gray-600 mb-6 font-body-md">
            <?= lang('Front.prof_modal_desc') ?>
        </p>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-4 py-2 font-semibold text-gray-600 bg-gray-100 rounded hover:bg-gray-200 transition-colors">
                <?= lang('Front.prof_modal_cancel') ?>
            </button>
            <form action="<?= base_url('user/delete-account') ?>" method="POST">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 font-semibold text-white bg-[#ba1a1a] rounded hover:bg-[#93000a] transition-colors">
                    <?= lang('Front.prof_modal_confirm') ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->include('front/layout/footer') ?>