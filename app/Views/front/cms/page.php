<?= $this->include('front/layout/header') ?>

<div class="bg-primary text-on-primary py-16 px-4 md:px-10 border-b-4 border-secondary-fixed">
    <div class="max-w-[1280px] mx-auto text-center">
        <h1 class="font-headline-xl text-[40px] md:text-[48px] font-bold mb-4"><?= esc($pageTitle) ?></h1>
        <p class="font-body-lg text-[18px] text-primary-fixed-dim max-w-2xl mx-auto"><?= lang('Front.page_subtitle') ?></p>
    </div>
</div>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[60vh]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <aside class="lg:col-span-3">
            <div class="sticky top-28 bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                <div class="p-4 bg-surface-container-low border-b border-outline-variant">
                    <h3 class="font-label-md text-[14px] uppercase tracking-wider font-bold text-on-surface"><?= lang('Front.lbl_legal_info') ?></h3>
                </div>
                <ul class="flex flex-col">
                    <li>
                        <a href="<?= base_url('page/about-us') ?>" class="block px-6 py-4 font-body-md text-[15px] border-l-4 <?= $slug == 'about-us' ? 'border-primary bg-primary-fixed/10 font-semibold text-primary' : 'border-transparent text-on-surface-variant hover:bg-surface-container hover:text-primary' ?> transition-colors">
                            <?= lang('Front.nav_about') ?>
                        </a>
                    </li>
                    <li class="border-t border-outline-variant/50">
                        <a href="<?= base_url('page/privacy-policy') ?>" class="block px-6 py-4 font-body-md text-[15px] border-l-4 <?= $slug == 'privacy-policy' ? 'border-primary bg-primary-fixed/10 font-semibold text-primary' : 'border-transparent text-on-surface-variant hover:bg-surface-container hover:text-primary' ?> transition-colors">
                            <?= lang('Front.nav_privacy') ?>
                        </a>
                    </li>
                    <li class="border-t border-outline-variant/50">
                        <a href="<?= base_url('page/terms-of-service') ?>" class="block px-6 py-4 font-body-md text-[15px] border-l-4 <?= $slug == 'terms-of-service' ? 'border-primary bg-primary-fixed/10 font-semibold text-primary' : 'border-transparent text-on-surface-variant hover:bg-surface-container hover:text-primary' ?> transition-colors">
                            <?= lang('Front.ft_tos') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <article class="lg:col-span-9">
            <div class="bg-surface border border-outline-variant rounded-xl p-8 md:p-10 shadow-sm">
                <div class="prose prose-primary max-w-none text-on-surface font-body-md text-[16px] leading-relaxed whitespace-pre-line">
                    
                    <?php if (!empty($post)): ?>
                        <?= nl2br(esc($post->content_body)) ?>
                        
                    <?php else: ?>
                        <?php if ($slug == 'about-us'): ?>
                            <h2 class="text-2xl font-bold mb-4"><?= lang('Front.page_mission_title') ?></h2>
                            <p class="mb-6 text-on-surface-variant"><?= lang('Front.page_mission_text') ?></p>
                            
                            <h2 class="text-2xl font-bold mb-4 mt-8"><?= lang('Front.page_choose_title') ?></h2>
                            <ul class="list-disc pl-5 space-y-3 mb-6 text-on-surface-variant">
                                <li><strong>Reliability:</strong> <?= lang('Front.page_choose_li1') ?></li>
                                <li><strong>Trust:</strong> <?= lang('Front.page_choose_li2') ?></li>
                                <li><strong>Community:</strong> <?= lang('Front.page_choose_li3') ?></li>
                            </ul>
                        
                        <?php elseif ($slug == 'privacy-policy'): ?>
                            <h2 class="text-2xl font-bold mb-4"><?= lang('Front.page_data_title') ?></h2>
                            <p class="mb-6"><?= lang('Front.page_data_text') ?></p>
                            
                            <h2 class="text-2xl font-bold mb-4"><?= lang('Front.page_use_title') ?></h2>
                            <p class="mb-6"><?= lang('Front.page_use_text') ?></p>
                        
                        <?php else: ?>
                            <h2 class="text-2xl font-bold mb-4"><?= lang('Front.page_uc_title') ?></h2>
                            <p class="mb-6 text-on-surface-variant"><?= sprintf(lang('Front.page_uc_text'), esc($pageTitle)) ?></p>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
                
                <div class="mt-10 pt-6 border-t border-outline-variant flex items-center justify-between text-sm text-on-surface-variant">
                    <span><?= lang('Front.lbl_last_updated') ?> <?= !empty($post) ? date('F j, Y', strtotime($post->updated_at ?? $post->published_at)) : date('F j, Y') ?></span>
                    <a href="<?= base_url('contact') ?>" class="text-primary hover:underline font-semibold"><?= lang('Front.btn_contact_support') ?></a>
                </div>
            </div>
        </article>

    </div>
</main>

<?= $this->include('front/layout/footer') ?>