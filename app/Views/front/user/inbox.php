<?= $this->include('front/layout/header') ?>

<main class="flex-grow max-w-[1280px] w-full mx-auto px-4 md:px-10 py-12 flex flex-col md:flex-row gap-8 items-start min-h-[70vh]">
    
    <aside class="w-full md:w-64 flex-shrink-0 sticky top-28 hidden md:block">
        <h2 class="font-headline-lg text-[24px] font-bold text-primary mb-6">My Workspace</h2>
        <nav class="flex flex-col gap-2">
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary-container text-white font-label-md text-[14px] font-semibold transition-all shadow-sm" href="<?= base_url('user/inbox') ?>">
                <span class="material-symbols-outlined">forum</span>
                My Inquiries
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-primary font-label-md text-[14px] font-semibold transition-all" href="<?= base_url('user/profile') ?>">
                <span class="material-symbols-outlined">settings</span>
                Profile Settings
            </a>
        </nav>
    </aside>

    <div class="w-full flex flex-col gap-8">
        
        <header class="flex flex-col gap-2 border-b border-outline-variant pb-4">
            <h1 class="font-headline-lg text-[28px] font-bold text-on-surface">My Inquiries</h1>
            <p class="font-body-md text-[16px] text-on-surface-variant">Track your communication with agents and upcoming property visits.</p>
        </header>

        <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden flex flex-col shadow-sm hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-low">
                            <th class="py-4 px-6 font-label-md text-[14px] text-on-surface-variant font-bold">Property Details</th>
                            <th class="py-4 px-6 font-label-md text-[14px] text-on-surface-variant font-bold">Message</th>
                            <th class="py-4 px-6 font-label-md text-[14px] text-on-surface-variant font-bold">Date Sent</th>
                            <th class="py-4 px-6 font-label-md text-[14px] text-on-surface-variant font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-[14px] text-on-surface">
                        <?php if(!empty($leads)): foreach($leads as $lead): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors group cursor-pointer">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col">
                                            <a href="<?= base_url('property/' . $lead->property_id) ?>" class="font-semibold text-primary hover:underline text-[16px]">
                                                <?= esc($lead->property_title ?? 'Unknown Property') ?>
                                            </a>
                                            <span class="text-[12px] text-on-surface-variant mt-1"><?= esc($lead->address_line_1) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant max-w-xs truncate">
                                    <?= esc($lead->message) ?>
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant font-medium">
                                    <?= date('M d, Y', strtotime($lead->created_date)) ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <?php 
                                        $statusColor = 'bg-surface-container-high text-on-surface-variant border border-outline-variant'; // Default
                                        if ($lead->lead_status == 'New') $statusColor = 'bg-primary-container text-white';
                                        if (in_array($lead->lead_status, ['Contacted', 'Follow Up'])) $statusColor = 'bg-secondary-container text-on-secondary-container';
                                        if (in_array($lead->lead_status, ['Qualified', 'Negotiation'])) $statusColor = 'bg-[#fef7e0] text-[#31302b] border border-[#eaddb9]';
                                        if ($lead->lead_status == 'Won') $statusColor = 'bg-[#d3e3fd] text-[#041e49] border border-[#a8c7fa]';
                                        if ($lead->lead_status == 'Lost') $statusColor = 'bg-error-container text-error border border-error/20';
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold <?= $statusColor ?>">
                                        <?= esc($lead->lead_status ?? 'Sent') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <span class="material-symbols-outlined text-[48px] opacity-20 mb-4 block">mail</span>
                                    <p class="text-on-surface-variant text-lg">You haven't sent any inquiries yet.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (isset($pager)) : ?>
                <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest">
                    <?= $pager->links('default', 'tailwind_pagination') ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</main>

<?= $this->include('front/layout/footer') ?>