<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<!-- Added manageSubStatus to Alpine data -->
<div x-data="{ showModal: false, receiptUrl: '', invoiceNum: '', phoneNum: '', showManageModal: false, manageSubId: '', manageUserName: '', managePlanName: '', manageSubStatus: '' }" class="flex-1 p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto w-full">
    
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
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-label-md text-caption">
                                    <span class="material-symbols-outlined text-[14px]">workspace_premium</span>
                                    <?= esc($sub->plan_name) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-on-surface-variant"><?= date('d M Y', strtotime($sub->created_date)) ?></td>
                            
                            <td class="py-4 px-4">
                                <?php if(!empty($sub->payment_proof)): ?>
                                    <button @click="showModal = true; 
                                                    receiptUrl = '<?= base_url('uploads/payments/' . $sub->payment_proof) ?>'; 
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
                                <!-- FIXED: Now checking sub_status instead of status -->
                                <?php if($sub->sub_status == 'Pending'): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-error-container text-on-error-container font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">pending_actions</span> Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-tertiary-container text-on-tertiary-container font-label-md text-caption">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Active
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <!-- UNIFIED MANAGE BUTTON: Passes all data to the modal, including sub_status -->
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
        <!-- ... (Receipt modal code remains exactly the same) ... -->
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

    <!-- 2. Manage Subscription Modal (Smart Modal) -->
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
                <p class="text-sm text-on-surface">What would you like to do with the <span class="font-bold" x-text="managePlanName"></span> subscription for this user?</p>

                <!-- Show ACTIVATE button if sub_status is Pending -->
                <template x-if="manageSubStatus === 'Pending'">
                    <form :action="'<?= base_url('admin/subscriptions/activate/') ?>' + manageSubId" method="POST" onsubmit="return confirm('Activate this subscription for 1 year?');">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span> Activate Subscription
                        </button>
                    </form>
                </template>

                <!-- Show REVOKE button if sub_status is already Active -->
                <template x-if="manageSubStatus === 'Active'">
                    <form :action="'<?= base_url('admin/subscriptions/revoke/') ?>' + manageSubId" method="POST" onsubmit="return confirm('Are you sure you want to revoke this subscription? The user will lose access immediately.');">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-error text-on-error px-4 py-2 rounded font-label-md text-label-md hover:bg-error-container hover:text-on-error-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">cancel</span> Revoke Subscription
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>