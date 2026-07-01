<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto mt-4 pb-12" x-data="{ 
    showDeleteModal: false, deleteUrl: '', deleteMessage: '',
    showEditTypeModal: false, editTypeId: '', editTypeName: '',
    showEditStateModal: false, editStateId: '', editStateName: '',
    showEditCityModal: false, editCityId: '', editCityName: '', editCityStateId: '',
    showCreatePlanModal: false, showEditPlanModal: false,
    editPlanId: '', editPlanCode: '', editPlanName: '', editPlanDesc: '', editPlanPrice: 0, editPlanProp: 1, editPlanAgent: 0, editPlanMsg: 0, editPlanEmail: 0
}">
    <div class="mb-stack-lg mt-4 flex justify-between items-end">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-primary mb-stack-sm">Master Data Configuration</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Manage core system taxonomies, locations, and packages.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-8 mt-4">
        
        <!-- Subscription Packages -->
        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg flex justify-between items-center">
                <h2 class="font-label-md text-label-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">workspace_premium</span> Subscription Packages
                </h2>
                <button @click="showCreatePlanModal = true" class="px-4 py-2 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">add</span> Create Package
                </button>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Code</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Package Name</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Price</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant">Limits (Props/Agents)</th>
                            <th class="py-3 px-6 font-label-md text-caption text-on-surface-variant text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($plans)): foreach($plans as $plan): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                <td class="py-3 px-6 font-label-md font-bold text-primary"><?= esc($plan->code ?? 'N/A') ?></td>
                                <td class="py-3 px-6 font-body-md text-on-surface">
                                    <span class="font-semibold block"><?= esc($plan->name) ?></span>
                                    <span class="text-[11px] text-on-surface-variant truncate max-w-[200px] block"><?= esc($plan->description ?? 'No description') ?></span>
                                </td>
                                <td class="py-3 px-6 font-body-md text-on-surface">Rp <?= number_format($plan->price, 0, ',', '.') ?></td>
                                <td class="py-3 px-6 text-on-surface-variant text-sm">
                                    <span class="bg-surface-container-high px-2 py-1 rounded mr-1"><?= esc($plan->max_properties ?? 1) ?> Props</span>
                                    <span class="bg-surface-container-high px-2 py-1 rounded"><?= esc($plan->max_agents ?? 0) ?> Sub-Agents</span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" @click="showEditPlanModal = true; editPlanId = <?= $plan->id ?>; editPlanCode = '<?= esc(addslashes($plan->code ?? '')) ?>'; editPlanName = '<?= esc(addslashes($plan->name)) ?>'; editPlanDesc = '<?= esc(addslashes($plan->description ?? '')) ?>'; editPlanPrice = <?= $plan->price ?>; editPlanProp = <?= $plan->max_properties ?? 1 ?>; editPlanAgent = <?= $plan->max_agents ?? 0 ?>; editPlanMsg = <?= $plan->allow_messages ?? 0 ?>; editPlanEmail = <?= $plan->direct_email_inquiry ?? 0 ?>;" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-plan/' . $plan->id) ?>'; deleteMessage = 'Are you sure you want to permanently delete this package?';" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5" class="py-6 text-center text-outline">No subscription packages found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">category</span> Property Types
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-type') ?>" method="POST" class="flex gap-2">
                        <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add new type..." type="text"/>
                        <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1 whitespace-nowrap">
                            Add
                        </button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($propertyTypes)): foreach($propertyTypes as $type): ?>
                                <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                    <td class="py-3 px-6 font-body-md text-body-md text-on-surface"><?= esc($type->type_name ?? $type->name) ?></td>
                                    <td class="py-3 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="showEditTypeModal = true; editTypeId = <?= $type->id ?>; editTypeName = '<?= esc(addslashes($type->type_name ?? $type->name)) ?>';" class="text-on-surface-variant hover:text-primary transition-colors p-1"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                            <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-type/' . $type->id) ?>'; deleteMessage = 'Delete this property type?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-lg transition-shadow duration-200">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                    <h2 class="font-label-md text-label-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">format_list_bulleted</span> Features (Amenities)
                    </h2>
                    <form action="<?= base_url('admin/master-data/store-feature') ?>" method="POST" class="flex gap-2">
                        <input name="name" required class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 bg-surface-container-lowest" placeholder="Add feature..." type="text"/>
                        <button type="submit" class="h-10 px-6 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1 whitespace-nowrap">
                            Add
                        </button>
                    </form>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php if(!empty($features)): foreach($features as $feature): ?>
                                <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                    <td class="py-3 px-6 font-body-md text-body-md text-on-surface font-semibold"><?= esc($feature->name ?? $feature->feature_name) ?></td>
                                    <td class="py-3 px-6 text-right">
                                        <button type="button" @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/master-data/delete-feature/' . $feature->id) ?>'; deleteMessage = 'Delete this feature?';" class="text-on-surface-variant hover:text-error transition-colors p-1"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

    </div>

    <!-- CREATE PLAN MODAL -->
    <div x-show="showCreatePlanModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showCreatePlanModal = false" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Create Package</h2>
                <button type="button" @click="showCreatePlanModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="<?= base_url('admin/master-data/store-plan') ?>" method="POST" class="overflow-y-auto custom-scrollbar">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold mb-1">Unique Code</label><input type="text" name="code" placeholder="e.g. FREE" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div><label class="block text-sm font-semibold mb-1">Package Name</label><input type="text" name="name" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1">Description</label><textarea name="description" rows="2" class="w-full p-3 border border-outline-variant rounded bg-surface resize-none"></textarea></div>
                    <div><label class="block text-sm font-semibold mb-1">Price (IDR)</label><input type="number" name="price" value="0" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div><label class="block text-sm font-semibold mb-1">Max Properties Allowed</label><input type="number" name="max_properties" value="1" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div><label class="block text-sm font-semibold mb-1">Max Sub-Agents Allowed</label><input type="number" name="max_agents" value="0" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div class="flex flex-col gap-2 pt-4">
                        <label class="flex items-center gap-2"><input type="hidden" name="allow_messages" value="0"><input type="checkbox" name="allow_messages" value="1" class="rounded"> <span class="text-sm">Allow Message System</span></label>
                        <label class="flex items-center gap-2"><input type="hidden" name="direct_email_inquiry" value="0"><input type="checkbox" name="direct_email_inquiry" value="1" class="rounded"> <span class="text-sm">Allow Direct Email Inquiry</span></label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showCreatePlanModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT PLAN MODAL -->
    <div x-show="showEditPlanModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditPlanModal = false" class="bg-surface w-full max-w-2xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Package</h2>
                <button type="button" @click="showEditPlanModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/master-data/update-plan/') ?>' + editPlanId" method="POST" class="overflow-y-auto custom-scrollbar">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold mb-1">Unique Code</label><input type="text" name="code" x-model="editPlanCode" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div><label class="block text-sm font-semibold mb-1">Package Name</label><input type="text" name="name" x-model="editPlanName" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1">Description</label><textarea name="description" x-model="editPlanDesc" rows="2" class="w-full p-3 border border-outline-variant rounded bg-surface resize-none"></textarea></div>
                    <div><label class="block text-sm font-semibold mb-1">Price (IDR)</label><input type="number" name="price" x-model="editPlanPrice" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div><label class="block text-sm font-semibold mb-1">Max Properties</label><input type="number" name="max_properties" x-model="editPlanProp" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div><label class="block text-sm font-semibold mb-1">Max Sub-Agents</label><input type="number" name="max_agents" x-model="editPlanAgent" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface"></div>
                    <div class="flex flex-col gap-2 pt-4">
                        <label class="flex items-center gap-2"><input type="hidden" name="allow_messages" value="0"><input type="checkbox" name="allow_messages" value="1" :checked="editPlanMsg == 1" class="rounded"> <span class="text-sm">Allow Message System</span></label>
                        <label class="flex items-center gap-2"><input type="hidden" name="direct_email_inquiry" value="0"><input type="checkbox" name="direct_email_inquiry" value="1" :checked="editPlanEmail == 1" class="rounded"> <span class="text-sm">Allow Direct Email Inquiry</span></label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditPlanModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Update Package</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-[32px]">warning</span></div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Confirm Deletion</h2>
                <p class="text-sm text-on-surface-variant" x-text="deleteMessage"></p>
            </div>
            <div class="px-6 py-4 flex justify-between gap-3 bg-surface-container-lowest border-t border-outline-variant">
                <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2 border border-outline-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form :action="deleteUrl" method="POST" class="flex-1"><button type="submit" class="w-full px-4 py-2 bg-error text-on-error rounded font-semibold hover:opacity-90 transition">Delete</button></form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>