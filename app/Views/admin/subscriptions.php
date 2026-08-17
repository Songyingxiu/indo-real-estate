<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div x-data="{ 
        showModal: false, receiptUrl: '', invoiceNum: '', phoneNum: '', 
        showManageModal: false, manageSubId: '', manageUserName: '', managePlanName: '', manageSubStatus: '',
        showConfirmModal: false, confirmTitle: '', confirmMessage: '', confirmUrl: '', confirmActionTheme: 'primary',
        showDetailsModal: false, detailPlanName: '', detailDesc: '', detailProps: 0, detailAgents: 0, detailPois: 0, detailMsg: 0, detailEmail: 0
    }" class="flex-1 p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto w-full">
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-[#ffdad6] text-[#410002] p-4 rounded mb-6 border border-[#ffb4ab] flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="mb-stack-lg">
        <h2 class="font-headline-lg text-headline-lg text-primary mb-unit">Subscription Management</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Verify manual bank transfers and activate user upgrade packages.</p>
    </div>

    <div class="bg-surface rounded-lg border border-outline-variant overflow-hidden hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-300">
        <div class="overflow-x-auto pb-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">User Name</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Requested Plan</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Date Submitted</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Payment Proof</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant">Status</th>
                        <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-body-md text-on-surface">
                    <?php if(!empty($subscriptions)): foreach($subscriptions as $sub): ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="py-4 px-4 flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-fixed text-on-primary-fixed rounded-full flex items-center justify-center font-label-md uppercase">
                                    <?= substr($sub->first_name, 0, 1) . substr($sub->last_name ?? '', 0, 1) ?>
                                </div>
                                <span class="font-medium"><?= esc($sub->first_name . ' ' . $sub->last_name) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">workspace_premium</span>
                                        <?= esc($sub->plan_name) ?>
                                    </span>
                                    <button @click="showDetailsModal = true;
                                                    detailPlanName = '<?= esc(addslashes($sub->plan_name)) ?>';
                                                    detailDesc = '<?= esc(addslashes($sub->plan_desc ?? '')) ?>';
                                                    detailProps = <?= $sub->max_properties ?? 0 ?>;
                                                    detailAgents = <?= $sub->max_agents ?? 0 ?>;
                                                    detailPois = <?= $sub->max_pois ?? 0 ?>;
                                                    detailMsg = <?= $sub->allow_messages ?? 0 ?>;
                                                    detailEmail = <?= $sub->allow_direct_email ?? 0 ?>;"
                                            type="button" class="text-primary hover:bg-surface-container rounded-full p-1 transition-colors flex items-center justify-center" title="View Plan Details">
                                        <span class="material-symbols-outlined text-[18px]">info</span>
                                    </button>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-on-surface-variant"><?= date('d M Y', strtotime($sub->created_date)) ?></td>
                            
                            <td class="py-4 px-4">
                                <?php if(!empty($sub->payment_proof)): ?>
                                    <button @click="showModal = true; 
                                                    receiptUrl = '<?= esc($sub->payment_proof) ?>'; 
                                                    invoiceNum = '<?= esc($sub->invoice_number) ?>'; 
                                                    phoneNum = '<?= esc($sub->phone_number) ?>';" 
                                            type="button" class="flex items-center gap-1 text-primary border border-outline-variant px-3 py-1.5 rounded hover:bg-surface-container transition-colors font-label-md text-label-md">
                                        <span class="material-symbols-outlined text-[18px]">receipt_long</span> View Receipt
                                    </button>
                                <?php else: ?>
                                    <span class="text-outline-variant text-sm italic">No receipt uploaded</span>
                                <?php endif; ?>
                            </td>

                            <td class="py-4 px-4">
                                <?php if($sub->sub_status == 'Pending'): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-[#fff8e1] text-[#b45309] font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">pending_actions</span> Pending
                                    </span>
                                <?php elseif($sub->sub_status == 'Active'): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-tertiary-container text-on-tertiary-container font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-error text-on-error font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">block</span> Revoked
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <button @click="showManageModal = true;
                                                manageSubId = '<?= $sub->id ?>';
                                                manageUserName = '<?= esc(addslashes($sub->first_name . ' ' . $sub->last_name)) ?>';
                                                managePlanName = '<?= esc(addslashes($sub->plan_name)) ?>';
                                                manageSubStatus = '<?= esc(addslashes($sub->sub_status)) ?>';"
                                        type="button" class="inline-block text-center border border-primary text-primary px-4 py-2 rounded font-label-md text-label-md hover:bg-primary-fixed hover:text-on-primary-fixed transition-colors">
                                    Manage
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="py-6 text-center text-outline">No subscription requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 1. Receipt Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showModal = false" x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-lg rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div>
                    <h2 class="text-lg font-bold text-on-surface">Payment Receipt</h2>
                    <p class="text-sm text-primary font-bold mt-1" x-text="invoiceNum"></p>
                </div>
                <button @click="showModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 bg-surface-container-low flex flex-col justify-center items-center min-h-[300px] max-h-[60vh] overflow-y-auto">
                <template x-if="receiptUrl && !receiptUrl.endsWith('/')">
                    <img :src="receiptUrl" alt="Receipt Preview" class="max-w-full rounded border border-outline-variant shadow-sm" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'text-center text-outline-variant\'><span class=\'material-symbols-outlined text-[48px] mb-2\'>broken_image</span><p>Image file not found on server.</p></div>';">
                </template>
                <template x-if="!receiptUrl || receiptUrl.endsWith('/')">
                    <div class="flex flex-col items-center text-outline-variant">
                        <span class="material-symbols-outlined text-[48px] mb-2">image_not_supported</span>
                        <p class="text-sm">No valid receipt file uploaded.</p>
                    </div>
                </template>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px]">call</span>
                    <span x-text="phoneNum"></span>
                </div>
                <button type="button" @click="showModal = false" class="px-6 py-2 border border-outline-variant text-on-surface rounded font-semibold hover:bg-surface-container transition">Close</button>
            </div>
        </div>
    </div>

    <!-- 2. Manage Subscription Modal (Triggers Confirmation) -->
    <div x-show="showManageModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showManageModal = false" x-show="showManageModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div>
                    <h2 class="text-lg font-bold text-on-surface">Manage Subscription</h2>
                    <p class="text-sm text-on-surface-variant mt-1">User: <span class="font-bold text-primary" x-text="manageUserName"></span></p>
                </div>
                <button @click="showManageModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 bg-surface flex flex-col gap-4">
                
                <template x-if="manageSubStatus === 'Expired'">
                    <div class="text-center py-4 bg-surface-container-low text-on-surface border border-outline-variant rounded">
                        <span class="material-symbols-outlined text-error text-[32px] mb-2">block</span>
                        <p class="font-bold">Subscription is Revoked</p>
                        <p class="text-sm text-on-surface-variant mt-1">No further actions can be taken on this record.</p>
                    </div>
                </template>

                <template x-if="manageSubStatus !== 'Expired'">
                    <p class="text-sm text-on-surface">What would you like to do with the <span class="font-bold" x-text="managePlanName"></span> subscription for this user?</p>
                </template>

                <template x-if="manageSubStatus === 'Pending'">
                    <button type="button" @click="
                        showConfirmModal = true;
                        confirmTitle = 'Activate Subscription';
                        confirmMessage = 'Are you sure you want to activate this subscription for 1 year?';
                        confirmUrl = '<?= base_url('admin/subscriptions/activate/') ?>' + manageSubId;
                        confirmActionTheme = 'primary';
                    " class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span> Activate Subscription
                    </button>
                </template>

                <template x-if="manageSubStatus === 'Active'">
                    <button type="button" @click="
                        showConfirmModal = true;
                        confirmTitle = 'Revoke Subscription';
                        confirmMessage = 'Are you sure you want to revoke this subscription? The user will lose access immediately.';
                        confirmUrl = '<?= base_url('admin/subscriptions/revoke/') ?>' + manageSubId;
                        confirmActionTheme = 'error';
                    " class="w-full flex items-center justify-center gap-2 bg-error text-on-error px-4 py-2 rounded font-label-md text-label-md hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]">cancel</span> Revoke Subscription
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- 3. Custom Confirmation Modal -->
    <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#1a1c1e]/80 backdrop-blur-sm p-4">
        <div @click.outside="showConfirmModal = false" x-show="showConfirmModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-sm rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden text-center">
            
            <div class="p-6">
                <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-4"
                     :class="confirmActionTheme === 'error' ? 'bg-error-container text-on-error-container' : 'bg-primary-container text-on-primary-container'">
                    <span class="material-symbols-outlined text-[24px]" x-text="confirmActionTheme === 'error' ? 'warning' : 'info'"></span>
                </div>
                <h2 class="text-lg font-bold text-on-surface mb-2" x-text="confirmTitle"></h2>
                <p class="text-sm text-on-surface-variant" x-text="confirmMessage"></p>
            </div>
            
            <div class="px-6 py-4 bg-surface-container-lowest flex gap-3 justify-end border-t border-outline-variant">
                <button type="button" @click="showConfirmModal = false" class="px-4 py-2 border border-outline-variant text-on-surface rounded font-label-md text-label-md hover:bg-surface-container transition">Cancel</button>
                
                <form :action="confirmUrl" method="POST" class="m-0">
                    <button type="submit" class="px-4 py-2 rounded font-label-md text-label-md text-white transition h-full"
                            :class="confirmActionTheme === 'error' ? 'bg-error hover:bg-error/90' : 'bg-primary hover:bg-primary/90'"
                            x-text="confirmTitle.split(' ')[0]"></button>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. Plan Details Modal -->
    <div x-show="showDetailsModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        <div @click.outside="showDetailsModal = false" x-show="showDetailsModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <div>
                    <h2 class="text-lg font-bold text-on-surface">Plan Capabilities</h2>
                    <p class="text-sm text-primary font-bold mt-1" x-text="detailPlanName"></p>
                </div>
                <button @click="showDetailsModal = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 bg-surface flex flex-col gap-4">
                <p class="text-sm text-on-surface-variant" x-text="detailDesc"></p>
                
                <ul class="grid grid-cols-1 gap-3 text-sm text-on-surface mt-2">
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span> 
                        <span class="font-semibold" x-text="detailProps >= 9999 ? 'Unlimited' : detailProps"></span> Properties
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span> 
                        <span class="font-semibold" x-text="detailAgents >= 9999 ? 'Unlimited' : detailAgents"></span> Agents
                    </li>
                    <template x-if="detailMsg == 1">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span> Messaging
                        </li>
                    </template>
                    <template x-if="detailEmail == 1">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span> Direct Inquiry Email
                        </li>
                    </template>
                    <template x-if="detailPois > 0">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[18px]">check_circle</span> 
                            <span class="font-semibold" x-text="detailPois >= 9999 ? 'Unlimited' : detailPois"></span> Custom POI
                        </li>
                    </template>
                </ul>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant flex justify-end bg-surface-container-lowest">
                <button type="button" @click="showDetailsModal = false" class="px-6 py-2 border border-outline-variant text-on-surface rounded font-semibold hover:bg-surface-container transition">Close</button>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>