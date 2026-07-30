<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<header class="mb-stack-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-stack-md mt-4">
    <div>
        <h2 class="font-headline-lg text-primary mb-unit">User Management</h2>
        <p class="text-on-surface-variant">Oversee and manage registered marketplace accounts.</p>
    </div>
    <a href="<?= base_url('admin/users/create') ?>" class="bg-primary text-on-primary px-4 py-2 rounded font-semibold flex items-center gap-2 hover:opacity-90 transition shadow-sm">
        <span class="material-symbols-outlined text-[18px]">person_add</span> Create User
    </a>
</header>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div x-data="{ showRoleModal: false, selectedUserId: 0, selectedRole: 1, userName: '', showDeleteModal: false, deleteUrl: '', deleteUserName: '', showDocModal: false, docUrl: '', docStatus: '', docDate: '', docUser: '' }" class="flex flex-col gap-unit">
    
    <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-surface border-b border-outline-variant font-label-md uppercase tracking-wider rounded-t">
        <div class="col-span-4">User Details</div>
        <div class="col-span-3">Role</div>
        <div class="col-span-3">Registration Date</div>
        <div class="col-span-2 text-right">Actions</div>
    </div>

    <?php if(!empty($users) && is_array($users)): ?>
        <?php foreach($users as $user): ?>
            <?php 
                $initials = strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1));
                $roleName = 'Visitor';
                $badgeColor = 'bg-surface-container-high text-on-surface-variant';
                
                if ($user['role_id'] == 4) { $roleName = 'Admin'; $badgeColor = 'bg-primary-fixed text-on-primary-fixed'; }
                if ($user['role_id'] == 3) { $roleName = 'Agent'; $badgeColor = 'bg-secondary-fixed text-on-secondary-fixed'; }
                if ($user['role_id'] == 2) { $roleName = 'Owner'; $badgeColor = 'bg-tertiary-fixed text-on-tertiary-fixed'; }
                if ($user['role_id'] == 1) { $roleName = 'Buyer'; $badgeColor = 'bg-surface-container-high text-on-surface-variant'; }

                $isSuspended = !empty($user['deleted_at']);
                $userDoc = $agentDocs[$user['id']] ?? null;
            ?>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 md:px-6 md:py-4 bg-surface <?= $isSuspended ? 'opacity-60 bg-surface-container-lowest' : '' ?> border border-outline-variant rounded transition-all">
                <div class="col-span-1 md:col-span-4 flex items-center gap-stack-md">
                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold shrink-0"><?= $initials ?></div>
                    <div>
                        <p class="font-label-md text-primary"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></p>
                        <p class="font-caption text-outline"><?= esc($user['email']) ?> <?= $isSuspended ? '<span class="text-error font-bold">(Suspended)</span>' : '' ?></p>
                    </div>
                </div>
                <div class="col-span-1 md:col-span-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $badgeColor ?>"><?= $roleName ?></span>
                </div>
                <div class="col-span-1 md:col-span-3 text-on-surface-variant">
                    <?= date('M d, Y', strtotime($user['created_date'])) ?>
                </div>
                <div class="col-span-1 md:col-span-2 flex justify-end gap-2">
                    <?php if(!$isSuspended): ?>
                        
                        <?php if (($user['role_id'] == 2 || $user['role_id'] == 3) && $userDoc): ?>
                            <button @click="showDocModal = true; 
                                            docUrl = '<?= strpos($userDoc->ktp_document, 'http') === 0 ? esc($userDoc->ktp_document) : base_url('uploads/documents/' . esc($userDoc->ktp_document)) ?>';
                                            docStatus = '<?= esc($userDoc->approval_status) ?>';
                                            docDate = '<?= date('M d, Y', strtotime($userDoc->created_at ?? $userDoc->created_date ?? 'now')) ?>';
                                            docUser = '<?= esc(addslashes($user['first_name'] . ' ' . $user['last_name'])) ?>';" 
                                    class="text-secondary hover:bg-surface-container-high p-2 rounded-full transition-colors" title="View Identity Document">
                                <span class="material-symbols-outlined">badge</span>
                            </button>
                        <?php endif; ?>

                        <button @click="showRoleModal = true; 
                                        selectedUserId = <?= $user['id'] ?>; 
                                        selectedRole = <?= $user['role_id'] ?>; 
                                        userName = '<?= esc(addslashes($user['first_name'] . ' ' . $user['last_name'])) ?>';" 
                                class="text-primary hover:bg-surface-container-high p-2 rounded-full transition-colors" title="Change Role">
                            <span class="material-symbols-outlined">manage_accounts</span>
                        </button>

                        <button @click="showDeleteModal = true; 
                                        deleteUrl = '<?= base_url('admin/users/delete/' . $user['id']) ?>'; 
                                        deleteUserName = '<?= esc(addslashes($user['first_name'] . ' ' . $user['last_name'])) ?>';" 
                                class="text-error hover:bg-error-container p-2 rounded-full transition-colors" title="Suspend User">
                            <span class="material-symbols-outlined">person_remove</span>
                        </button>

                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Document Viewer Modal -->
    <div x-show="showDocModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDocModal = false" x-show="showDocModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Identity Verification Record</h2>
                    <p class="text-sm text-on-surface-variant">Submitted by <span class="font-semibold" x-text="docUser"></span> on <span x-text="docDate"></span></p>
                </div>
                <button @click="showDocModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 bg-surface-container-low flex justify-center items-center min-h-[300px] max-h-[60vh] overflow-y-auto relative">
                <div class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-xs font-bold shadow-sm"
                     :class="docStatus === 'Verified' ? 'bg-primary text-on-primary' : (docStatus === 'Rejected' ? 'bg-error text-on-error' : 'bg-[#fef7e0] text-[#b06000]')">
                    <span x-text="docStatus"></span>
                </div>
                <template x-if="docUrl && !docUrl.endsWith('/')">
                    <img :src="docUrl" alt="Document Preview" class="max-w-full rounded border border-outline-variant shadow-sm" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'text-center text-outline-variant\'><span class=\'material-symbols-outlined text-[48px] mb-2\'>broken_image</span><p>Image file not found on server.</p></div>';">
                </template>
                <template x-if="!docUrl || docUrl.endsWith('/')">
                    <div class="flex flex-col items-center text-outline-variant">
                        <span class="material-symbols-outlined text-[48px] mb-2">image_not_supported</span>
                        <p class="text-sm">No valid document file uploaded.</p>
                    </div>
                </template>
            </div>
            
            <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                <button type="button" @click="showDocModal = false" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Close Viewer</button>
            </div>
        </div>
    </div>

    <!-- Role Modal -->
    <div x-show="showRoleModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showRoleModal = false" x-show="showRoleModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Change User Role</h2>
                <button type="button" @click="showRoleModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form :action="'<?= base_url('admin/users/updateRole/') ?>' + selectedUserId" method="POST">
                <div class="p-6">
                    <p class="text-sm text-on-surface-variant mb-4">Updating role for <span class="font-bold text-on-surface" x-text="userName"></span></p>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Select New Role</label>
                    <select name="role_id" x-model="selectedRole" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <option value="4">Admin</option>
                        <option value="3">Agent</option>
                        <option value="2">Owner</option>
                        <option value="1">Buyer</option>
                    </select>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showRoleModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">no_accounts</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Suspend User Account</h2>
                <p class="text-sm text-on-surface-variant mb-2">Are you sure you want to suspend <span class="font-bold text-on-surface" x-text="deleteUserName"></span>?</p>
                <p class="text-xs text-error">They will immediately lose access to the platform.</p>
            </div>
            <div class="px-6 py-4 flex justify-between gap-3 bg-surface-container-lowest border-t border-outline-variant">
                <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    <button type="submit" class="w-full px-4 py-2 bg-error text-on-error rounded font-semibold hover:opacity-90 transition">Suspend</button>
                </form>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>