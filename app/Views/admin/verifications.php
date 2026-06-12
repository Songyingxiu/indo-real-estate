<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="mb-stack-lg">
    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-unit">Verification Center</h2>
    <p class="font-body-md text-body-md text-on-surface-variant">Review pending documents and payments requiring manual administrative approval.</p>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm">
    <div class="p-stack-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
        <div>
            <h3 class="font-brand-text text-brand-text text-on-surface">Action Required</h3>
            <p class="font-caption text-caption text-on-surface-variant">Please review the following submissions carefully.</p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-lowest border-b border-outline-variant">
                    <th class="font-label-md text-label-md text-on-surface-variant p-stack-md font-semibold">Type</th>
                    <th class="font-label-md text-label-md text-on-surface-variant p-stack-md font-semibold">Submitted By</th>
                    <th class="font-label-md text-label-md text-on-surface-variant p-stack-md font-semibold">Date</th>
                    <th class="font-label-md text-label-md text-on-surface-variant p-stack-md font-semibold">Status</th>
                    <th class="font-label-md text-label-md text-on-surface-variant p-stack-md font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                
                <tr class="bg-surface-container-lowest hover:bg-surface-container transition-colors">
                    <td class="p-stack-md">
                        <div class="flex items-center gap-stack-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                            </div>
                            <span class="font-body-md text-body-md text-on-surface">Offline Payment</span>
                        </div>
                    </td>
                    <td class="p-stack-md">
                        <div class="flex items-center gap-stack-sm">
                            <div class="w-8 h-8 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center font-label-md text-xs">JD</div>
                            <span class="font-body-md text-body-md text-on-surface">John Doe</span>
                        </div>
                    </td>
                    <td class="p-stack-md font-body-md text-body-md text-on-surface-variant">Oct 24, 2023</td>
                    <td class="p-stack-md">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-caption text-caption border border-secondary">Pending Review</span>
                    </td>
                    <td class="p-stack-md text-right space-x-2 whitespace-nowrap">
                        <button class="bg-primary-container text-on-primary-container font-label-md text-label-md px-3 py-1.5 rounded hover:opacity-90 transition-opacity">Approve</button>
                        <button class="border border-outline font-label-md text-label-md px-3 py-1.5 rounded hover:bg-surface-container-high transition-colors">Reject</button>
                    </td>
                </tr>

                <tr class="bg-background hover:bg-surface-container transition-colors">
                    <td class="p-stack-md">
                        <div class="flex items-center gap-stack-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">id_card</span>
                            </div>
                            <span class="font-body-md text-body-md text-on-surface">Agent KTP</span>
                        </div>
                    </td>
                    <td class="p-stack-md">
                        <div class="flex items-center gap-stack-sm">
                            <div class="w-8 h-8 bg-tertiary-container text-on-tertiary-container rounded-full flex items-center justify-center font-label-md text-xs">JS</div>
                            <span class="font-body-md text-body-md text-on-surface">Jane Smith</span>
                        </div>
                    </td>
                    <td class="p-stack-md font-body-md text-body-md text-on-surface-variant">Oct 23, 2023</td>
                    <td class="p-stack-md">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-caption text-caption border border-secondary">Pending Review</span>
                    </td>
                    <td class="p-stack-md text-right space-x-2 whitespace-nowrap">
                        <button class="bg-primary-container text-on-primary-container font-label-md text-label-md px-3 py-1.5 rounded hover:opacity-90 transition-opacity">Approve</button>
                        <button class="border border-outline font-label-md text-label-md px-3 py-1.5 rounded hover:bg-surface-container-high transition-colors">Reject</button>
                    </td>
                </tr>

                <tr class="bg-surface-container-lowest hover:bg-surface-container transition-colors">
                    <td class="p-stack-md">
                        <div class="flex items-center gap-stack-sm">
                            <div class="w-8 h-8 rounded bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[18px]">description</span>
                            </div>
                            <span class="font-body-md text-body-md text-on-surface">Property Document</span>
                        </div>
                    </td>
                    <td class="p-stack-md">
                        <div class="flex items-center gap-stack-sm">
                            <div class="w-8 h-8 bg-primary-fixed text-on-primary-fixed rounded-full flex items-center justify-center font-label-md text-xs">ML</div>
                            <span class="font-body-md text-body-md text-on-surface">Michael Lee</span>
                        </div>
                    </td>
                    <td class="p-stack-md font-body-md text-body-md text-on-surface-variant">Oct 23, 2023</td>
                    <td class="p-stack-md">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-caption text-caption border border-secondary">Pending Review</span>
                    </td>
                    <td class="p-stack-md text-right space-x-2 whitespace-nowrap">
                        <button class="bg-primary-container text-on-primary-container font-label-md text-label-md px-3 py-1.5 rounded hover:opacity-90 transition-opacity">Approve</button>
                        <button class="border border-outline font-label-md text-label-md px-3 py-1.5 rounded hover:bg-surface-container-high transition-colors">Reject</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>