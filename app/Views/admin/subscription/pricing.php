<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="mt-4 mb-8 text-center max-w-2xl mx-auto">
    <h1 class="text-3xl md:text-4xl font-bold text-primary mb-4">Choose Your Agent Plan</h1>
    <p class="text-on-surface-variant">Upgrade your HuniKita experience to unlock more property listings, priority placements, and direct buyer messaging.</p>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded-xl mb-8 border border-[#a8c7fa] flex items-center justify-center gap-2 shadow-sm max-w-3xl mx-auto">
        <span class="material-symbols-outlined">check_circle</span>
        <span class="font-semibold"><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start pb-12">
    <?php if(!empty($plans)): foreach($plans as $plan): ?>
        
        <div class="bg-surface border <?= $plan->price > 0 && $plan->price < 1000000 ? 'border-primary shadow-xl scale-105 z-10' : 'border-outline-variant shadow-sm hover:shadow-md' ?> rounded-2xl flex flex-col relative overflow-hidden transition-all duration-300">
            
            <?php if($plan->price > 0 && $plan->price < 1000000): ?>
                <div class="bg-primary text-on-primary text-center py-1 text-xs font-bold tracking-widest uppercase">Most Popular</div>
            <?php endif; ?>

            <div class="p-6 flex flex-col h-full">
                <h3 class="text-xl font-bold text-on-surface mb-2"><?= esc($plan->name) ?></h3>
                <p class="text-on-surface-variant text-sm mb-6 min-h-[40px]"><?= esc($plan->description) ?></p>
                
                <div class="mb-6 pb-6 border-b border-outline-variant">
                    <span class="text-3xl font-bold text-primary">Rp <?= number_format($plan->price, 0, ',', '.') ?></span>
                    <span class="text-on-surface-variant text-sm">/ yr</span>
                </div>

                <ul class="flex flex-col gap-4 mb-8 flex-grow">
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                        <span class="text-on-surface text-sm font-medium"><?= $plan->max_properties == 0 ? 'Unlimited' : esc($plan->max_properties) ?> Property Listings</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined <?= ($plan->allow_messages ?? false) ? 'text-primary' : 'text-outline-variant/50' ?> text-[20px]"><?= ($plan->allow_messages ?? false) ? 'check_circle' : 'cancel' ?></span>
                        <span class="text-on-surface text-sm <?= ($plan->allow_messages ?? false) ? 'font-medium' : 'text-outline-variant opacity-70 line-through' ?>">Direct In-App Messaging</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined <?= ($plan->direct_email_inquiry ?? false) ? 'text-primary' : 'text-outline-variant/50' ?> text-[20px]"><?= ($plan->direct_email_inquiry ?? false) ? 'check_circle' : 'cancel' ?></span>
                        <span class="text-on-surface text-sm <?= ($plan->direct_email_inquiry ?? false) ? 'font-medium' : 'text-outline-variant opacity-70 line-through' ?>">Direct Email & Phone Inquiries</span>
                    </li>
                </ul>

                <form action="<?= base_url('admin/subscription/checkout') ?>" method="POST" class="mt-auto">
                    <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                    <button type="submit" class="w-full <?= $plan->price > 0 && $plan->price < 1000000 ? 'bg-primary text-on-primary hover:bg-primary-container' : 'bg-primary-container text-on-primary hover:bg-primary' ?> py-3 rounded-lg font-bold transition-colors">
                        <?= $plan->price == 0 ? 'Current Plan' : 'Select Plan' ?>
                    </button>
                </form>
            </div>
        </div>

    <?php endforeach; else: ?>
        <p class="col-span-full text-center text-on-surface-variant">No subscription plans available at the moment.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>