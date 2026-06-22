<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<header class="mb-stack-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-stack-md mt-4">
    <div>
        <h2 class="font-headline-lg text-primary mb-unit">User Management</h2>
        <p class="text-on-surface-variant">Oversee and manage registered marketplace accounts.</p>
    </div>
</header>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="flex flex-col gap-unit">
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

                $isSuspended = !empty($user['deleted_at']);
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
                        <form action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="POST" onsubmit="return confirm('Suspend this user account?');">
                            <button type="submit" class="text-error hover:bg-error-container p-2 rounded-full transition-colors"><span class="material-symbols-outlined">person_remove</span></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>