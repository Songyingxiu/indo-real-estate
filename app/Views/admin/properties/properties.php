<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="font-headline-lg text-[28px] font-bold text-on-surface">Property Listings</h2>
            <p class="text-on-surface-variant">Manage and view your real estate portfolio.</p>
        </div>
        <a href="<?= base_url('admin/properties/create') ?>" class="bg-primary-container text-on-primary px-6 py-2.5 rounded font-label-md font-semibold hover:bg-primary transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Add Property
        </a>
    </div>

    <!-- Alert Placeholders for AJAX -->
    <div id="ajaxAlert" class="hidden p-4 rounded mb-6 border flex items-center gap-2 transition-all"></div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined mt-0.5">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded mb-6 border flex items-center gap-2">
            <span class="material-symbols-outlined mt-0.5">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant font-label-md text-[14px]">
                    <th class="py-4 px-6 font-semibold">Property Title</th>
                    <th class="py-4 px-6 font-semibold">Type</th>
                    <th class="py-4 px-6 font-semibold">Price (IDR)</th>
                    <th class="py-4 px-6 font-semibold">Property Status</th>
                    <th class="py-4 px-6 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-[14px] text-on-surface">
                <?php if (!empty($properties) && is_array($properties)): ?>
                    <?php foreach ($properties as $property): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-container-low/50">
                            <td class="py-4 px-6 font-semibold text-primary">
                                <?= esc($property['title']) ?><br>
                                <span class="text-xs font-normal text-on-surface-variant">Approval: <?= esc($property['approval_status']) ?></span><br>
                                
                                <?php 
                                    $dStatus = $property['doc_status'] ?? 'Not Submitted';
                                    $dColor = $dStatus === 'Verified' ? 'text-[#0d652d]' : 'text-error';
                                ?>
                                <span class="text-xs font-normal <?= $dColor ?>">Doc Status: <?= esc($dStatus) ?></span>
                            </td>
                            <td class="py-4 px-6"><span class="px-3 py-1 rounded-full bg-surface-container"><?= esc($property['listing_type']) ?></span></td>
                            <td class="py-4 px-6">Rp <?= number_format($property['tax_price'], 0, ',', '.') ?></td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <select onchange="updatePropertyStatus(<?= $property['id'] ?>, this.value)" class="px-3 py-1.5 rounded bg-surface border border-outline-variant text-[14px] font-semibold cursor-pointer focus:ring-1 focus:ring-primary outline-none">
                                    <option value="Draft" <?= $property['status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="Pending Approval" <?= $property['status'] == 'Pending Approval' ? 'selected' : '' ?>>Pending Approval</option>
                                    
                                    <option value="Active" <?= $property['status'] == 'Active' ? 'selected' : '' ?> <?= ($property['doc_status'] ?? '') !== 'Verified' ? 'disabled' : '' ?>>
                                        Active <?= ($property['doc_status'] ?? '') !== 'Verified' ? '(Doc Required)' : '' ?>
                                    </option>
                                    
                                    <option value="Sold" <?= $property['status'] == 'Sold' ? 'selected' : '' ?>>Sold</option>
                                    <option value="Expired" <?= $property['status'] == 'Expired' ? 'selected' : '' ?>>Expired</option>
                                    <option value="Inactive" <?= $property['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </td>
                            <td class="py-4 px-6">
                                <a href="<?= base_url('admin/properties/edit/' . $property['id']) ?>" class="text-primary hover:text-primary-container transition p-1 inline-block" title="Edit Property">
                                    <span class="material-symbols-outlined text-[20px] align-middle">edit</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="py-8 text-center">No properties found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function updatePropertyStatus(id, newStatus) {
        const alertBox = document.getElementById('ajaxAlert');
        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');
        
        fetch('<?= base_url('admin/properties/update-status/') ?>' + id, {
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
                alertBox.innerHTML = `<span class="material-symbols-outlined mt-0.5">check_circle</span> ${data.message}`;
            } else {
                alertBox.classList.add('bg-error-container', 'text-on-error-container');
                alertBox.innerHTML = `<span class="material-symbols-outlined mt-0.5">warning</span> ${data.message}`;
                
                setTimeout(() => { window.location.reload(); }, 1500);
            }
            setTimeout(() => { alertBox.classList.add('hidden'); }, 4000);
        })
        .catch(err => console.error('Error:', err));
    }
</script>
<?= $this->endSection() ?>