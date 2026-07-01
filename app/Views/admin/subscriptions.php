<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="flex-1 p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto w-full">
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="mb-stack-lg">
        <h2 class="font-headline-lg text-headline-lg text-primary mb-unit">Subscription Management</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Verify and manage offline payment requests and user accounts.</p>
    </div>

    <!-- Data Table Card -->
    <div class="bg-surface rounded-lg border border-outline-variant overflow-hidden hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">User Name</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Requested Plan</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Date Submitted</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Payment Proof</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Status</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-body-md text-on-surface">
                    <?php if(!empty($subscriptions)): foreach($subscriptions as $sub): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="py-4 px-4 flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-fixed text-on-primary-fixed rounded-full flex items-center justify-center font-label-md uppercase">
                                    <?= substr($sub->first_name, 0, 1) . substr($sub->last_name ?? '', 0, 1) ?>
                                </div>
                                <span class="font-medium"><?= esc($sub->first_name . ' ' . $sub->last_name) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-label-md text-caption">
                                    <span class="material-symbols-outlined text-[14px]">workspace_premium</span>
                                    <?= esc($sub->plan_name) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-on-surface-variant"><?= date('d M Y', strtotime($sub->created_date)) ?></td>
                            <td class="py-4 px-4">
                                <button class="flex items-center gap-1 text-primary hover:underline font-label-md text-label-md">
                                    <span class="material-symbols-outlined text-[18px]">receipt_long</span> View Receipt
                                </button>
                            </td>
                            <td class="py-4 px-4">
                                <?php if($sub->status == 'Pending'): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-error-container text-on-error-container font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">pending_actions</span> Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-tertiary-container text-on-tertiary-container font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Active
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <?php if($sub->status == 'Pending'): ?>
                                    <form action="<?= base_url('admin/subscriptions/activate/' . $sub->id) ?>" method="POST">
                                        <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md hover:opacity-90 transition-opacity">
                                            Activate
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="text-primary px-4 py-2 rounded font-label-md text-label-md hover:bg-primary-fixed transition-colors" disabled>
                                        Manage
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="py-6 text-center text-outline">No subscription requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>