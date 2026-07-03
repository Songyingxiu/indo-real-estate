<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="pb-12" x-data="{ 
    showStatusModal: false, editId: '', currentStatus: '', buyerName: '',
    showDeleteModal: false, deleteUrl: '',
    showModal: false, modalName: '', modalEmail: '', modalPhone: '', modalMessage: '', modalProp: '', modalDate: ''
}">

    <div class="mt-4 mb-6">
        <h1 class="text-2xl font-bold text-on-surface">Lead Management</h1>
        <p class="text-on-surface-variant">Track buyer inquiries and contact requests.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-error-container text-on-error-container p-4 rounded mb-6 border flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($leads)): ?>
        <div class="bg-surface border border-outline-variant rounded-lg p-12 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-3xl">record_voice_over</span>
            </div>
            <h3 class="text-lg font-semibold mb-2">No Active Leads</h3>
            <p class="text-on-surface-variant max-w-md">Your properties haven't received any inquiries yet. When buyers use the 'Contact Agent' form, they will appear here.</p>
        </div>
    <?php else: ?>
        
        <div class="bg-surface border border-outline-variant rounded-lg overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
                    <tr>
                        <th class="p-4 font-semibold text-on-surface-variant w-12"></th>
                        <th class="p-4 font-semibold text-on-surface-variant">Date</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Buyer Info</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Interested In</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Assigned Agent</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Status</th>
                        <th class="p-4 font-semibold text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php foreach ($leads as $lead): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition <?= isset($lead->is_read) && $lead->is_read == 0 ? 'bg-primary-fixed/10' : '' ?>">
                            <td class="p-4 text-center">
                                <?php if(isset($lead->is_read) && $lead->is_read == 0): ?>
                                    <span class="w-2.5 h-2.5 rounded-full bg-error inline-block animate-pulse" title="Unread Message"></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-on-surface-variant whitespace-nowrap">
                                <?= date('M d, Y', strtotime($lead->created_date)) ?>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-on-surface <?= isset($lead->is_read) && $lead->is_read == 0 ? 'font-bold' : '' ?>">
                                    <?= esc($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last)) ?: 'Guest' ?>
                                </div>
                                <div class="text-xs text-on-surface-variant"><?= esc($lead->email ?? $lead->buyer_email ?? 'No email provided') ?></div>
                                <?php if(!empty($lead->phone)): ?>
                                    <div class="text-xs text-on-surface-variant mt-0.5"><?= esc($lead->phone) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-on-surface <?= isset($lead->is_read) && $lead->is_read == 0 ? 'font-bold' : '' ?>"><?= esc($lead->property_title ?? 'Unknown Property') ?></div>
                                <div class="text-xs text-on-surface-variant capitalize mt-0.5 mb-1">Source: <?= esc($lead->source ?? 'Direct') ?></div>
                                <?php if(!empty($lead->message)): ?>
                                    <div class="text-xs text-on-surface-variant truncate max-w-[200px]"><?= esc($lead->message) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-on-surface-variant">
                                <?= esc(($lead->agent_first ?? '') . ' ' . ($lead->agent_last ?? 'Unassigned')) ?>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $statusColor = 'bg-surface-container-high text-on-surface';
                                    if ($lead->lead_status == 'New') $statusColor = 'bg-primary-container text-on-primary-container';
                                    if (in_array($lead->lead_status, ['Contacted', 'Follow Up'])) $statusColor = 'bg-secondary-container text-on-secondary-container';
                                    if (in_array($lead->lead_status, ['Qualified', 'Negotiation'])) $statusColor = 'bg-[#fef7e0] text-[#31302b] border border-[#eaddb9]';
                                    if ($lead->lead_status == 'Won') $statusColor = 'bg-[#d3e3fd] text-[#041e49] border border-[#a8c7fa]';
                                    if ($lead->lead_status == 'Lost') $statusColor = 'bg-error-container text-on-error-container';
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                                    <?= esc($lead->lead_status ?? 'New') ?>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    
                                    <button type="button" @click="showModal = true; modalName = '<?= esc(addslashes($lead->name ?? ($lead->buyer_first . ' ' . $lead->buyer_last))) ?>'; modalEmail = '<?= esc(addslashes($lead->email ?? $lead->buyer_email)) ?>'; modalPhone = '<?= esc(addslashes($lead->phone ?? '')) ?>'; modalMessage = '<?= esc(addslashes($lead->message ?? 'No message provided.')) ?>'; modalProp = '<?= esc(addslashes($lead->property_title ?? 'Unknown Property')) ?>'; modalDate = '<?= date('M d, Y h:i A', strtotime($lead->created_date)) ?>';" class="text-primary hover:text-primary-container transition p-1" title="Read Message">
                                        <span class="material-symbols-outlined text-[18px]">mail</span>
                                    </button>

                                    <?php if(isset($lead->is_read) && $lead->is_read == 0): ?>
                                        <form action="<?= base_url('admin/leads/mark-read/' . $lead->id) ?>" method="POST" class="inline">
                                            <button type="submit" class="text-primary hover:text-primary-container transition p-1" title="Mark as Read">
                                                <span class="material-symbols-outlined text-[18px]">mark_email_read</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- UPDATE STATUS: Visible only to Admins (4) and Agents (3) -->
                                    <?php if(in_array(session()->get('role_id'), [3, 4])): ?>
                                        <button @click="showStatusModal = true; editId = <?= $lead->id ?>; currentStatus = '<?= esc($lead->lead_status ?? 'New') ?>'; buyerName = '<?= esc(addslashes(($lead->buyer_first ?? '') . ' ' . ($lead->buyer_last ?? 'Guest'))) ?>';" class="text-on-surface-variant hover:text-primary transition p-1" title="Update Status">
                                            <span class="material-symbols-outlined text-[18px]">rule</span>
                                        </button>
                                    <?php endif; ?>

                                    <!-- DELETE LEAD: Visible ONLY to Admins (4) -->
                                    <?php if(session()->get('role_id') == 4): ?>
                                        <button @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/leads/delete/' . $lead->id) ?>';" class="text-on-surface-variant hover:text-error transition p-1" title="Delete Lead">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if ($pager) : ?>
                <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                    <?= $pager->links('leads', 'tailwind_pagination') ?>
                </div>
            <?php endif ?>

        </div>
    <?php endif; ?>

    <!-- READ MESSAGE MODAL -->
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
            <div class="px-6 py-4 border-t border-outline-variant flex justify-end bg-surface-container-lowest">
                <button type="button" @click="showModal = false" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition-all">Close</button>
            </div>
        </div>
    </div>

    <!-- STATUS UPDATE MODAL -->
    <div x-show="showStatusModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showStatusModal = false" x-show="showStatusModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
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

    <!-- DELETE MODAL -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">warning</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Delete Lead?</h2>
                <p class="text-sm text-on-surface-variant mb-2">Are you sure you want to permanently remove this inquiry?</p>
            </div>
            <div class="px-6 py-4 flex justify-between gap-3 bg-surface-container-lowest border-t border-outline-variant">
                <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    <button type="submit" class="w-full px-4 py-2 bg-error text-on-error rounded font-semibold hover:opacity-90 transition">Delete</button>
                </form>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>