<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="mt-4 mb-8">
    <h1 class="text-2xl font-bold text-primary">Complete Your Upgrade</h1>
    <p class="text-on-surface-variant">Please complete the manual bank transfer to activate your <?= esc($plan->name) ?> package.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12">
    
    <!-- Invoice Details -->
    <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm h-fit">
        <h2 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">receipt_long</span> Order Summary
        </h2>
        
        <div class="flex justify-between items-center mb-3 text-sm">
            <span class="text-on-surface-variant">Invoice Number:</span>
            <span class="font-bold text-on-surface"><?= esc($invoice_number) ?></span>
        </div>
        <div class="flex justify-between items-center mb-6 text-sm">
            <span class="text-on-surface-variant">Plan Selected:</span>
            <span class="font-bold text-primary bg-primary-fixed-dim px-2 py-0.5 rounded"><?= esc($plan->name) ?></span>
        </div>

        <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant mb-6">
            <div class="flex justify-between items-center font-bold text-lg">
                <span class="text-on-surface">Total Amount:</span>
                <span class="text-primary">Rp <?= number_format($plan->price, 0, ',', '.') ?></span>
            </div>
        </div>

        <h3 class="font-bold text-on-surface mb-2">Transfer Instructions</h3>
        <p class="text-sm text-on-surface-variant mb-4">Please transfer the exact amount to one of our official bank accounts below:</p>
        
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded border border-outline-variant/50">
                <div class="w-12 h-8 bg-white border border-outline-variant rounded flex items-center justify-center font-bold text-primary text-xs">BCA</div>
                <div>
                    <p class="font-bold text-on-surface text-sm">8040 123 456</p>
                    <p class="text-xs text-on-surface-variant">PT HuniKita Properti</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded border border-outline-variant/50">
                <div class="w-12 h-8 bg-white border border-outline-variant rounded flex items-center justify-center font-bold text-[#003d79] text-xs">MNDRI</div>
                <div>
                    <p class="font-bold text-on-surface text-sm">137 000 999 888</p>
                    <p class="text-xs text-on-surface-variant">PT HuniKita Properti</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Proof Form -->
    <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm h-fit">
        <h2 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">upload_file</span> Upload Payment Proof
        </h2>
        
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-[#ffdad6] text-[#410002] p-3 rounded mb-4 text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/subscription/upload-proof') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            
            <input type="hidden" name="subscription_id" value="<?= esc($subscription_id) ?>">
            <input type="hidden" name="invoice_number" value="<?= esc($invoice_number) ?>">
            
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">WhatsApp / Phone Number <span class="text-error">*</span></label>
                <p class="text-xs text-on-surface-variant mb-2">We will notify you once your payment is verified.</p>
                <input type="tel" name="phone_number" required placeholder="e.g. 081234567890" value="<?= esc(session()->get('phone_number')) ?>" class="w-full border border-outline-variant rounded px-3 py-2 text-sm focus:border-primary focus:ring-1 bg-white outline-none">
                <?= session('errors.phone_number') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.phone_number')).'</p>' : '' ?>
            </div>

            <!-- Alpine.js Component for Upload Box -->
            <div x-data="{ fileName: '', fileUrl: '' }">
                <label class="block text-sm font-semibold text-on-surface mb-2">Upload Transfer Receipt <span class="text-error">*</span></label>
                
                <label for="payment_proof" 
                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer transition-colors duration-200"
                       :class="fileName ? 'border-success bg-success/5' : 'border-outline-variant hover:bg-surface-container-low bg-surface'">
                    
                    <!-- Default State -->
                    <div x-show="!fileName" class="flex flex-col items-center justify-center pt-5 pb-6">
                        <span class="material-symbols-outlined text-[36px] text-primary mb-2">cloud_upload</span>
                        <p class="text-sm font-semibold text-on-surface">Click to upload or drag and drop</p>
                        <p class="text-xs text-on-surface-variant mt-1">PNG, JPG up to 5MB</p>
                    </div>

                    <!-- Uploaded State -->
                    <div x-show="fileName" class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4" style="display: none;">
                        <span class="material-symbols-outlined text-[36px] text-success mb-2">check_circle</span>
                        <p class="text-sm font-bold text-success mb-1 truncate w-full" x-text="fileName"></p>
                        <p class="text-xs text-on-surface-variant">File attached and ready to submit</p>
                    </div>

                    <input id="payment_proof" name="payment_proof" type="file" accept="image/*" required class="hidden"
                           @change="fileName = $event.target.files[0]?.name; fileUrl = URL.createObjectURL($event.target.files[0])">
                </label>
                <?= session('errors.payment_proof') ? '<p class="text-error text-xs mt-1 font-medium">'.esc(session('errors.payment_proof')).'</p>' : '' ?>

                <!-- Image Preview Thumbnail -->
                <template x-if="fileUrl">
                    <div class="mt-4 flex justify-center">
                        <img :src="fileUrl" class="max-h-40 rounded border border-outline-variant shadow-sm object-contain" />
                    </div>
                </template>

                <div class="pt-4 mt-2 border-t border-outline-variant">
                    <button type="submit" :disabled="!fileName" class="w-full bg-primary text-on-primary px-6 py-3 rounded font-bold text-sm flex items-center justify-center gap-2" :class="!fileName ? 'opacity-50 cursor-not-allowed' : 'hover:bg-primary-container transition-colors'">
                        <span class="material-symbols-outlined text-[18px]">verified</span> Submit Payment Proof
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>