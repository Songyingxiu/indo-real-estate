<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="mb-stack-lg mt-4">
    <h2 class="font-headline-lg text-headline-lg text-primary mb-unit">Subscription Management</h2>
    <p class="font-body-md text-body-md text-on-surface-variant">Verify and manage offline payment requests.</p>
</div>

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
                
                <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-4 px-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-fixed text-on-primary-fixed rounded-full flex items-center justify-center font-label-md">BS</div>
                        <span class="font-medium">Budi Santoso</span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-label-md text-caption">
                            <span class="material-symbols-outlined text-[14px]">workspace_premium</span> Premium
                        </span>
                    </td>
                    <td class="py-4 px-4 text-on-surface-variant">15 Oct 2024</td>
                    <td class="py-4 px-4">
                        <button class="flex items-center gap-1 text-primary hover:underline font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span> View Receipt
                        </button>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-error-container text-on-error-container font-label-md text-caption">
                            <span class="material-symbols-outlined text-[14px]">pending_actions</span> Pending
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md hover:opacity-90 transition-opacity">
                            Activate
                        </button>
                    </td>
                </tr>
                
                <tr class="border-b border-outline-variant bg-surface-bright hover:bg-surface-container-low transition-colors">
                    <td class="py-4 px-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-tertiary-fixed text-on-tertiary-fixed rounded-full flex items-center justify-center font-label-md">SW</div>
                        <span class="font-medium">Sari Wijaya</span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded border border-outline-variant text-on-surface font-label-md text-caption bg-surface">
                            Basic
                        </span>
                    </td>
                    <td class="py-4 px-4 text-on-surface-variant">14 Oct 2024</td>
                    <td class="py-4 px-4">
                        <button class="flex items-center gap-1 text-primary hover:underline font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span> View Receipt
                        </button>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-tertiary-container text-on-tertiary-container font-label-md text-caption">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> Verified
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="text-primary px-4 py-2 rounded font-label-md text-label-md hover:bg-primary-fixed transition-colors">
                            Manage
                        </button>
                    </td>
                </tr>
                
                <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                    <td class="py-4 px-4 flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-fixed text-on-primary-fixed rounded-full flex items-center justify-center font-label-md">AH</div>
                        <span class="font-medium">Agus Hakim</span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-primary text-on-primary font-label-md text-caption">
                            <span class="material-symbols-outlined text-[14px]">business</span> Enterprise
                        </span>
                    </td>
                    <td class="py-4 px-4 text-on-surface-variant">12 Oct 2024</td>
                    <td class="py-4 px-4">
                        <button class="flex items-center gap-1 text-primary hover:underline font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span> View Receipt
                        </button>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-error-container text-on-error-container font-label-md text-caption">
                            <span class="material-symbols-outlined text-[14px]">pending_actions</span> Pending
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md hover:opacity-90 transition-opacity">
                            Activate
                        </button>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>