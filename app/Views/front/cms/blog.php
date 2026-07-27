<?= $this->extend('front/layout/master') ?>
<?= $this->section('content') ?>

<main class="max-w-6xl mx-auto px-4 py-12 min-h-screen">
    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-on-surface mb-4">News & Updates</h1>
        <p class="text-on-surface-variant max-w-2xl mx-auto">Stay up to date with the latest announcements, property tips, and blog posts from HuniKita.</p>
    </div>

    <?php if (!empty($posts)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($posts as $post): ?>
                <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-tertiary-container text-on-tertiary-container">
                                <?= esc($post->category) ?>
                            </span>
                            <span class="text-xs text-on-surface-variant">
                                <?= date('M d, Y', strtotime($post->published_at)) ?>
                            </span>
                        </div>
                        
                        <h2 class="text-xl font-bold text-on-surface mb-3 line-clamp-2">
                            <?= esc($post->title) ?>
                        </h2>
                        
                        <!-- Strip HTML tags to create a clean text excerpt -->
                        <p class="text-on-surface-variant text-sm mb-6 line-clamp-3">
                            <?= word_limiter(strip_tags(htmlspecialchars_decode($post->content_body)), 20) ?>
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-outline-variant">
                            <!-- Link to the individual page using the slug -->
                            <a href="<?= base_url('cms/page/' . $post->slug) ?>" class="text-primary font-semibold hover:underline flex items-center gap-1 text-sm w-max">
                                Read More <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-20 bg-surface-container-low rounded-xl border border-outline-variant">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">article</span>
            <h2 class="text-xl font-semibold text-on-surface">No updates yet</h2>
            <p class="text-on-surface-variant mt-2">Check back later for new articles and announcements.</p>
        </div>
    <?php endif; ?>
</main>

<?= $this->endSection() ?>