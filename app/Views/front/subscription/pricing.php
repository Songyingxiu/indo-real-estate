<?= $this->include('front/layout/header') ?>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-16 min-h-[70vh]">
    
    <div class="text-center mb-12 max-w-2xl mx-auto">
        <h1 class="font-headline-xl text-[36px] md:text-[48px] font-bold text-primary mb-4">Choose Your Agent Plan</h1>
        <p class="font-body-lg text-[18px] text-on-surface-variant">Upgrade your HuniKita experience to unlock more property listings, priority placements, and direct buyer messaging.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded-xl mb-8 border border-[#a8c7fa] flex items-center justify-center gap-2 shadow-sm max-w-3xl mx-auto">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-semibold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
        <?php if(!empty($plans)): foreach($plans as $plan): ?>
            
            <div class="bg-surface border <?= $plan->price > 0 && $plan->price < 1000000 ? 'border-primary shadow-xl scale-105 z-10' : 'border-outline-variant shadow-sm hover:shadow-md' ?> rounded-2xl flex flex-col relative overflow-hidden transition-all duration-300">
                
                <?php if($plan->price > 0 && $plan->price < 1000000): ?>
                    <div class="bg-primary text-on-primary text-center py-1 text-xs font-bold tracking-widest uppercase">Most Popular</div>
                <?php endif; ?>

                <div class="p-6 md:p-8 flex flex-col h-full">
                    <h3 class="font-headline-lg text-[24px] font-bold text-on-surface mb-2"><?= esc($plan->name) ?></h3>
                    <p class="text-on-surface-variant text-[14px] mb-6 min-h-[40px]"><?= esc($plan->description) ?></p>
                    
                    <div class="mb-6 pb-6 border-b border-outline-variant">
                        <span class="font-headline-xl text-[36px] font-bold text-primary">Rp <?= number_format($plan->price, 0, ',', '.') ?></span>
                        <span class="text-on-surface-variant text-sm">/ yr</span>
                    </div>

                    <ul class="flex flex-col gap-4 mb-8 flex-grow">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            <span class="text-on-surface text-[14px] font-medium"><?= $plan->max_properties == 0 ? 'Unlimited' : esc($plan->max_properties) ?> Property Listings</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined <?= $plan->allow_messages ? 'text-primary' : 'text-outline-variant/50' ?> text-[20px]"><?= $plan->allow_messages ? 'check_circle' : 'cancel' ?></span>
                            <span class="text-on-surface text-[14px] <?= $plan->allow_messages ? 'font-medium' : 'text-outline-variant opacity-70 line-through' ?>">Direct In-App Messaging</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined <?= $plan->direct_email_inquiry ? 'text-primary' : 'text-outline-variant/50' ?> text-[20px]"><?= $plan->direct_email_inquiry ? 'check_circle' : 'cancel' ?></span>
                            <span class="text-on-surface text-[14px] <?= $plan->direct_email_inquiry ? 'font-medium' : 'text-outline-variant opacity-70 line-through' ?>">Direct Email & Phone Inquiries</span>
                        </li>
                    </ul>

                    <?php if(!session()->get('id')): ?>
                        <a href="<?= base_url('login') ?>" class="block text-center w-full bg-surface-container-high text-on-surface py-3 rounded-lg font-bold hover:bg-surface-container-highest transition-colors">Sign in to Upgrade</a>
                    <?php else: ?>
                        <form action="<?= base_url('subscription/checkout') ?>" method="POST" class="mt-auto">
                            <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                            <button type="submit" class="w-full <?= $plan->price > 0 && $plan->price < 1000000 ? 'bg-primary text-on-primary hover:bg-primary-container' : 'bg-primary-container text-on-primary hover:bg-primary' ?> py-3 rounded-lg font-bold transition-colors">
                                <?= $plan->price == 0 ? 'Current Plan' : 'Select Plan' ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; else: ?>
            <p class="col-span-full text-center text-on-surface-variant">No subscription plans available at the moment.</p>
        <?php endif; ?>
    </div>
</main>

<?= $this->include('front/layout/footer') ?>