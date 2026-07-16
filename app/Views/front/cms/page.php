<?= $this->include('front/layout/header') ?>

<!-- CMS Dynamic Hero Section -->
<div class="bg-primary text-on-primary py-16 px-4 md:px-10 border-b-4 border-secondary-fixed">
    <div class="max-w-[1280px] mx-auto text-center">
        <h1 class="font-headline-xl text-[40px] md:text-[48px] font-bold mb-4"><?= esc($post->title) ?></h1>
        <p class="font-body-lg text-[18px] text-primary-fixed-dim max-w-2xl mx-auto">Platform details, policy guidelines, and standard informational notices.</p>
    </div>
</div>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[60vh]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Sidebar Navigation Layout -->
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

        <!-- Main Dynamic Content Engine -->
        <article class="lg:col-span-9">
            <div class="bg-surface border border-outline-variant rounded-xl p-8 md:p-10 shadow-sm">
                <div class="prose prose-primary max-w-none text-on-surface font-body-md text-[16px] leading-relaxed whitespace-pre-line">
                    
                    <!-- Renders true content from database column -->
                    <?= nl2br(esc($post->content_body)) ?>

                </div>
                
                <div class="mt-10 pt-6 border-t border-outline-variant flex items-center justify-between text-sm text-on-surface-variant">
                    <span>Last updated: <?= date('F j, Y', strtotime($post->updated_at ?? $post->published_at)) ?></span>
                    <a href="<?= base_url('support') ?>" class="text-primary hover:underline font-semibold">Contact Support</a>
                </div>
            </div>
        </article>

    </div>
</main>

<?= $this->include('front/layout/footer') ?>