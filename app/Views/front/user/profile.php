<?= $this->include('front/layout/header') ?>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[70vh]">
    <div class="mb-8">
        <h1 class="font-headline-lg text-[32px] font-bold text-primary">My Profile</h1>
        <p class="text-on-surface-variant font-body-md">Manage your account details and security settings.</p>
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
        
        <!-- Personal Information Form -->
        <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
            <h2 class="font-label-md text-[18px] font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span> Personal Information
            </h2>
            
            <form action="<?= base_url('user/update-profile') ?>" method="POST" class="flex flex-col gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">First Name</label>
                        <input type="text" name="first_name" value="<?= esc($user['first_name']) ?>" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Last Name</label>
                        <input type="text" name="last_name" value="<?= esc($user['last_name']) ?>" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Email Address</label>
                    <input type="email" value="<?= esc($user['email']) ?>" disabled class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] bg-surface-container-high text-on-surface-variant cursor-not-allowed outline-none" title="Email cannot be changed">
                    <p class="text-xs text-outline mt-1">Contact support if you need to change your registered email.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Phone Number</label>
                    <input type="tel" name="phone_number" value="<?= esc($user['phone_number']) ?>" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                </div>

                <div class="pt-4 mt-2 border-t border-outline-variant">
                    <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded font-bold text-[14px] hover:bg-primary-container transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Security / Password Form -->
        <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm h-fit">
            <h2 class="font-label-md text-[18px] font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">lock</span> Change Password
            </h2>
            
            <form action="<?= base_url('user/update-password') ?>" method="POST" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                </div>

                <div class="grid grid-cols-1 gap-4 pt-2 border-t border-outline-variant/50">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">New Password</label>
                        <input type="password" name="new_password" required minlength="8" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" required minlength="8" class="w-full border border-outline-variant rounded px-3 py-2 text-[16px] focus:border-primary focus:ring-1 bg-white outline-none">
                    </div>
                </div>

                <div class="pt-4 mt-2 border-t border-outline-variant">
                    <button type="submit" class="bg-surface-container-highest text-on-surface px-6 py-2.5 border border-outline-variant rounded font-bold text-[14px] hover:bg-surface-container transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<?= $this->include('front/layout/footer') ?>