<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Moderation Queue</h1>
    <p class="text-on-surface-variant">Review new property listings before they are published to the public.</p>
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

<div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
            <tr>
                <th class="p-4 font-semibold text-on-surface-variant">Property Details</th>
                <th class="p-4 font-semibold text-on-surface-variant">Submitted By</th>
                <th class="p-4 font-semibold text-on-surface-variant">Price</th>
                <th class="p-4 font-semibold text-on-surface-variant text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $prop): ?>
                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                        <td class="p-4">
                            <div class="font-semibold text-on-surface"><?= esc($prop['title'] ?? 'Untitled Property') ?></div>
                            <div class="text-xs text-on-surface-variant">Property ID: #<?= esc($prop['id']) ?></div>
                        </td>
                        <td class="p-4 text-on-surface-variant">
                            <?= esc(($prop['first_name'] ?? '') . ' ' . ($prop['last_name'] ?? '')) ?>
                        </td>
                        <td class="p-4 text-on-surface font-medium">
                            Rp <?= number_format($prop['price'] ?? 0, 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-right flex justify-end gap-2">
                            
                            <form action="<?= base_url('admin/moderation/approve/' . $prop['id']) ?>" method="POST" onsubmit="return confirm('Publish this property to the live marketplace?');">
                                <button type="submit" class="bg-[#2d3142] text-white px-4 py-1.5 rounded font-semibold hover:bg-opacity-90 transition">Approve</button>
                            </form>

                            <form action="<?= base_url('admin/moderation/reject/' . $prop['id']) ?>" method="POST" onsubmit="return confirm('Reject this property listing?');">
                                <button type="submit" class="bg-error text-white px-4 py-1.5 rounded font-semibold hover:bg-opacity-90 transition">Reject</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="p-8 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[48px] opacity-50 mb-2">inbox</span>
                        <p>No properties are currently pending review.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>