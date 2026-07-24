<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto mt-6 mb-12 px-4 sm:px-6">
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-success/10 text-success-700 p-4 rounded mb-6 border border-success/20 flex items-center gap-2 print:hidden">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-6 print:hidden">
        <a href="<?= base_url('admin/dashboard') ?>" class="text-primary hover:underline flex items-center gap-1 text-sm font-semibold">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Dashboard
        </a>
        <button onclick="window.print()" class="bg-surface border border-outline-variant text-on-surface hover:bg-surface-container transition-colors px-4 py-2 rounded font-semibold text-sm flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">print</span> Print Invoice
        </button>
    </div>

    <!-- Invoice Document -->
    <div class="bg-white border border-outline-variant rounded-xl shadow-md overflow-hidden print:border-none print:shadow-none">
        
        <!-- Header -->
        <div class="bg-[#003d79] text-white p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">INVOICE</h1>
                <p class="text-primary-200 mt-1 font-medium"><?= esc($payment->invoice_number) ?></p>
            </div>
            <div class="mt-4 sm:mt-0 text-right">
                <div class="flex items-center justify-end gap-2 text-2xl font-bold mb-1">
                    <span class="material-symbols-outlined text-3xl">real_estate_agent</span>
                    HuniKita
                </div>
                <p class="text-sm text-primary-200">PT HuniKita Properti Indonesia</p>
                <p class="text-sm text-primary-200">contact@hunikita.com</p>
            </div>
        </div>

        <div class="p-8">
            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-xs font-bold text-outline uppercase tracking-wider mb-2">Billed To</h3>
                    <p class="font-bold text-on-surface text-lg"><?= esc(session()->get('first_name') . ' ' . session()->get('last_name')) ?></p>
                    <p class="text-sm text-on-surface-variant"><?= esc(session()->get('email')) ?></p>
                    <p class="text-sm text-on-surface-variant mt-1">Phone: <?= esc($payment->phone_number) ?></p>
                </div>
                <div class="md:text-right">
                    <h3 class="text-xs font-bold text-outline uppercase tracking-wider mb-2">Invoice Details</h3>
                    <p class="text-sm text-on-surface-variant"><span class="font-semibold">Date Issued:</span> <?= date('d M Y', strtotime($payment->created_date)) ?></p>
                    
                    <div class="mt-3 inline-block">
                        <?php if ($payment->approval_status == 'Verified'): ?>
                            <span class="bg-success/10 border border-success/30 text-success-700 px-3 py-1 rounded font-bold text-sm tracking-wide">PAID / VERIFIED</span>
                        <?php elseif ($payment->approval_status == 'Rejected'): ?>
                            <span class="bg-error/10 border border-error/30 text-error-700 px-3 py-1 rounded font-bold text-sm tracking-wide">PAYMENT REJECTED</span>
                        <?php else: ?>
                            <span class="bg-amber-100 border border-amber-300 text-amber-800 px-3 py-1 rounded font-bold text-sm tracking-wide">PENDING VERIFICATION</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Itemized Table -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-outline-variant text-on-surface">
                            <th class="pb-3 text-sm font-bold uppercase">Description</th>
                            <th class="pb-3 text-sm font-bold uppercase text-center">Duration</th>
                            <th class="pb-3 text-sm font-bold uppercase text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-outline-variant/50">
                            <td class="py-4">
                                <p class="font-bold text-on-surface">HuniKita <?= esc($plan->name) ?> Plan</p>
                                <p class="text-sm text-on-surface-variant">Subscription access and premium features upgrade.</p>
                            </td>
                            <td class="py-4 text-center text-on-surface-variant">1 Year</td>
                            <td class="py-4 text-right font-bold text-on-surface">Rp <?= number_format($plan->price, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="flex justify-end mb-12">
                <div class="w-full md:w-1/2 bg-surface-container-lowest p-4 rounded-lg border border-outline-variant">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span class="text-on-surface">Total Due</span>
                        <span class="text-primary text-2xl">Rp <?= number_format($plan->price, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="border-t border-outline-variant pt-6">
                <h4 class="font-bold text-on-surface mb-2">Thank you for your business!</h4>
                <p class="text-sm text-on-surface-variant">
                    <?php if ($payment->approval_status == 'Pending'): ?>
                        Your uploaded payment proof is currently under review by our moderation team. Your subscription will be activated automatically once the funds have been verified.
                    <?php else: ?>
                        If you have any questions concerning this invoice, please contact support via the dashboard.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: white !important; }
        #sidebar, header, nav, .print\:hidden { display: none !important; }
        main { padding: 0 !important; margin: 0 !important; }
    }
</style>

<?= $this->endSection() ?>