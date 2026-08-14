<?= $this->include('front/layout/header') ?>

<main class="flex-grow bg-surface-container-lowest min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 md:px-10">
        
        <!-- Search Header Section -->
        <div class="text-center mb-8">
            <h1 class="font-headline-xl text-[32px] md:text-[48px] font-bold text-on-background mb-4"><?= lang('Front.faq_title') ?></h1>
            <p class="text-on-surface-variant font-body-lg mb-8"><?= lang('Front.faq_subtitle') ?></p>
            
            <div class="relative max-w-2xl mx-auto">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" id="faqSearch" placeholder="<?= lang('Front.placeholder_faq_search') ?>" class="w-full pl-12 pr-4 py-4 rounded-full border border-outline-variant shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-body-lg text-[16px] text-on-surface">
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="flex flex-wrap justify-center gap-2 mb-8" id="faqCategories">
            <button class="faq-cat-btn active px-5 py-2 rounded-full border border-primary bg-primary text-on-primary font-label-md transition-colors" data-cat="All">All</button>
            <button class="faq-cat-btn px-5 py-2 rounded-full border border-outline-variant bg-surface text-on-surface-variant hover:border-primary hover:text-primary font-label-md transition-colors" data-cat="User/Profile">User/Profile</button>
            <button class="faq-cat-btn px-5 py-2 rounded-full border border-outline-variant bg-surface text-on-surface-variant hover:border-primary hover:text-primary font-label-md transition-colors" data-cat="Property">Property</button>
            <button class="faq-cat-btn px-5 py-2 rounded-full border border-outline-variant bg-surface text-on-surface-variant hover:border-primary hover:text-primary font-label-md transition-colors" data-cat="Chat">Chat</button>
            <button class="faq-cat-btn px-5 py-2 rounded-full border border-outline-variant bg-surface text-on-surface-variant hover:border-primary hover:text-primary font-label-md transition-colors" data-cat="Subscription">Subscription</button>
            <button class="faq-cat-btn px-5 py-2 rounded-full border border-outline-variant bg-surface text-on-surface-variant hover:border-primary hover:text-primary font-label-md transition-colors" data-cat="Payment">Payment</button>
        </div>

        <!-- FAQ Accordion Container -->
        <div class="flex flex-col gap-4" id="faqContainer">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $faq): ?>
                    <details class="faq-item group bg-surface border border-outline-variant rounded-lg [&_summary::-webkit-details-marker]:hidden shadow-sm" data-category="<?= esc($faq->faq_category ?? 'Uncategorized') ?>">
                        <summary class="flex items-center justify-between p-5 cursor-pointer hover:text-primary transition-colors faq-title">
                            <div class="flex flex-col gap-1 pr-4">
                                <span class="font-label-lg text-[18px] text-on-background"><?= esc($faq->title) ?></span>
                                <span class="text-[12px] font-bold text-primary uppercase tracking-wider"><?= esc($faq->faq_category ?? 'General') ?></span>
                            </div>
                            <span class="material-symbols-outlined transition duration-300 group-open:-rotate-180 shrink-0">expand_more</span>
                        </summary>
                        <div class="p-5 pt-0 text-on-surface-variant font-body-md border-t border-outline-variant/30 mt-2 leading-relaxed faq-content">
                            <?= $faq->content_body ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center p-8 bg-surface border border-outline-variant rounded-lg">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/50 mb-2">help_outline</span>
                    <p class="text-on-surface-variant"><?= lang('Front.lbl_no_faq') ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Empty State for Search Results -->
            <div id="noResults" class="hidden text-center p-8 bg-surface border border-outline-variant rounded-lg">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/50 mb-2">search_off</span>
                <p class="text-on-surface-variant text-lg font-semibold"><?= lang('Front.lbl_no_results') ?></p>
                <p class="text-on-surface-variant text-sm mt-1"><?= lang('Front.lbl_no_results_sub') ?></p>
            </div>
        </div>
        
    </div>
</main>

<!-- Live Search and Category Filter JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('faqSearch');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('noResults');
        const catBtns = document.querySelectorAll('.faq-cat-btn');

        let currentQuery = '';
        let currentCategory = 'All';

        function filterFAQs() {
            let visibleCount = 0;

            faqItems.forEach(item => {
                const title = item.querySelector('.faq-title').textContent.toLowerCase();
                const content = item.querySelector('.faq-content').textContent.toLowerCase();
                const category = item.getAttribute('data-category');
                
                const matchesSearch = title.includes(currentQuery) || content.includes(currentQuery);
                const matchesCategory = currentCategory === 'All' || category === currentCategory;

                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentQuery = this.value.toLowerCase().trim();
                filterFAQs();
            });
        }

        catBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button styling
                catBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-on-primary', 'border-primary');
                    b.classList.add('bg-surface', 'text-on-surface-variant', 'border-outline-variant');
                });
                this.classList.remove('bg-surface', 'text-on-surface-variant', 'border-outline-variant');
                this.classList.add('bg-primary', 'text-on-primary', 'border-primary');
                
                currentCategory = this.getAttribute('data-cat');
                filterFAQs();
            });
        });
    });
</script>

<?= $this->include('front/layout/footer') ?>