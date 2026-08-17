<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Moderation & State Machine</h1>
    <p class="text-on-surface-variant">Review listings and manage the complete property workflow lifecycle.</p>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="bg-error-container text-on-error-container p-4 rounded mb-6 border border-error flex items-center gap-2">
        <span class="material-symbols-outlined">cancel</span>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-surface border border-outline-variant rounded-lg overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
                <tr>
                    <th class="p-4 font-semibold text-on-surface-variant">Property Details</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Submitted By</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Price</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Doc Status</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Current State</th>
                    <th class="p-4 font-semibold text-on-surface-variant text-right">Workflow Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if (!empty($properties)): ?>
                    <?php foreach ($properties as $prop): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                            <td class="p-4">
                                <div class="font-semibold text-on-surface text-[15px]"><?= esc($prop['title'] ?? 'Untitled Property') ?></div>
                                <div class="text-xs text-on-surface-variant mt-0.5">Property ID: #<?= esc($prop['id']) ?></div>
                            </td>
                            <td class="p-4 text-on-surface-variant">
                                <?= esc(($prop['first_name'] ?? '') . ' ' . ($prop['last_name'] ?? '')) ?>
                            </td>
                            <td class="p-4 text-on-surface font-medium">
                                Rp <?= number_format($prop['tax_price'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $dStatus = $prop['doc_status'] ?? 'Not Submitted';
                                    $dBadge = 'bg-surface-container-high text-on-surface-variant border border-outline-variant';
                                    if ($dStatus == 'Verified') $dBadge = 'bg-[#c4eed0] text-[#0d652d]';
                                    elseif (in_array($dStatus, ['Pending Verification', 'Pending', 'Under Review'])) $dBadge = 'bg-[#fef7e0] text-[#b06000]';
                                    elseif ($dStatus == 'Rejected') $dBadge = 'bg-error-container text-on-error-container';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap <?= $dBadge ?>"><?= esc($dStatus) ?></span>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $badge = 'bg-surface-container text-on-surface';
                                    if ($prop['approval_status'] == 'Published') $badge = 'bg-[#c4eed0] text-[#0d652d]';
                                    if ($prop['approval_status'] == 'Approved') $badge = 'bg-[#d3e3fd] text-[#001d35]';
                                    if ($prop['approval_status'] == 'Pending Review') $badge = 'bg-[#fef7e0] text-[#b06000]';
                                    if ($prop['approval_status'] == 'Rejected') $badge = 'bg-error-container text-on-error-container';
                                    if ($prop['approval_status'] == 'Draft' || $prop['approval_status'] == 'Archived' || $prop['approval_status'] == 'Expired') $badge = 'bg-surface-container-high text-on-surface-variant border border-outline-variant';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $badge ?>"><?= esc($prop['approval_status']) ?></span>
                            </td>
                            <td class="p-4 text-right flex justify-end gap-2">
                                
                                <form action="<?= base_url('admin/moderation/update-status/' . $prop['id']) ?>" method="POST" class="flex items-center gap-2">
                                    <select name="approval_status" class="border border-outline-variant bg-surface rounded px-2 py-1.5 text-sm outline-none focus:border-primary">
                                        <option value="Draft" <?= $prop['approval_status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="Pending Review" <?= $prop['approval_status'] == 'Pending Review' ? 'selected' : '' ?>>Pending Review</option>
                                        <option value="Approved" <?= $prop['approval_status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                        
                                        <option value="Published" <?= $prop['approval_status'] == 'Published' ? 'selected' : '' ?> <?= ($prop['doc_status'] ?? '') !== 'Verified' ? 'disabled' : '' ?>>
                                            Published <?= ($prop['doc_status'] ?? '') !== 'Verified' ? '(Doc Required)' : '' ?>
                                        </option>
                                        
                                        <option value="Rejected" <?= $prop['approval_status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                        <option value="Expired" <?= $prop['approval_status'] == 'Expired' ? 'selected' : '' ?>>Expired</option>
                                        <option value="Archived" <?= $prop['approval_status'] == 'Archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                    
                                    <button type="submit" class="bg-primary text-on-primary px-4 py-1.5 rounded font-semibold hover:bg-primary-container transition-colors">
                                        Update
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[48px] opacity-50 mb-2">inbox</span>
                            <p>No properties exist in the workflow.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>