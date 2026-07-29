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
                            <th class="py-4 px-6 font-label-md text-[14px] text-on-surface-variant font-bold">Date</th>
                            <th class="py-4 px-6 font-label-md text-[14px] text-on-surface-variant font-bold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-[14px] text-on-surface">
                        <?php $currentUserId = session()->get('id'); ?>
                        <?php if(!empty($inquiries)): foreach($inquiries as $inq): ?>
                            <?php 
                                // Check if the message is a reply sent TO the current user
                                $isReply = ($inq->receiver_id == $currentUserId); 
                            ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors group cursor-pointer <?= $isReply ? 'bg-primary/5' : '' ?>">
                                <td class="py-4 px-6 align-top">
                                    <div class="flex flex-col">
                                        <a href="<?= base_url('property/' . $inq->property_id) ?>" class="font-semibold text-primary hover:underline text-[16px] mb-1">
                                            <?= esc($inq->property_title ?? 'Unknown Property') ?>
                                        </a>
                                        <span class="text-[12px] text-on-surface-variant mb-2"><?= esc($inq->address_line_1) ?></span>
                                        
                                        <div>
                                            <?php if($isReply): ?>
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-primary-container text-white px-2 py-1 rounded">
                                                    <span class="material-symbols-outlined text-[14px]">reply</span> Reply from Agent
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-surface-container-high text-on-surface-variant px-2 py-1 rounded">
                                                    <span class="material-symbols-outlined text-[14px]">send</span> Sent by you
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant max-w-sm align-top">
                                    <?php if($isReply): ?>
                                        <div class="font-bold text-on-surface mb-1 text-[13px]">Agent <?= esc($inq->first_name . ' ' . $inq->last_name) ?> said:</div>
                                    <?php else: ?>
                                        <div class="font-bold text-on-surface-variant mb-1 text-[13px]">Your Message:</div>
                                    <?php endif; ?>
                                    
                                    <div class="text-[13px] bg-surface-container-lowest p-3 rounded border border-outline-variant/40 whitespace-pre-wrap shadow-sm">
                                        <?= nl2br(esc($inq->message)) ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant font-medium align-top whitespace-nowrap">
                                    <?= date('M d, Y', strtotime($inq->created_at)) ?>
                                </td>
                                <td class="py-4 px-6 text-right align-top">
                                    <?php 
                                        $statusColor = 'bg-surface-container-high text-on-surface-variant border border-outline-variant'; 
                                        if ($inq->status == 'Pending') $statusColor = 'bg-primary-container text-white';
                                        if (in_array($inq->status, ['In Discussion', 'Replied', 'Negotiating'])) $statusColor = 'bg-[#fef7e0] text-[#31302b] border border-[#eaddb9]';
                                        if ($inq->status == 'Under Contract') $statusColor = 'bg-secondary-container text-on-secondary-container';
                                        if ($inq->status == 'Closed') $statusColor = 'bg-[#d3e3fd] text-[#041e49] border border-[#a8c7fa]';
                                        if ($inq->status == 'Cancelled') $statusColor = 'bg-error-container text-error border border-error/20';
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold <?= $statusColor ?>">
                                        <?= esc($inq->status ?? 'Sent') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <span class="material-symbols-outlined text-[48px] opacity-20 mb-4 block">mail</span>
                                    <p class="text-on-surface-variant text-lg">You have no inquiries in your inbox.</p>
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