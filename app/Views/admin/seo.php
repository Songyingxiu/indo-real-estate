<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="mb-stack-lg mt-4">
    <h2 class="font-headline-lg text-headline-lg text-on-background mb-stack-sm">SEO Configuration</h2>
    <p class="font-body-md text-body-md text-on-surface-variant">Manage meta tags and search engine visibility for primary platform pages.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    
    <div class="lg:col-span-2 space-y-stack-lg">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-stack-lg hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-300">
            <div class="space-y-stack-md">
                
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-stack-sm">Target Page</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-surface border border-outline-variant rounded px-stack-md py-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all">
                            <option>Homepage</option>
                            <option>Property Directory</option>
                            <option>Blog</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-stack-sm">
                        <label class="font-label-md text-label-md text-on-surface">Meta Title</label>
                        <span class="font-caption text-caption text-on-surface-variant" id="title-counter">50 / 60</span>
                    </div>
                    <input class="w-full bg-surface border border-outline-variant rounded px-stack-md py-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all" id="meta-title-input" placeholder="Sewa & Beli Properti Terbaik di Indonesia" type="text" value="Sewa & Beli Properti Mewah Terbaik di Indonesia | App Name">
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-stack-sm">
                        <label class="font-label-md text-label-md text-on-surface">Meta Description</label>
                        <span class="font-caption text-caption text-on-surface-variant" id="desc-counter">148 / 160</span>
                    </div>
                    <textarea class="w-full bg-surface border border-outline-variant rounded px-stack-md py-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all resize-none" id="meta-desc-input" placeholder="Temukan hunian eksklusif dan peluang investasi real estate..." rows="4">Temukan hunian eksklusif dan peluang investasi real estate premium di seluruh Indonesia. Platform tepercaya untuk pembeli, penyewa, dan investor serius.</textarea>
                </div>
                
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-stack-sm">Focus Keywords</label>
                    <input class="w-full bg-surface border border-outline-variant rounded px-stack-md py-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all" placeholder="e.g. real estate, rumah mewah indonesia" type="text" value="properti mewah, real estate indonesia, investasi rumah">
                    <p class="font-caption text-caption text-on-surface-variant mt-2">Pisahkan kata kunci dengan koma.</p>
                </div>
            </div>
            
            <div class="mt-stack-lg flex justify-end gap-stack-sm border-t border-outline-variant pt-stack-md">
                <button class="px-6 py-2 font-label-md text-label-md text-primary-container border border-transparent hover:bg-surface-container-low rounded transition-colors">Discard</button>
                <button class="px-6 py-2 font-label-md text-label-md bg-primary-container text-on-primary hover:bg-primary transition-colors rounded shadow-sm">Save Changes</button>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-stack-lg">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-stack-lg sticky top-24 shadow-sm">
            <h3 class="font-label-md text-label-md text-on-surface mb-stack-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">visibility</span> Search Engine Preview
            </h3>
            
            <div class="bg-surface p-stack-md rounded border border-outline-variant mt-stack-sm">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-6 h-6 rounded-full bg-surface-variant flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[14px] text-on-surface-variant">domain</span>
                    </div>
                    <div>
                        <p class="font-caption text-caption text-on-surface leading-tight">App Name</p>
                        <p class="font-caption text-[11px] text-on-surface-variant leading-tight">https://www.appname.co.id</p>
                    </div>
                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant ml-auto">more_vert</span>
                </div>
                <h4 class="font-body-lg text-body-lg text-[#1a0dab] hover:underline cursor-pointer mb-1 leading-tight" id="preview-title">Sewa & Beli Properti Mewah Terbaik di Indonesia | App Name</h4>
                <p class="font-caption text-[13px] text-[#4d5156] leading-relaxed line-clamp-2" id="preview-desc">Temukan hunian eksklusif dan peluang investasi real estate premium di seluruh Indonesia. Platform tepercaya untuk pembeli, penyewa, dan investor serius.</p>
            </div>
            
            <div class="mt-stack-md p-stack-sm bg-surface-container-low rounded border border-outline-variant border-dashed">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-tertiary-container text-[18px]">check_circle</span>
                    <span class="font-caption text-caption text-on-surface font-semibold">SEO Score: Good</span>
                </div>
                <ul class="font-caption text-caption text-on-surface-variant space-y-1 list-disc pl-5">
                    <li>Title length is optimal.</li>
                    <li>Description length is optimal.</li>
                    <li>Keywords present in title.</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('meta-title-input');
        const descInput = document.getElementById('meta-desc-input');
        const previewTitle = document.getElementById('preview-title');
        const previewDesc = document.getElementById('preview-desc');
        const titleCounter = document.getElementById('title-counter');
        const descCounter = document.getElementById('desc-counter');

        if(titleInput && previewTitle) {
            titleInput.addEventListener('input', (e) => {
                previewTitle.textContent = e.target.value || 'Sewa & Beli Properti Terbaik di Indonesia';
                titleCounter.textContent = `${e.target.value.length} / 60`;
                if(e.target.value.length > 60) titleCounter.classList.add('text-error');
                else titleCounter.classList.remove('text-error');
            });
        }

        if(descInput && previewDesc) {
            descInput.addEventListener('input', (e) => {
                previewDesc.textContent = e.target.value || 'Temukan hunian eksklusif...';
                descCounter.textContent = `${e.target.value.length} / 160`;
                if(e.target.value.length > 160) descCounter.classList.add('text-error');
                else descCounter.classList.remove('text-error');
            });
        }
    });
</script>

<?= $this->endSection() ?>