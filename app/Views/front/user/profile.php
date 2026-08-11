<?= $this->include('front/layout/header') ?>

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

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 border flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
            <h2 class="font-label-md text-[18px] font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span> <?= lang('Front.prof_personal_info') ?>
            </h2>
            
            <form action="<?= base_url('user/update-profile') ?>" method="POST" class="flex flex-col gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_first_name') ?></label>
                        <input type="text" name="first_name" value="<?= esc($user['first_name']) ?>" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_last_name') ?></label>
                        <input type="text" name="last_name" value="<?= esc($user['last_name']) ?>" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_email') ?></label>
                    <input type="email" value="<?= esc($user['email']) ?>" disabled class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] bg-surface-container-high text-on-surface-variant cursor-not-allowed outline-none" title="Email cannot be changed">
                    <p class="text-xs text-outline mt-1"><?= lang('Front.prof_email_notice') ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_phone') ?></label>
                    <input type="tel" name="phone_number" value="<?= esc($user['phone_number']) ?>" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
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
            
            <form action="<?= base_url('user/update-password') ?>" method="POST" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_current_pass') ?></label>
                    <input type="password" name="current_password" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                </div>

                <div class="grid grid-cols-1 gap-4 pt-2 border-t border-outline-variant/50">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_new_pass') ?></label>
                        <input type="password" name="new_password" required minlength="8" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1"><?= lang('Front.prof_confirm_pass') ?></label>
                        <input type="password" name="confirm_password" required minlength="8" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                </div>

                <div class="pt-4 mt-2 border-t border-outline-variant">
                    <button type="submit" class="bg-surface-container-highest text-on-surface px-6 py-2.5 border border-outline-variant rounded font-bold text-[14px] hover:bg-surface-container transition-colors">
                        <?= lang('Front.prof_update_pass') ?>
                    </button>
                </div>
            </form>
        </div>

        <?php if(in_array(session()->get('role_id'), [2, 3])): ?>
        <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm md:col-span-2">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-outline-variant">
                <h2 class="font-label-md text-[18px] font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">verified_user</span> <?= lang('Front.prof_verify_id') ?>
                </h2>
                <span class="bg-[#fef7e0] text-[#b06000] px-3 py-1 rounded-full text-xs font-semibold"><?= lang('Front.prof_not_verified') ?></span>
            </div>
            
            <p class="text-sm text-on-surface-variant mb-6"><?= lang('Front.prof_verify_desc') ?></p>

            <form action="<?= base_url('user/upload-agent-docs') ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2"><?= lang('Front.prof_ktp') ?> <span class="text-error">*</span></label>
                    <input type="file" name="ktp_document" accept="image/*,.pdf" required class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-fixed-dim file:text-primary hover:file:bg-primary-fixed cursor-pointer">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2"><?= lang('Front.prof_npwp') ?></label>
                    <input type="file" name="npwp" accept="image/*,.pdf" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-surface-container-high file:text-on-surface-variant hover:file:bg-surface-container cursor-pointer">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-2"><?= lang('Front.prof_siup') ?></label>
                    <input type="file" name="business_license" accept="image/*,.pdf" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-surface-container-high file:text-on-surface-variant hover:file:bg-surface-container cursor-pointer">
                </div>

                <div class="md:col-span-3 pt-4 border-t border-outline-variant flex justify-end">
                    <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded font-bold text-[14px] hover:bg-primary-container transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">upload_file</span> <?= lang('Front.prof_submit_verify') ?>
                    </button>
                </div>
            </form>
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
</main>

<!-- Tailwind Modal for Account Deletion -->
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