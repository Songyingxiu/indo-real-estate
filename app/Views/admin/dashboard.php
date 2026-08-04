<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-stack-lg mt-4 flex justify-between items-end">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface mb-unit">Dashboard Overview</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Here is the current status of your analytics.</p>
    </div>
</div>

<!-- Primary Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-stack-lg">
    <?php if(session()->get('role_id') == 4): ?>
        <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex justify-between items-start mb-stack-sm">
                <span class="font-label-md text-label-md text-on-surface-variant">Pending Tasks</span>
                <span class="material-symbols-outlined text-primary">assignment_late</span>
            </div>
            <div class="font-headline-lg text-headline-lg text-primary mb-2"><?= esc($pendingTasks ?? 0) ?></div>
            <div class="font-caption text-caption text-error flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">trending_up</span> Action Required
            </div>
        </div>
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex justify-between items-start mb-stack-sm">
                <span class="font-label-md text-label-md text-on-surface-variant">Active Users</span>
                <span class="material-symbols-outlined text-primary">group</span>
            </div>
            <div class="font-headline-lg text-headline-lg text-primary mb-2"><?= number_format($totalUsers) ?></div>
            <div class="font-caption text-caption text-[#0d652d] flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">trending_up</span> Platform Total
            </div>
        </div>
    <?php endif; ?>
    
    <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
        <div class="flex justify-between items-start mb-stack-sm">
            <span class="font-label-md text-label-md text-on-surface-variant"><?= session()->get('role_id') == 4 ? 'Active Properties' : 'My Active Properties' ?></span>
            <span class="material-symbols-outlined text-primary">real_estate_agent</span>
        </div>
        <div class="font-headline-lg text-headline-lg text-primary mb-2"><?= number_format($activeProperties) ?></div>
        <div class="font-caption text-caption text-[#0d652d] flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">trending_up</span> Published Listings
        </div>
    </div>

    <?php if(session()->get('role_id') == 4): ?>
        <div class="bg-red-50 border border-red-200 rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex justify-between items-start mb-stack-sm">
                <span class="font-label-md text-label-md text-red-700">Suspended Users</span>
                <span class="material-symbols-outlined text-red-700">block</span>
            </div>
            <div class="font-headline-lg text-headline-lg text-red-700 mb-2"><?= number_format($suspendedUsers) ?></div>
            <div class="font-caption text-caption text-red-600 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">error</span> Deleted / Inactive
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded p-stack-md hover:shadow-lg transition-shadow duration-200">
            <div class="flex justify-between items-start mb-stack-sm">
                <span class="font-label-md text-label-md text-on-surface-variant">Total Revenue</span>
                <span class="material-symbols-outlined text-primary">payments</span>
            </div>
            <div class="font-headline-lg text-headline-lg text-primary mb-2">Rp <?= number_format($revenueSum ?? 0, 0, ',', '.') ?></div>
            <div class="font-caption text-caption text-[#0d652d] flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">trending_up</span> Lifetime Volume
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Detailed Metrics Grids -->
<div class="grid grid-cols-1 md:grid-cols-2 <?= session()->get('role_id') == 4 ? 'lg:grid-cols-3' : 'lg:grid-cols-2' ?> gap-6 mb-8">
    
    <!-- Properties Analytics -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-5">
        <h3 class="font-bold text-on-surface border-b border-outline-variant pb-2 mb-4">Properties Analytics</h3>
        <ul class="space-y-3 text-sm">
            <li class="flex justify-between"><span class="text-on-surface-variant">Total Created</span> <span class="font-semibold"><?= number_format($propTotal) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Created Today</span> <span class="font-semibold text-primary"><?= number_format($propToday) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Last 7 Days</span> <span class="font-semibold"><?= number_format($prop7Days) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Last Week</span> <span class="font-semibold"><?= number_format($propLastWeek) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">This Month</span> <span class="font-semibold"><?= number_format($propThisMonth) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Last Month</span> <span class="font-semibold"><?= number_format($propLastMonth) ?></span></li>
        </ul>
    </div>

    <!-- Inquiries Analytics -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-5">
        <h3 class="font-bold text-on-surface border-b border-outline-variant pb-2 mb-4">Inquiries & Threads</h3>
        <ul class="space-y-3 text-sm">
            <li class="flex justify-between"><span class="text-on-surface-variant">Total Inquiries</span> <span class="font-semibold"><?= number_format($inqTotal) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Successfully Completed</span> <span class="font-semibold text-[#0d652d]"><?= number_format($inqCompleted) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Received Today</span> <span class="font-semibold text-primary"><?= number_format($inqToday) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">This Week</span> <span class="font-semibold"><?= number_format($inqWeekly) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">This Month</span> <span class="font-semibold"><?= number_format($inqMonthly) ?></span></li>
        </ul>
    </div>

    <!-- Subscriptions Analytics (Admin Only) -->
    <?php if(session()->get('role_id') == 4): ?>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-5">
        <h3 class="font-bold text-on-surface border-b border-outline-variant pb-2 mb-4">Subscriptions Growth</h3>
        <ul class="space-y-3 text-sm">
            <li class="flex justify-between"><span class="text-on-surface-variant">Total Subscriptions</span> <span class="font-semibold"><?= number_format($subTotal ?? 0) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">New Today</span> <span class="font-semibold text-primary"><?= number_format($subToday ?? 0) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Last 7 Days</span> <span class="font-semibold"><?= number_format($sub7Days ?? 0) ?></span></li>
            <li class="flex justify-between"><span class="text-on-surface-variant">Last 30 Days</span> <span class="font-semibold"><?= number_format($sub30Days ?? 0) ?></span></li>
        </ul>
    </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-on-surface mb-4"><?= session()->get('role_id') == 4 ? 'Platform Growth Chart' : 'My Activity Chart' ?></h3>
        <div class="relative h-[300px] w-full">
            <canvas id="platformAnalyticsChart"></canvas>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg shadow-sm p-6 flex flex-col gap-3">
        <h3 class="text-lg font-bold text-on-surface mb-2">Quick Actions</h3>
        
        <!-- Visible to everyone -->
        <a href="<?= base_url('admin/properties/create') ?>" class="w-full flex items-center gap-3 p-3 bg-surface border border-outline-variant rounded hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-primary">add_box</span>
            <span class="font-medium text-sm text-on-surface">Add New Property</span>
        </a>
        <a href="<?= base_url('admin/inquiries') ?>" class="w-full flex items-center gap-3 p-3 bg-surface border border-outline-variant rounded hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-primary">forum</span>
            <span class="font-medium text-sm text-on-surface">View Inquiries</span>
        </a>

        <!-- Visible ONLY to Admins -->
        <?php if(session()->get('role_id') == 4): ?>
        <a href="<?= base_url('admin/users') ?>" class="w-full flex items-center gap-3 p-3 bg-surface border border-outline-variant rounded hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-primary">person_add</span>
            <span class="font-medium text-sm text-on-surface">Manage Active Users</span>
        </a>
        <a href="<?= base_url('admin/users/suspended') ?>" class="w-full flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined text-red-600">person_off</span>
            <span class="font-medium text-sm text-red-700">Manage Suspended Users</span>
        </a>
        <a href="<?= base_url('admin/seo') ?>" class="w-full flex items-center gap-3 p-3 bg-surface border border-outline-variant rounded hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-primary">search_insights</span>
            <span class="font-medium text-sm text-on-surface">Configure SEO</span>
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if(session()->get('role_id') == 4): ?>
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg mt-8 shadow-sm">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low rounded-t-lg">
            <div>
                <h2 class="text-xl font-bold text-on-surface">Verification Center</h2>
                <p class="text-sm text-on-surface-variant mt-1">Pending documents and payments requiring manual review.</p>
            </div>
            <a href="<?= base_url('admin/verifications') ?>" class="text-primary font-semibold flex items-center gap-1 hover:bg-primary-container hover:text-on-primary-container px-3 py-2 rounded transition-colors">
                View All <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface border-b border-outline-variant">
                    <tr>
                        <th class="p-4 font-semibold text-sm text-on-surface-variant">Type</th>
                        <th class="p-4 font-semibold text-sm text-on-surface-variant">Submitted By</th>
                        <th class="p-4 font-semibold text-sm text-on-surface-variant">Date</th>
                        <th class="p-4 font-semibold text-sm text-on-surface-variant">Status</th>
                        <th class="p-4 font-semibold text-sm text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-on-surface">
                    <?php if(!empty($verifications)): ?>
                        <?php foreach($verifications as $task): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="p-4 flex items-center gap-3">
                                    <div class="bg-surface-container p-2 rounded border border-outline-variant">
                                        <span class="material-symbols-outlined text-outline-variant"><?= esc($task['icon']) ?></span>
                                    </div>
                                    <span class="font-medium text-primary"><?= esc($task['type']) ?></span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-surface-container-high text-xs flex items-center justify-center font-bold text-on-surface-variant">
                                            <?= esc($task['initials']) ?>
                                        </div>
                                        <?= esc($task['submitter']) ?>
                                    </div>
                                </td>
                                <td class="p-4 text-on-surface-variant">
                                    <?php 
                                        $dateVal = $task['date'] ?? $task['created_at'] ?? $task['created_date'] ?? null;
                                        echo !empty($dateVal) ? date('M d, Y', strtotime($dateVal)) : 'N/A';
                                    ?>
                                </td>
                                <td class="p-4">
                                    <span class="bg-[#fef7e0] text-[#b06000] px-3 py-1 rounded-full text-xs font-semibold">
                                        <?= esc($task['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="<?= base_url('admin/verifications') ?>" class="bg-[#2d3142] text-white px-4 py-1.5 rounded font-semibold hover:bg-opacity-90 transition mr-2 shadow-sm inline-block">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-[48px] text-outline-variant mb-2">task_alt</span>
                                    <p>No pending verifications found! You are all caught up.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('platformAnalyticsChart').getContext('2d');
    
    const labels = <?= $chartLabels ?>;
    const dataValues = <?= $chartValues ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Data Count',
                data: dataValues,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)', // Blue
                    'rgba(16, 185, 129, 0.7)', // Green
                    'rgba(245, 158, 11, 0.7)'  // Orange
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 } 
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>