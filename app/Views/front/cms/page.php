<?= $this->include('front/layout/header') ?>

<!-- CMS Hero Section -->
<div class="bg-primary text-on-primary py-16 px-4 md:px-10 border-b-4 border-secondary-fixed">
    <div class="max-w-[1280px] mx-auto text-center">
        <h1 class="font-headline-xl text-[40px] md:text-[48px] font-bold mb-4"><?= esc($pageTitle) ?></h1>
        <p class="font-body-lg text-[18px] text-primary-fixed-dim max-w-2xl mx-auto">Learn more about HuniKita's policies, terms, and our mission to simplify real estate.</p>
    </div>
</div>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[60vh]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3">
            <div class="sticky top-28 bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                <div class="p-4 bg-surface-container-low border-b border-outline-variant">
                    <h3 class="font-label-md text-[14px] uppercase tracking-wider font-bold text-on-surface">Legal & Info</h3>
                </div>
                <ul class="flex flex-col">
                    <li>
                        <a href="<?= base_url('page/about-us') ?>" class="block px-6 py-4 font-body-md text-[15px] border-l-4 <?= $slug == 'about-us' ? 'border-primary bg-primary-fixed/10 font-semibold text-primary' : 'border-transparent text-on-surface-variant hover:bg-surface-container hover:text-primary' ?> transition-colors">
                            About Us
                        </a>
                    </li>
                    <li class="border-t border-outline-variant/50">
                        <a href="<?= base_url('page/privacy-policy') ?>" class="block px-6 py-4 font-body-md text-[15px] border-l-4 <?= $slug == 'privacy-policy' ? 'border-primary bg-primary-fixed/10 font-semibold text-primary' : 'border-transparent text-on-surface-variant hover:bg-surface-container hover:text-primary' ?> transition-colors">
                            Privacy Policy
                        </a>
                    </li>
                    <li class="border-t border-outline-variant/50">
                        <a href="<?= base_url('page/terms-of-service') ?>" class="block px-6 py-4 font-body-md text-[15px] border-l-4 <?= $slug == 'terms-of-service' ? 'border-primary bg-primary-fixed/10 font-semibold text-primary' : 'border-transparent text-on-surface-variant hover:bg-surface-container hover:text-primary' ?> transition-colors">
                            Terms of Service
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content Area -->
        <article class="lg:col-span-9">
            <div class="bg-surface border border-outline-variant rounded-xl p-8 md:p-10 shadow-sm">
                
                <div class="prose prose-primary max-w-none text-on-surface font-body-md text-[16px] leading-relaxed">
                    
                    <?php if ($slug == 'about-us'): ?>
                        <h2 class="text-2xl font-bold mb-4">Our Mission</h2>
                        <p class="mb-6">HuniKita was founded with a simple goal: to make finding, buying, and selling property transparent, secure, and effortless. We bridge the gap between premium agents and eager buyers using cutting-edge technology.</p>
                        
                        <h2 class="text-2xl font-bold mb-4">Why Choose Us?</h2>
                        <ul class="list-disc pl-5 space-y-2 mb-6 text-on-surface-variant">
                            <li>Verified Agents and authentic property listings.</li>
                            <li>Secure built-in communication systems.</li>
                            <li>Advanced map search and virtual tour capabilities.</li>
                        </ul>
                    
                    <?php elseif ($slug == 'privacy-policy'): ?>
                        <h2 class="text-2xl font-bold mb-4">Data Collection</h2>
                        <p class="mb-6">We collect information to provide better services to our users. This includes basic information like your email address and phone number when you submit property inquiries.</p>
                        
                        <h2 class="text-2xl font-bold mb-4">How We Use Your Information</h2>
                        <p class="mb-6">The information we collect is used strictly to facilitate communication between buyers and verified agents. We do not sell your personal data to third parties.</p>
                    
                    <?php else: ?>
                        <h2 class="text-2xl font-bold mb-4">Page Under Construction</h2>
                        <p class="mb-6 text-on-surface-variant">The content for the <strong><?= esc($pageTitle) ?></strong> page is currently being drafted by our legal and content teams. Please check back later.</p>
                    <?php endif; ?>

                </div>
                
                <div class="mt-10 pt-6 border-t border-outline-variant flex items-center justify-between text-sm text-on-surface-variant">
                    <span>Last updated: <?= date('F j, Y') ?></span>
                    <a href="<?= base_url('contact') ?>" class="text-primary hover:underline font-semibold">Contact Support</a>
                </div>
            </div>
        </article>

    </div>
</main>

<?= $this->include('front/layout/footer') ?>