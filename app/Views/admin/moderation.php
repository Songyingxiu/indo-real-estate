<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<!-- @author debra -->

<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Moderation Queue</h1>
    <p class="text-on-surface-variant">Review new property listings before they are published to the public.</p>
</div>

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
            <!-- Example Moderation Data -->
            <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                <td class="p-4">
                    <div class="font-semibold text-on-surface">Luxury Villa in Canggu</div>
                    <div class="text-xs text-on-surface-variant">Villa • Bali</div>
                </td>
                <td class="p-4 text-on-surface-variant">Agent: Budi Santoso</td>
                <td class="p-4 text-on-surface font-medium">Rp 4.5 Billion</td>
                <td class="p-4 text-right">
                    <button class="bg-[#2d3142] text-white px-4 py-1.5 rounded font-semibold hover:bg-opacity-90 transition mr-2">Review</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>