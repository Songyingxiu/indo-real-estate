<?= $this->include('front/layout/header') ?>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[60vh]">
    <div class="mb-8">
        <h1 class="font-headline-lg text-[32px] font-bold text-primary">My Inbox</h1>
        <p class="text-on-surface-variant font-body-md">Track the status of properties you've inquired about.</p>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
                    <tr>
                        <th class="p-4 font-semibold text-on-surface-variant">Date Sent</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Property</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Your Message</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Current Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if(!empty($leads)): foreach($leads as $lead): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="p-4 text-on-surface-variant whitespace-nowrap">
                                <?= date('M d, Y', strtotime($lead->created_date)) ?>
                            </td>
                            <td class="p-4">
                                <a href="<?= base_url('property/' . $lead->property_id) ?>" class="font-semibold text-primary hover:underline">
                                    <?= esc($lead->property_title ?? 'Unknown Property') ?>
                                </a>
                            </td>
                            <td class="p-4 text-on-surface-variant max-w-md truncate">
                                <?= esc($lead->message) ?>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $statusColor = 'bg-surface-container-high text-on-surface';
                                    if ($lead->lead_status == 'New') $statusColor = 'bg-primary-container text-on-primary-container';
                                    if (in_array($lead->lead_status, ['Contacted', 'Follow Up'])) $statusColor = 'bg-secondary-container text-on-secondary-container';
                                    if (in_array($lead->lead_status, ['Qualified', 'Negotiation'])) $statusColor = 'bg-[#fef7e0] text-[#31302b] border border-[#eaddb9]';
                                    if ($lead->lead_status == 'Won') $statusColor = 'bg-[#d3e3fd] text-[#041e49] border border-[#a8c7fa]';
                                    if ($lead->lead_status == 'Lost') $statusColor = 'bg-error-container text-on-error-container';
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                                    <?= esc($lead->lead_status ?? 'Sent') ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="py-12 text-center text-on-surface-variant">You haven't sent any inquiries yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($pager) : ?>
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                <?= $pager->links('default', 'tailwind_pagination') ?>
            </div>
        <?php endif ?>
    </div>
</main>

<?= $this->include('front/layout/footer') ?>