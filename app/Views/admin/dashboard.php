<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="mb-stack-lg mt-4">
    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-unit">Dashboard Overview</h2>
    <p class="font-body-md text-body-md text-on-surface-variant">Welcome back. Here is the current status of the marketplace.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-stack-lg">
    <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
        <div class="flex justify-between items-start mb-stack-sm">
            <span class="font-label-md text-label-md text-on-surface-variant">Pending Tasks</span>
            <span class="material-symbols-outlined text-primary">assignment_late</span>
        </div>
        <div class="font-headline-lg text-headline-lg text-primary">142</div>
        <div class="font-caption text-caption text-error mt-unit flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">trending_up</span> +12% from yesterday
        </div>
    </div>
    
    <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
        <div class="flex justify-between items-start mb-stack-sm">
            <span class="font-label-md text-label-md text-on-surface-variant">Active Users</span>
            <span class="material-symbols-outlined text-primary">group</span>
        </div>
        <div class="font-headline-lg text-headline-lg text-primary">24.5k</div>
        <div class="font-caption text-caption text-tertiary-container mt-unit flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">trending_up</span> +5% this week
        </div>
    </div>
    
    <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
        <div class="flex justify-between items-start mb-stack-sm">
            <span class="font-label-md text-label-md text-on-surface-variant">Total Revenue</span>
            <span class="material-symbols-outlined text-primary">payments</span>
        </div>
        <div class="font-headline-lg text-headline-lg text-primary">$1.2M</div>
        <div class="font-caption text-caption text-tertiary-container mt-unit flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">trending_up</span> +8% this month
        </div>
    </div>
    
    <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
        <div class="flex justify-between items-start mb-stack-sm">
            <span class="font-label-md text-label-md text-on-surface-variant">Moderation Queue</span>
            <span class="material-symbols-outlined text-primary">gavel</span>
        </div>
        <div class="font-headline-lg text-headline-lg text-primary">89</div>
        <div class="font-caption text-caption text-error mt-unit flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">trending_up</span> Needs attention
        </div>
    </div>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden mb-stack-lg">
    <div class="p-stack-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
        <div>
            <h3 class="font-brand-text text-brand-text text-on-surface">Verification Center</h3>
            <p class="font-caption text-caption text-on-surface-variant">Pending documents and payments requiring manual review.</p>
        </div>
        <button class="text-primary font-label-md text-label-md hover:underline flex items-center gap-1">
            View All <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </button>
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
                            <img alt="User avatar" class="w-6 h-6 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe&background=e0e3e5&color=002045"/>
                            <span class="font-body-md text-body-md text-on-surface">John Doe</span>
                        </div>
                    </td>
                    <td class="p-stack-md font-body-md text-body-md text-on-surface-variant">Oct 24, 2023</td>
                    <td class="p-stack-md">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-caption text-caption border border-secondary">
                            Pending Review
                        </span>
                    </td>
                    <td class="p-stack-md text-right space-x-2">
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
                            <img alt="User avatar" class="w-6 h-6 rounded-full" src="https://ui-avatars.com/api/?name=Jane+Smith&background=e0e3e5&color=002045"/>
                            <span class="font-body-md text-body-md text-on-surface">Jane Smith</span>
                        </div>
                    </td>
                    <td class="p-stack-md font-body-md text-body-md text-on-surface-variant">Oct 23, 2023</td>
                    <td class="p-stack-md">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-caption text-caption border border-secondary">
                            Pending Review
                        </span>
                    </td>
                    <td class="p-stack-md text-right space-x-2">
                        <button class="bg-primary-container text-on-primary-container font-label-md text-label-md px-3 py-1.5 rounded hover:opacity-90 transition-opacity">Approve</button>
                        <button class="border border-outline font-label-md text-label-md px-3 py-1.5 rounded hover:bg-surface-container-high transition-colors">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>