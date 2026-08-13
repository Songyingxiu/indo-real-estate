<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Verification Center</h1>
    <p class="text-on-surface-variant">Review pending Agent identities and Property ownership certificates.</p>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div x-data="{ activeTab: 'agents', showModal: false, docName: '', submitter: '', verificationId: 0, docUrl: '', processUrl: '' }" class="pb-12">
    
    <div class="flex gap-4 mb-6 border-b border-outline-variant pb-2">
        <button @click="activeTab = 'agents'" :class="activeTab === 'agents' ? 'text-primary border-b-2 border-primary font-bold' : 'text-on-surface-variant hover:text-primary'" class="pb-2 px-4 transition-colors font-label-md text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">badge</span> Agent Identities
        </button>
        <button @click="activeTab = 'properties'" :class="activeTab === 'properties' ? 'text-primary border-b-2 border-primary font-bold' : 'text-on-surface-variant hover:text-primary'" class="pb-2 px-4 transition-colors font-label-md text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">description</span> Property Documents
        </button>
    </div>

    <div class="bg-surface border border-outline-variant rounded-lg overflow-x-auto">
        
        <table x-show="activeTab === 'agents'" class="w-full text-left border-collapse">
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
                <?php if (!empty($agent_verifications)): ?>
                    <?php foreach ($agent_verifications as $v): $row = (object) $v; ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                            <td class="p-4 flex items-center gap-3">
                                <div class="bg-surface-container p-2 rounded border border-outline-variant">
                                    <span class="material-symbols-outlined text-outline-variant text-[20px]">badge</span>
                                </div>
                                <span class="font-medium">KTP / ID Card</span>
                            </td>
                            <td class="p-4 font-semibold text-primary"><?= esc(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?></td>
                            <td class="p-4 text-on-surface-variant whitespace-nowrap"><?= date('M d, Y', strtotime($row->created_at ?? $row->created_date ?? 'now')) ?></td>
                            <td class="p-4">
                                <span class="bg-[#fef7e0] text-[#b06000] px-3 py-1 rounded-full text-xs font-semibold"><?= esc($row->approval_status ?: 'Pending') ?></span>
                            </td>
                            <td class="p-4 text-right">
                                <button @click="showModal = true; 
                                                docName = 'Agent KTP (ID Card)'; 
                                                submitter = '<?= esc(addslashes(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''))) ?>';
                                                verificationId = <?= $row->id ?? 0 ?>;
                                                processUrl = '<?= base_url('admin/verifications/process-agent/') ?>' + verificationId;
                                                docUrl = '<?= strpos((string)($row->ktp_document ?? ''), 'http') === 0 ? esc($row->ktp_document) : base_url('uploads/documents/' . ($row->ktp_document ?? '')) ?>';" 
                                        class="border border-outline-variant text-on-surface px-4 py-1.5 rounded font-semibold hover:bg-surface-container transition whitespace-nowrap">
                                    View File
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-8 text-center text-on-surface-variant italic">
                            <span class="material-symbols-outlined text-[48px] opacity-50 mb-2">task</span>
                            <p>No Agent documents pending verification.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <table x-show="activeTab === 'properties'" style="display: none;" class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
                <tr>
                    <th class="p-4 font-semibold text-on-surface-variant">Document Type</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Property & Submitter</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Date Submitted</th>
                    <th class="p-4 font-semibold text-on-surface-variant">Status</th>
                    <th class="p-4 font-semibold text-on-surface-variant text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-on-surface">
                <?php if (!empty($prop_verifications)): ?>
                    <?php foreach ($prop_verifications as $v): $row = (object) $v; ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                            <td class="p-4 flex items-center gap-3">
                                <div class="bg-surface-container p-2 rounded border border-outline-variant">
                                    <span class="material-symbols-outlined text-outline-variant text-[20px]">description</span>
                                </div>
                                <span class="font-medium">Land Certificate (SHM)</span>
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-primary mb-1"><?= esc($row->property_title ?? 'Unknown Property') ?></p>
                                <p class="text-xs text-on-surface-variant">Owner: <?= esc(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?></p>
                            </td>
                            <td class="p-4 text-on-surface-variant whitespace-nowrap"><?= date('M d, Y', strtotime($row->created_at ?? $row->created_date ?? 'now')) ?></td>
                            <td class="p-4">
                                <span class="bg-[#fef7e0] text-[#b06000] px-3 py-1 rounded-full text-xs font-semibold"><?= esc($row->approval_status ?: 'Pending') ?></span>
                            </td>
                            <td class="p-4 text-right">
                                <button @click="showModal = true; 
                                                docName = 'Property Ownership Certificate (SHM)'; 
                                                submitter = '<?= esc(addslashes(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''))) ?>';
                                                verificationId = <?= $row->id ?? 0 ?>;
                                                processUrl = '<?= base_url('admin/verifications/process-property/') ?>' + verificationId;
                                                docUrl = '<?= strpos((string)($row->ownership_certificate ?? ''), 'http') === 0 ? esc($row->ownership_certificate) : base_url('uploads/documents/' . ($row->ownership_certificate ?? '')) ?>';" 
                                        class="border border-outline-variant text-on-surface px-4 py-1.5 rounded font-semibold hover:bg-surface-container transition whitespace-nowrap">
                                    View File
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-8 text-center text-on-surface-variant italic">
                            <span class="material-symbols-outlined text-[48px] opacity-50 mb-2">task</span>
                            <p>No Property documents pending verification.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showModal = false" x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div>
                    <h2 class="text-xl font-bold text-on-surface" x-text="docName"></h2>
                    <p class="text-sm text-on-surface-variant">Submitted by <span class="font-semibold" x-text="submitter"></span></p>
                </div>
                <button @click="showModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 bg-surface-container-low flex flex-col justify-center items-center min-h-[300px] max-h-[60vh] overflow-y-auto">
                
                <!-- PDF Renderer -->
                <template x-if="docUrl && docUrl.toLowerCase().includes('.pdf')">
                    <iframe :src="docUrl" class="w-full h-[50vh] rounded border border-outline-variant shadow-sm bg-white"></iframe>
                </template>
                
                <!-- Image Renderer -->
                <template x-if="docUrl && !docUrl.toLowerCase().includes('.pdf') && !docUrl.endsWith('/')">
                    <div class="flex flex-col items-center w-full">
                        <img :src="docUrl" alt="Document Preview" class="max-w-full max-h-[45vh] object-contain rounded border border-outline-variant shadow-sm" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                        <div class="hidden text-center text-outline-variant py-8">
                            <span class="material-symbols-outlined text-[48px] mb-2">broken_image</span>
                            <p>Preview unavailable. The file might be corrupted.</p>
                        </div>
                    </div>
                </template>
                
                <!-- Missing File Fallback -->
                <template x-if="!docUrl || docUrl.endsWith('/')">
                    <div class="flex flex-col items-center text-outline-variant">
                        <span class="material-symbols-outlined text-[48px] mb-2">image_not_supported</span>
                        <p class="text-sm">No valid document file uploaded.</p>
                    </div>
                </template>

                <!-- Direct Link Button (Always visible if a URL exists) -->
                <template x-if="docUrl && !docUrl.endsWith('/')">
                    <a :href="docUrl" target="_blank" class="mt-6 flex items-center gap-2 px-4 py-2 border border-outline-variant bg-surface text-primary rounded font-semibold hover:bg-surface-bright transition shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        Open Document in New Tab
                    </a>
                </template>
            </div>

            <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                <form :action="processUrl" method="POST">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="px-6 py-2 border border-error text-error rounded font-semibold hover:bg-error-container transition" onsubmit="return confirm('Reject this document?');">Reject</button>
                </form>
                
                <form :action="processUrl" method="POST">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Approve Document</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>