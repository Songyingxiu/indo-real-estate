<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="pb-12" x-data="{ 
    showStatusModal: false, editId: '', currentStatus: '', buyerName: '',
    showDeleteModal: false, deleteUrl: ''
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
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                            <td class="p-4 text-on-surface-variant whitespace-nowrap">
                                <?= date('M d, Y', strtotime($lead->created_date)) ?>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-on-surface"><?= esc(($lead->buyer_first ?? '') . ' ' . ($lead->buyer_last ?? 'Guest')) ?></div>
                                <div class="text-xs text-on-surface-variant"><?= esc($lead->buyer_email ?? 'No email provided') ?></div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-on-surface"><?= esc($lead->property_title ?? 'Unknown Property') ?></div>
                                <div class="text-xs text-on-surface-variant capitalize">Source: <?= esc($lead->source ?? 'Direct') ?></div>
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
                <?= $pager->links('leads', 'tailwind') ?>
            <?php endif ?>

        </div>
    <?php endif; ?>

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
                        <!-- Options mapped strictly to database ENUM -->
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