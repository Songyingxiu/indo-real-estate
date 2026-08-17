<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="pb-12" x-data="{ 
    showEditModal: <?= session('errors.first_name') || session('errors.last_name') || session('errors.email') || session('errors.phone_number') ? 'true' : 'false' ?>, 
    showPasswordModal: <?= session('errors.new_password') || session('errors.confirm_password') || session('errors.current_password') ? 'true' : 'false' ?>, 
    showDeleteModal: false 
}">

    <div class="mt-4 mb-6">
        <h1 class="text-2xl font-bold text-on-surface">My Profile & Settings</h1>
        <p class="text-on-surface-variant">Manage your personal information and account preferences.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded mb-6 border border-error flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1">
            <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm flex flex-col items-center text-center">
                <?php 
                    $initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'] ?? '', 0, 1));
                    $roleName = 'User';
                    if($user['role_id'] == 4) $roleName = 'Administrator';
                    if($user['role_id'] == 3) $roleName = 'Real Estate Agent';
                    if($user['role_id'] == 2) $roleName = 'Property Owner';
                    if($user['role_id'] == 1) $roleName = 'Buyer';
                ?>
                
                <div class="w-24 h-24 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-3xl font-bold mb-4 shadow-inner">
                    <?= esc($initials) ?>
                </div>
                
                <h2 class="text-xl font-bold text-on-surface"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                <p class="text-primary font-medium text-sm mb-4"><?= esc($roleName) ?></p>
                
                <div class="w-full border-t border-outline-variant pt-4 mt-2">
                    <button @click="showEditModal = true" class="w-full py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">edit</span> Edit Profile
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span> Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-on-surface-variant mb-1">Full Name</p>
                        <p class="text-on-surface"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-on-surface-variant mb-1">Email Address</p>
                        <p class="text-on-surface"><?= esc($user['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-on-surface-variant mb-1">Phone Number</p>
                        <p class="text-on-surface"><?= esc($user['phone_number'] ?? 'Not provided') ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-on-surface-variant mb-1">Member Since</p>
                        <p class="text-on-surface"><?= date('F j, Y', strtotime($user['created_date'])) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">card_membership</span> Subscription Status
                </h3>
                
                <?php if (isset($activeSubscription) && $activeSubscription && isset($activePlan)): ?>
                    <div class="bg-primary-container text-on-primary-container p-5 rounded-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <p class="text-sm font-semibold opacity-80 mb-1">Current Plan</p>
                            <h4 class="text-2xl font-bold"><?= esc($activePlan->name ?? $activePlan['name'] ?? 'Premium Plan') ?></h4>
                        </div>
                        <div class="bg-primary text-on-primary px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">verified</span> 
                            <?= esc($activeSubscription->sub_status ?? $activeSubscription['sub_status'] ?? '') ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">play_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface-variant">Start Date</p>
                                <p class="text-on-surface font-medium"><?= date('F j, Y', strtotime($activeSubscription->start_date ?? $activeSubscription['start_date'] ?? 'now')) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-error">
                                <span class="material-symbols-outlined">event_busy</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-on-surface-variant">Expiration Date</p>
                                <p class="text-on-surface font-medium"><?= date('F j, Y', strtotime($activeSubscription->end_date ?? $activeSubscription['end_date'] ?? 'now')) ?></p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">inventory_2</span>
                        <p class="text-on-surface font-medium text-lg mb-1">No Active Plan</p>
                        <p class="text-on-surface-variant text-sm mb-5">You do not have an active subscription package.</p>
                        <a href="<?= base_url('admin/pricing') ?>" class="px-5 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition shadow-sm">View Pricing Plans</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($user['role_id'] != 4 && $user['role_id'] != 1): ?>
                <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">verified_user</span> Identity Verification
                    </h3>

                    <?php 
                        $approvalStatus = '';
                        if (isset($agentVerification) && $agentVerification) {
                            $approvalStatus = is_object($agentVerification) ? $agentVerification->approval_status : $agentVerification['approval_status'];
                        }
                    ?>

                    <?php if ($approvalStatus && $approvalStatus !== 'Rejected'): ?>
                        <div class="flex items-center gap-4 p-4 rounded-lg <?= $approvalStatus == 'Verified' ? 'bg-[#d3e3fd] border border-[#a8c7fa]' : 'bg-[#fef7e0] border border-[#ffe082]' ?>">
                            <span class="material-symbols-outlined text-3xl <?= $approvalStatus == 'Verified' ? 'text-primary' : 'text-[#b06000]' ?>">
                                <?= $approvalStatus == 'Verified' ? 'check_circle' : 'hourglass_empty' ?>
                            </span>
                            <div>
                                <p class="font-bold text-on-surface">Status: <?= esc($approvalStatus) ?></p>
                                <p class="text-sm text-on-surface-variant">
                                    <?= $approvalStatus == 'Verified' ? 'You are fully verified and can post properties.' : 'Your identity documents are currently under review.' ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-surface-container-lowest p-4 border border-outline-variant rounded-lg">
                            <?php if ($approvalStatus === 'Rejected'): ?>
                                <div class="bg-[#ffdad6] text-[#410002] border border-[#ffb4ab] p-3 rounded mb-4 text-sm flex gap-2">
                                    <span class="material-symbols-outlined text-[20px]">error</span>
                                    Your previous verification document was rejected. Please upload a clear, legible photo of your valid KTP.
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-on-surface-variant mb-4">To post listings on HuniKita, you must first verify your identity. Please upload a clear photo of your KTP.</p>
                            <?php endif; ?>
                            
                            <form action="<?= base_url('admin/profile/upload-docs') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                                <?= csrf_field() ?>
                                <div>
                                    <label class="block text-sm font-semibold text-on-surface mb-1">KTP Document <span class="text-error">*</span></label>
                                    <input type="file" name="ktp_document" accept="image/*,.pdf" required class="w-full p-2 border border-outline-variant rounded bg-surface text-sm">
                                    <?= session('errors.ktp_document') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.ktp_document')).'</p>' : '' ?>
                                </div>
                                <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition shadow-sm">Submit for Verification</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">shield_lock</span> Security Settings
                </h3>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-on-surface">Two-Factor Authentication (2FA)</p>
                            <p class="text-sm text-on-surface-variant">Require an extra security code when logging in.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer">
                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="pt-4 border-t border-outline-variant flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-on-surface">Account Password</p>
                            <p class="text-sm text-on-surface-variant">Ensure your account is using a long, random password.</p>
                        </div>
                        <button @click="showPasswordModal = true" class="px-4 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm" id="settings">
                <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">settings</span> App Preferences
                </h3>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-on-surface">Email Notifications</p>
                            <p class="text-sm text-on-surface-variant">Receive alerts for new leads and verifications.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-outline-variant">
                        <div>
                            <p class="font-semibold text-on-surface">Dark Mode Default</p>
                            <p class="text-sm text-on-surface-variant">Force the dashboard to always load in dark theme.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" x-data="{ isDark: document.documentElement.classList.contains('dark') }">
                            <input type="checkbox" x-model="isDark" @change="isDark ? (document.documentElement.classList.add('dark'), localStorage.theme = 'dark') : (document.documentElement.classList.remove('dark'), localStorage.theme = 'light')" class="sr-only peer">
                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-bold text-[#ba1a1a] mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#ba1a1a]">warning</span> Danger Zone
                </h3>
                <p class="text-sm text-on-surface-variant mb-4">
                    Deleting your account will immediately hide your profile and active listings. <strong>Your data will be kept for 60 days.</strong> If you change your mind, simply log back in within 60 days to restore your account.
                </p>
                <button type="button" @click="showDeleteModal = true" class="px-4 py-2 bg-[#ba1a1a] text-white rounded font-semibold hover:bg-[#93000a] transition shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">person_remove</span> Delete My Account
                </button>
            </div>

        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditModal = false" x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-lg rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Profile</h2>
                <button type="button" @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="<?= base_url('admin/profile/update') ?>" method="POST">
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">First Name <span class="text-error">*</span></label>
                            <input type="text" name="first_name" value="<?= esc($user['first_name']) ?>" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                            <?= session('errors.first_name') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.first_name')).'</p>' : '' ?>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Last Name <span class="text-error">*</span></label>
                            <input type="text" name="last_name" value="<?= esc($user['last_name']) ?>" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                            <?= session('errors.last_name') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.last_name')).'</p>' : '' ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Email Address <span class="text-error">*</span></label>
                        <input type="email" name="email" value="<?= esc($user['email']) ?>" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                        <?= session('errors.email') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.email')).'</p>' : '' ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Phone Number <span class="text-error">*</span></label>
                        <input type="tel" name="phone_number" value="<?= esc($user['phone_number']) ?>" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                        <?= session('errors.phone_number') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.phone_number')).'</p>' : '' ?>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Modal -->
    <div x-show="showPasswordModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showPasswordModal = false" x-show="showPasswordModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Change Password</h2>
                <button type="button" @click="showPasswordModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="<?= base_url('admin/profile/update-password') ?>" method="POST">
                <div class="p-6 space-y-4">
                    
                    <?php $hasLocalPassword = !empty($user['password']); ?>
                    <?php if($hasLocalPassword): ?>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Current Password <span class="text-error">*</span></label>
                            <input type="password" name="current_password" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                            <?= session('errors.current_password') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.current_password')).'</p>' : '' ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-surface-container-low text-on-surface-variant p-3 rounded text-sm border border-outline-variant">
                            You registered with Google. Set a new password below to enable email login.
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">New Password <span class="text-error">*</span></label>
                        <input type="password" name="new_password" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                        <p class="text-xs text-on-surface-variant mt-1">Must be at least 8 characters long.</p>
                        <?= session('errors.new_password') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.new_password')).'</p>' : '' ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Confirm New Password <span class="text-error">*</span></label>
                        <input type="password" name="confirm_password" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                        <?= session('errors.confirm_password') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.confirm_password')).'</p>' : '' ?>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showPasswordModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition shadow-sm">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#ba1a1a]">warning</span> Confirm Deletion
                </h2>
                <button type="button" @click="showDeleteModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-gray-600 text-sm">
                    Are you sure you want to delete your account? You have 60 days to recover it before your account and all associated property listings are permanently erased.
                </p>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                <button type="button" @click="showDeleteModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form action="<?= base_url('user/delete-account') ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-6 py-2 bg-[#ba1a1a] text-white rounded font-semibold hover:bg-[#93000a] transition shadow-sm">Yes, Delete Account</button>
                </form>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>