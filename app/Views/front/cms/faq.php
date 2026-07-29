<?= $this->include('front/layout/header') ?>

<main class="flex-grow bg-surface-container-lowest min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 md:px-10">
        
        <!-- Search Header Section -->
        <div class="text-center mb-10">
            <h1 class="font-headline-xl text-[32px] md:text-[48px] font-bold text-on-background mb-4">How can we help you?</h1>
            <p class="text-on-surface-variant font-body-lg mb-8">Search our knowledge base or browse frequently asked questions below.</p>
            
            <div class="relative max-w-2xl mx-auto">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" id="faqSearch" placeholder="Type a keyword to find your answer..." class="w-full pl-12 pr-4 py-4 rounded-full border border-outline-variant shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-body-lg text-[16px] text-on-surface">
            </div>
        </div>

        <!-- FAQ Accordion Container -->
        <div class="flex flex-col gap-4" id="faqContainer">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $faq): ?>
                    <details class="faq-item group bg-surface border border-outline-variant rounded-lg [&_summary::-webkit-details-marker]:hidden shadow-sm">
                        <summary class="flex items-center justify-between p-5 font-label-lg text-[18px] text-on-background cursor-pointer hover:text-primary transition-colors faq-title">
                            <?= esc($faq->title) ?>
                            <span class="material-symbols-outlined transition duration-300 group-open:-rotate-180">expand_more</span>
                        </summary>
                        <div class="p-5 pt-0 text-on-surface-variant font-body-md border-t border-outline-variant/30 mt-2 leading-relaxed faq-content">
                            <?= $faq->content_body ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center p-8 bg-surface border border-outline-variant rounded-lg">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/50 mb-2">help_outline</span>
                    <p class="text-on-surface-variant">No FAQs available at the moment.</p>
                </div>
            <?php endif; ?>
            
            <!-- Empty State for Search Results -->
            <div id="noResults" class="hidden text-center p-8 bg-surface border border-outline-variant rounded-lg">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/50 mb-2">search_off</span>
                <p class="text-on-surface-variant text-lg font-semibold">No results found</p>
                <p class="text-on-surface-variant text-sm mt-1">We couldn't find any FAQs matching your search.</p>
            </div>
        </div>
        
    </div>
</main>

<!-- Live Search JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('faqSearch');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('noResults');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                faqItems.forEach(item => {
                    const title = item.querySelector('.faq-title').textContent.toLowerCase();
                    const content = item.querySelector('.faq-content').textContent.toLowerCase();
                    
                    // Check if query matches title or content
                    if (title.includes(query) || content.includes(query)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Toggle the 'no results' message
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            });
        }
    });
</script>

<?= $this->include('front/layout/footer') ?>