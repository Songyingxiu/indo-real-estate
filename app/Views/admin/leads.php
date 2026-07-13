<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<?php
// Group leads into Kanban columns
$colNew = []; $colContacted = []; $colQualified = []; $colNegotiation = []; $colClosed = [];
if (!empty($leads)) {
    foreach ($leads as $lead) {
        if ($lead->lead_status == 'New') $colNew[] = $lead;
        elseif ($lead->lead_status == 'Contacted') $colContacted[] = $lead;
        elseif (in_array($lead->lead_status, ['Follow Up', 'Qualified'])) $colQualified[] = $lead;
        elseif ($lead->lead_status == 'Negotiation') $colNegotiation[] = $lead;
        else $colClosed[] = $lead;
    }
}
?>

<div class="pb-12 max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8" x-data="{ 
    showStatusModal: false, editId: '', currentStatus: '', buyerName: '',
    showDeleteModal: false, deleteUrl: '',
    showModal: false, modalName: '', modalEmail: '', modalPhone: '', modalMessage: '', modalProp: '', modalDate: '',
    
    // Live WhatsApp Link Generator (Formats 08xxx to 628xxx dynamically)
    get waLink() {
        let phone = this.modalPhone.replace(/\D/g, '');
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        }
        let messageText = 'Halo ' + this.modalName + ', saya agen dari HuniKita. Menanggapi ketertarikan Anda pada properti *' + this.modalProp + '*, ';
        return 'https://wa.me/' + phone + '?text=' + encodeURIComponent(messageText);
    },
    
    // Live Email Mailto Link Generator
    get emailLink() {
        return 'mailto:' + this.modalEmail + '?subject=' + encodeURIComponent('Tanggapan Inkuiri Properti HuniKita: ' + this.modalProp);
    }
}">

    <div class="mt-4 mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Active Leads Pipeline</h1>
        <p class="text-on-surface-variant">Manage and track your incoming buyer inquiries via the Kanban board.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded mb-6 border flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm overflow-hidden">
        <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar min-h-[500px]">
            
            <!-- NEW COLUMN -->
            <div class="flex-shrink-0 w-72 bg-surface-container-low rounded-lg p-4 flex flex-col h-[600px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-label-md text-[14px] font-bold text-on-surface-variant">New</h3>
                    <span class="bg-surface-container-highest text-on-surface-variant text-xs px-2 py-1 rounded-full font-bold"><?= count($colNew) ?></span>
                </div>
                <div class="space-y-3 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <?php if(!empty($colNew)): foreach($colNew as $lead): ?>
                        <div class="bg-surface border border-outline-variant rounded p-3 shadow-sm hover:shadow-md transition-shadow <?= isset($lead->is_read) && $lead->is_read == 0 ? 'border-primary border-l-4' : '' ?>">
                            <div class="font-label-md text-[14px] font-bold text-on-background mb-1">
                                <?= esc($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last)) ?: 'Guest' ?>
                                <?php if(isset($lead->is_read) && $lead->is_read == 0): ?><span class="w-2 h-2 rounded-full bg-error inline-block animate-pulse ml-1" title="Unread"></span><?php endif; ?>
                            </div>
                            <div class="font-caption text-[12px] text-on-surface-variant mb-2 line-clamp-1">Inquiry: <?= esc($lead->property_title) ?></div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-surface-variant">
                                <span class="text-[10px] text-outline font-semibold bg-primary-container text-white px-2 py-0.5 rounded"><?= esc($lead->lead_status) ?></span>
                                <div class="flex gap-1">
                                    <button type="button" @click="showModal = true; modalName = '<?= esc(addslashes($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last))) ?>'; modalEmail = '<?= esc(addslashes($lead->email ?? $lead->buyer_email)) ?>'; modalPhone = '<?= esc(addslashes($lead->phone ?? '')) ?>'; modalMessage = '<?= esc(addslashes($lead->message ?? 'No message provided.')) ?>'; modalProp = '<?= esc(addslashes($lead->property_title ?? 'Unknown Property')) ?>'; modalDate = '<?= date('M d, Y h:i A', strtotime($lead->created_date)) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">mail</button>
                                    <?php if(in_array(session()->get('role_id'), [3, 4])): ?>
                                        <button @click="showStatusModal = true; editId = <?= $lead->id ?>; currentStatus = '<?= esc($lead->lead_status) ?>'; buyerName = '<?= esc(addslashes(($lead->buyer_first ?? '') . ' ' . ($lead->buyer_last ?? 'Guest'))) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">rule</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="flex items-center justify-center h-full border-2 border-dashed border-outline-variant/50 rounded bg-surface/50">
                            <span class="font-caption text-[12px] text-outline font-semibold">No new leads</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CONTACTED COLUMN -->
            <div class="flex-shrink-0 w-72 bg-surface-container-low rounded-lg p-4 flex flex-col h-[600px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-label-md text-[14px] font-bold text-on-surface-variant">Contacted</h3>
                    <span class="bg-surface-container-highest text-on-surface-variant text-xs px-2 py-1 rounded-full font-bold"><?= count($colContacted) ?></span>
                </div>
                <div class="space-y-3 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <?php if(!empty($colContacted)): foreach($colContacted as $lead): ?>
                        <div class="bg-surface border border-outline-variant rounded p-3 shadow-sm hover:shadow-md transition-shadow">
                            <div class="font-label-md text-[14px] font-bold text-on-background mb-1"><?= esc($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last)) ?: 'Guest' ?></div>
                            <div class="font-caption text-[12px] text-on-surface-variant mb-2 line-clamp-1">Inquiry: <?= esc($lead->property_title) ?></div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-surface-variant">
                                <span class="text-[10px] text-outline font-semibold bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded"><?= esc($lead->lead_status) ?></span>
                                <div class="flex gap-1">
                                    <button type="button" @click="showModal = true; modalName = '<?= esc(addslashes($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last))) ?>'; modalEmail = '<?= esc(addslashes($lead->email ?? $lead->buyer_email)) ?>'; modalPhone = '<?= esc(addslashes($lead->phone ?? '')) ?>'; modalMessage = '<?= esc(addslashes($lead->message ?? 'No message provided.')) ?>'; modalProp = '<?= esc(addslashes($lead->property_title ?? 'Unknown Property')) ?>'; modalDate = '<?= date('M d, Y h:i A', strtotime($lead->created_date)) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">mail</button>
                                    <?php if(in_array(session()->get('role_id'), [3, 4])): ?>
                                        <button @click="showStatusModal = true; editId = <?= $lead->id ?>; currentStatus = '<?= esc($lead->lead_status) ?>'; buyerName = '<?= esc(addslashes(($lead->buyer_first ?? '') . ' ' . ($lead->buyer_last ?? 'Guest'))) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">rule</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="flex items-center justify-center h-full border-2 border-dashed border-outline-variant/50 rounded bg-surface/50">
                            <span class="font-caption text-[12px] text-outline font-semibold">Drop leads here</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- QUALIFIED COLUMN -->
            <div class="flex-shrink-0 w-72 bg-surface-container-low rounded-lg p-4 flex flex-col h-[600px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-label-md text-[14px] font-bold text-on-surface-variant">Qualified / Follow Up</h3>
                    <span class="bg-surface-container-highest text-on-surface-variant text-xs px-2 py-1 rounded-full font-bold"><?= count($colQualified) ?></span>
                </div>
                <div class="space-y-3 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <?php if(!empty($colQualified)): foreach($colQualified as $lead): ?>
                        <div class="bg-surface border border-outline-variant rounded p-3 shadow-sm hover:shadow-md transition-shadow">
                            <div class="font-label-md text-[14px] font-bold text-on-background mb-1"><?= esc($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last)) ?: 'Guest' ?></div>
                            <div class="font-caption text-[12px] text-on-surface-variant mb-2 line-clamp-1">Inquiry: <?= esc($lead->property_title) ?></div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-surface-variant">
                                <span class="text-[10px] text-outline font-semibold bg-[#fef7e0] text-[#31302b] border border-[#eaddb9] px-2 py-0.5 rounded"><?= esc($lead->lead_status) ?></span>
                                <div class="flex gap-1">
                                    <button type="button" @click="showModal = true; modalName = '<?= esc(addslashes($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last))) ?>'; modalEmail = '<?= esc(addslashes($lead->email ?? $lead->buyer_email)) ?>'; modalPhone = '<?= esc(addslashes($lead->phone ?? '')) ?>'; modalMessage = '<?= esc(addslashes($lead->message ?? 'No message provided.')) ?>'; modalProp = '<?= esc(addslashes($lead->property_title ?? 'Unknown Property')) ?>'; modalDate = '<?= date('M d, Y h:i A', strtotime($lead->created_date)) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">mail</button>
                                    <?php if(in_array(session()->get('role_id'), [3, 4])): ?>
                                        <button @click="showStatusModal = true; editId = <?= $lead->id ?>; currentStatus = '<?= esc($lead->lead_status) ?>'; buyerName = '<?= esc(addslashes(($lead->buyer_first ?? '') . ' ' . ($lead->buyer_last ?? 'Guest'))) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">rule</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="flex items-center justify-center h-full border-2 border-dashed border-outline-variant/50 rounded bg-surface/50">
                            <span class="font-caption text-[12px] text-outline font-semibold">Drop leads here</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- NEGOTIATION COLUMN -->
            <div class="flex-shrink-0 w-72 bg-surface-container-low rounded-lg p-4 flex flex-col h-[600px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-label-md text-[14px] font-bold text-on-surface-variant">Negotiation</h3>
                    <span class="bg-surface-container-highest text-on-surface-variant text-xs px-2 py-1 rounded-full font-bold"><?= count($colNegotiation) ?></span>
                </div>
                <div class="space-y-3 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <?php if(!empty($colNegotiation)): foreach($colNegotiation as $lead): ?>
                        <div class="bg-surface border border-outline-variant rounded p-3 shadow-sm hover:shadow-md transition-shadow">
                            <div class="font-label-md text-[14px] font-bold text-on-background mb-1"><?= esc($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last)) ?: 'Guest' ?></div>
                            <div class="font-caption text-[12px] text-on-surface-variant mb-2 line-clamp-1">Inquiry: <?= esc($lead->property_title) ?></div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-surface-variant">
                                <span class="text-[10px] text-outline font-semibold bg-surface-variant text-on-surface px-2 py-0.5 rounded"><?= esc($lead->lead_status) ?></span>
                                <div class="flex gap-1">
                                    <button type="button" @click="showModal = true; modalName = '<?= esc(addslashes($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last))) ?>'; modalEmail = '<?= esc(addslashes($lead->email ?? $lead->buyer_email)) ?>'; modalPhone = '<?= esc(addslashes($lead->phone ?? '')) ?>'; modalMessage = '<?= esc(addslashes($lead->message ?? 'No message provided.')) ?>'; modalProp = '<?= esc(addslashes($lead->property_title ?? 'Unknown Property')) ?>'; modalDate = '<?= date('M d, Y h:i A', strtotime($lead->created_date)) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">mail</button>
                                    <?php if(in_array(session()->get('role_id'), [3, 4])): ?>
                                        <button @click="showStatusModal = true; editId = <?= $lead->id ?>; currentStatus = '<?= esc($lead->lead_status) ?>'; buyerName = '<?= esc(addslashes(($lead->buyer_first ?? '') . ' ' . ($lead->buyer_last ?? 'Guest'))) ?>';" class="material-symbols-outlined text-[18px] text-outline hover:text-primary transition-colors">rule</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="flex items-center justify-center h-full border-2 border-dashed border-outline-variant/50 rounded bg-surface/50">
                            <span class="font-caption text-[12px] text-outline font-semibold">Drop leads here</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        
        <?php if ($pager) : ?>
            <div class="mt-4 pt-4 border-t border-outline-variant">
                <?= $pager->links('leads', 'tailwind_pagination') ?>
            </div>
        <?php endif ?>
    </div>

    <!-- INQUIRY DETAILS ACTIONABLE MODAL -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showModal = false" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface flex items-center gap-2"><span class="material-symbols-outlined">mail</span> Inquiry Details</h2>
                <button type="button" @click="showModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar">
                <div class="flex justify-between items-start mb-6 border-b border-outline-variant pb-4">
                    <div>
                        <p class="font-bold text-lg text-on-surface" x-text="modalName"></p>
                        <p class="text-sm text-on-surface-variant flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">mail</span> <span x-text="modalEmail"></span></p>
                        <p class="text-sm text-on-surface-variant flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">call</span> <span x-text="modalPhone"></span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-on-surface-variant mb-1" x-text="modalDate"></p>
                        <span class="inline-block bg-primary-fixed text-on-primary-fixed text-xs font-bold px-2 py-1 rounded">Via Website</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm font-bold text-on-surface mb-2 uppercase tracking-wide">Property of Interest</p>
                    <div class="bg-surface-container-low p-3 rounded border border-outline-variant text-primary font-medium" x-text="modalProp"></div>
                </div>

                <div>
                    <p class="text-sm font-bold text-on-surface mb-2 uppercase tracking-wide">Message</p>
                    <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant text-on-surface whitespace-pre-wrap leading-relaxed" x-text="modalMessage"></div>
                </div>
            </div>
            
            <!-- FOOTER WITH COMMUNICATE ACTION BUTTONS -->
            <div class="px-6 py-4 border-t border-outline-variant flex flex-col sm:flex-row justify-between items-center gap-3 bg-surface-container-lowest">
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <!-- Dynamic WhatsApp Link -->
                    <a :href="waLink" target="_blank" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#25D366] text-white rounded font-semibold hover:opacity-90 transition-all text-sm shadow-sm">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-11.09c.307-.074.524-.303.659-.541.21-.37.408-.752.597-1.137.078-.16.035-.306-.02-.45-.094-.246-.192-.489-.286-.735-.146-.382-.289-.766-.435-1.149-.115-.303-.357-.492-.684-.504-.26-.01-.522-.005-.783-.005-.319.001-.572.138-.724.417-.26.478-.492.97-.704 1.472-.375.89-.582 1.83-.586 2.787-.009 2.192.983 4.157 2.457 5.688.196.203.418.388.643.562 1.905 1.481 4.192 2.193 6.587 2.106 1.009-.036 1.97-.272 2.883-.703.541-.256.974-.636 1.229-1.173.308-.648.56-1.325.748-2.022.069-.257-.038-.475-.25-.611-.456-.293-.923-.571-1.396-.84-.265-.15-.515-.126-.74.075-.245.22-.475.457-.71.688-.232.228-.487.279-.781.145-.522-.239-1.007-.542-1.458-.897-.56-.441-1.048-.948-1.47-1.523-.192-.26-.178-.508.043-.746.223-.241.457-.472.684-.71.246-.26.246-.531.026-.807z"/>
                        </svg>
                        WhatsApp
                    </a>
                    
                    <!-- Dynamic Mailto Link -->
                    <a :href="emailLink" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface rounded font-semibold border border-outline-variant hover:bg-surface-container-highest transition-all text-sm shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">mail</span>
                        Email
                    </a>
                </div>
                <button type="button" @click="showModal = false" class="w-full sm:w-auto px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition-all text-sm">Close</button>
            </div>
        </div>
    </div>

    <!-- UPDATE STATUS MODAL -->
    <div x-show="showStatusModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showStatusModal = false" x-show="showStatusModal" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Update Lead Status</h2>
                <button type="button" @click="showStatusModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/leads/update-status/') ?>' + editId" method="POST">
                <div class="p-6">
                    <p class="text-sm text-on-surface-variant mb-4">Updating inquiry from <span class="font-bold text-on-surface" x-text="buyerName"></span>.</p>
                    <label class="block text-sm font-semibold text-on-surface mb-2">Current Status</label>
                    <select name="lead_status" x-model="currentStatus" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none cursor-pointer">
                        <option value="New">New</option>
                        <option value="Contacted">Contacted</option>
                        <option value="Follow Up">Follow Up</option>
                        <option value="Qualified">Qualified</option>
                        <option value="Negotiation">Negotiation</option>
                        <option value="Won">Won</option>
                        <option value="Lost">Lost</option>
                    </select>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showStatusModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>