<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Verification Center</h1>
    <p class="text-on-surface-variant">Review pending Agent identity documents and offline payment proofs.</p>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-4 font-semibold text-sm"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div x-data="{ showModal: false, docName: '', submitter: '', verificationId: 0 }" class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
            <tr>
                <th class="p-4 font-semibold text-on-surface-variant">Document Type</th>
                <th class="p-4 font-semibold text-on-surface-variant">Submitted By</th>
                <th class="p-4 font-semibold text-on-surface-variant">Date Submitted</th>
                <th class="p-4 font-semibold text-on-surface-variant">Status</th>
                <th class="p-4 font-semibold text-on-surface-variant text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm text-on-surface">
            <?php if (!empty($verifications)): ?>
                <?php foreach ($verifications as $v): ?>
                <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                    <td class="p-4 flex items-center gap-3">
                        <div class="bg-surface-container p-2 rounded border border-outline-variant">
                            <span class="material-symbols-outlined text-outline-variant text-[20px]">badge</span>
                        </div>
                        <span class="font-medium">Agent KTP (ID Card)</span>
                    </td>
                    <td class="p-4"><?= esc($v['first_name'] . ' ' . $v['last_name']) ?></td>
                    <td class="p-4 text-on-surface-variant"><?= date('M d, Y h:i A', strtotime($v['created_date'])) ?></td>
                    <td class="p-4">
                        <span class="bg-[#fef7e0] text-[#b06000] px-3 py-1 rounded-full text-xs font-semibold"><?= esc($v['approval_status']) ?></span>
                    </td>
                    <td class="p-4 text-right">
                        <button @click="showModal = true; 
                                        docName = 'Agent KTP (ID Card)'; 
                                        submitter = '<?= esc(addslashes($v['first_name'] . ' ' . $v['last_name'])) ?>';
                                        verificationId = <?= $v['id'] ?>;" 
                                class="border border-outline-variant text-on-surface px-4 py-1.5 rounded font-semibold hover:bg-surface-container transition">
                            View File
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="p-6 text-center text-on-surface-variant italic">No documents currently pending verification.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div x-show="showModal" 
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        
        <div @click.outside="showModal = false" 
             x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div>
                    <h2 class="text-xl font-bold text-on-surface" x-text="docName"></h2>
                    <p class="text-sm text-on-surface-variant">Submitted by <span class="font-semibold" x-text="submitter"></span></p>
                </div>
                <button @click="showModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 bg-surface-container-low flex justify-center items-center min-h-[300px]">
                <div class="flex flex-col items-center text-outline">
                    <span class="material-symbols-outlined text-[64px] mb-2">image</span>
                    <p class="text-sm">Document preview will load here from database</p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                
                <form :action="'<?= base_url('admin/verifications/process/') ?>' + verificationId" method="POST">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="px-6 py-2 border border-error text-error rounded font-semibold hover:bg-error-container transition">Reject</button>
                </form>
                
                <form :action="'<?= base_url('admin/verifications/process/') ?>' + verificationId" method="POST">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Approve Document</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>