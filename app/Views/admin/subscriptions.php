<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="pb-12" x-data="{ 
    showCreateModal: false, 
    showEditModal: false, editId: '', editName: '', editPrice: 0,
    showDeleteModal: false, deleteUrl: '', deleteName: '' 
}">

    <div class="mt-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Subscription Plans</h1>
            <p class="text-on-surface-variant">Manage platform pricing tiers.</p>
        </div>
        <button @click="showCreateModal = true" class="bg-primary text-on-primary px-4 py-2 rounded font-semibold flex items-center gap-2 hover:opacity-90 transition shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Create Plan
        </button>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if(!empty($plans)): foreach($plans as $plan): ?>
            <?php $isPremium = ($plan->price > 0); ?>
            
            <div class="rounded-lg p-6 flex flex-col <?= $isPremium ? 'border-2 border-primary bg-primary-container/10' : 'border border-outline-variant bg-surface' ?>">
                
                <div class="flex justify-between items-start mb-4">
                    <span class="px-2 py-1 rounded text-xs font-bold uppercase <?= $isPremium ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface' ?>">
                        <?= esc($plan->name) ?>
                    </span>
                    
                    <div class="flex gap-1">
                        <button @click="showEditModal = true; editId = <?= $plan->id ?>; editName = '<?= esc(addslashes($plan->name)) ?>'; editPrice = <?= $plan->price ?>;" class="text-on-surface-variant hover:text-primary transition p-1" title="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                        <button @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/subscriptions/delete/' . $plan->id) ?>'; deleteName = '<?= esc(addslashes($plan->name)) ?>';" class="text-on-surface-variant hover:text-error transition p-1" title="Delete">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                </div>

                <div class="text-3xl font-bold mb-1 text-on-surface">
                    <?= $isPremium ? 'Rp ' . number_format($plan->price, 0, ',', '.') : 'Free' ?>
                    <?php if($isPremium): ?>
                        <span class="text-sm font-normal text-on-surface-variant">/mo</span>
                    <?php endif; ?>
                </div>
                
                <p class="text-sm text-on-surface-variant mb-6 flex-1">
                    <?= $isPremium ? 'Premium marketplace features and priority listings.' : 'Standard property listing access.' ?>
                </p>
                
                <button @click="showEditModal = true; editId = <?= $plan->id ?>; editName = '<?= esc(addslashes($plan->name)) ?>'; editPrice = <?= $plan->price ?>;" class="w-full font-semibold py-2 rounded transition <?= $isPremium ? 'bg-primary text-on-primary hover:opacity-90' : 'border border-primary text-primary hover:bg-surface-container-low' ?>">
                    Edit Plan Settings
                </button>
            </div>
        <?php endforeach; else: ?>
            <div class="col-span-1 md:col-span-3 text-center py-12 border border-outline-variant border-dashed rounded-lg text-on-surface-variant">
                No subscription plans found. Click "Create Plan" to get started.
            </div>
        <?php endif; ?>
    </div>

    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showCreateModal = false" x-show="showCreateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Create New Plan</h2>
                <button type="button" @click="showCreateModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="<?= base_url('admin/subscriptions/store') ?>" method="POST">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Plan Name</label>
                        <input type="text" name="name" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Price (IDR)</label>
                        <input type="number" name="price" required value="0" class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                        <p class="text-xs text-on-surface-variant mt-1">Set to 0 for a Free plan.</p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showCreateModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Create Plan</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showEditModal = false" x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface">Edit Plan</h2>
                <button type="button" @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container transition"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form :action="'<?= base_url('admin/subscriptions/update/') ?>' + editId" method="POST">
                <div class="p-6 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Plan Name</label>
                        <input type="text" name="name" x-model="editName" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Price (IDR)</label>
                        <input type="number" name="price" x-model="editPrice" required class="w-full h-10 px-3 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-2 outline-none">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDeleteModal = false" x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">warning</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface mb-2">Delete Plan?</h2>
                <p class="text-sm text-on-surface-variant mb-2">Are you sure you want to delete the <span class="font-bold text-on-surface" x-text="deleteName"></span> plan?</p>
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