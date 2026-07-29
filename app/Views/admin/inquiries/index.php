<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="font-headline-lg text-[28px] font-bold text-on-surface">Inquiries Inbox</h2>
            <p class="text-on-surface-variant">Manage incoming messages and client communication.</p>
        </div>
    </div>

    <div id="ajaxAlert" class="hidden p-4 rounded mb-6 border flex items-center gap-2 transition-all"></div>

    <div class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant font-label-md text-[14px]">
                    <th class="py-4 px-6 font-semibold">Client Details</th>
                    <th class="py-4 px-6 font-semibold">Property</th>
                    <th class="py-4 px-6 font-semibold">Message</th>
                    <th class="py-4 px-6 font-semibold">Date</th>
                    <th class="py-4 px-6 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-[14px] text-on-surface">
                <?php if (!empty($inquiries)): ?>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-container-low/50">
                            <td class="py-4 px-6">
                                <span class="font-semibold text-primary"><?= esc($inq->first_name . ' ' . $inq->last_name) ?></span><br>
                                <span class="text-xs text-on-surface-variant"><?= esc($inq->email) ?></span>
                            </td>
                            <td class="py-4 px-6 max-w-[200px] truncate">
                                <a href="<?= base_url('property/' . $inq->property_id) ?>" class="text-primary hover:underline font-semibold" target="_blank">
                                    <?= esc($inq->property_title) ?>
                                </a>
                            </td>
                            <td class="py-4 px-6 max-w-sm">
                                <div class="text-[13px] text-on-surface-variant whitespace-pre-wrap max-h-24 overflow-y-auto custom-scrollbar p-2 bg-surface rounded border border-outline-variant/30"><?= esc($inq->message) ?></div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-sm text-on-surface-variant">
                                <?= date('M d, Y h:i A', strtotime($inq->created_at)) ?>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <select onchange="updateInquiryStatus(<?= $inq->inquiry_id ?>, this.value)" class="px-3 py-1.5 rounded bg-surface border border-outline-variant text-[14px] font-semibold cursor-pointer focus:ring-1 focus:ring-primary outline-none">
                                    <option value="Pending" <?= $inq->status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Discussion" <?= $inq->status == 'In Discussion' ? 'selected' : '' ?>>In Discussion</option>
                                    <option value="Negotiating" <?= $inq->status == 'Negotiating' ? 'selected' : '' ?>>Negotiating</option>
                                    <option value="Under Contract" <?= $inq->status == 'Under Contract' ? 'selected' : '' ?>>Under Contract</option>
                                    <option value="Closed" <?= $inq->status == 'Closed' ? 'selected' : '' ?>>Closed</option>
                                    <option value="Cancelled" <?= $inq->status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="py-8 text-center text-on-surface-variant">No inquiries in your inbox.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (isset($pager)) : ?>
            <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest">
                <?= $pager->links('default', 'tailwind_pagination') ?>
            </div>
        <?php endif ?>
    </div>
</div>

<script>
    function updateInquiryStatus(id, newStatus) {
        const alertBox = document.getElementById('ajaxAlert');
        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');
        
        fetch('<?= base_url('admin/inquiries/update-status/') ?>' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfName]: csrfHash
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            alertBox.classList.remove('hidden', 'bg-error-container', 'text-on-error-container', 'bg-[#d3e3fd]', 'text-[#041e49]');
            if(data.status === 'success') {
                alertBox.classList.add('bg-[#d3e3fd]', 'text-[#041e49]');
                alertBox.innerHTML = `<span class="material-symbols-outlined mt-0.5">check_circle</span> Inquiry status updated.`;
            } else {
                alertBox.classList.add('bg-error-container', 'text-on-error-container');
                alertBox.innerHTML = `<span class="material-symbols-outlined mt-0.5">warning</span> Failed to update inquiry.`;
            }
            setTimeout(() => { alertBox.classList.add('hidden'); }, 3000);
        })
        .catch(err => console.error('Error:', err));
    }
</script>
<?= $this->endSection() ?>