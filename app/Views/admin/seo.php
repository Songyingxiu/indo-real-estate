<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">SEO Settings</h1>
    <p class="text-on-surface-variant">Configure how your platform appears on search engines.</p>
</div>

<div x-data="{ 
    metaTitle: '<?= esc($seo['meta_title'] ?? 'EstateAdmin Pro | Find Your Dream Property') ?>', 
    metaDesc: '<?= esc($seo['meta_description'] ?? 'Discover the most exclusive real estate listings. Buy, sell, and rent properties with trusted verified agents.') ?>' 
}" class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <div class="bg-surface border border-outline-variant rounded-lg p-6">
        <h3 class="text-lg font-semibold text-on-surface mb-6 border-b border-outline-variant pb-2">Global Meta Data</h3>
        
        <form action="<?= base_url('admin/seo/save') ?>" method="POST" class="flex flex-col gap-5">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Platform Meta Title</label>
                <input name="meta_title" x-model="metaTitle" type="text" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <p class="text-xs text-on-surface-variant mt-1">Recommended length: 50-60 characters.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Meta Description</label>
                <textarea name="meta_description" x-model="metaDesc" rows="3" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                <p class="text-xs text-on-surface-variant mt-1">Recommended length: 150-160 characters.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">Target Keywords</label>
                <input name="focus_keywords" type="text" value="<?= esc($seo['focus_keywords'] ?? 'real estate, buy house, verified agents, property listing') ?>" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>

            <button type="submit" class="mt-4 bg-primary text-on-primary font-semibold py-2.5 rounded hover:opacity-90 transition w-32">Save SEO</button>
        </form>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-on-surface mb-4">Search Engine Preview</h3>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-surface-container-high rounded-full flex items-center justify-center font-bold text-primary">E</div>
                <div>
                    <div class="text-sm text-on-surface font-semibold">EstateAdmin Pro</div>
                    <div class="text-xs text-on-surface-variant">https://www.estateadmin.com</div>
                </div>
            </div>
            <h4 class="text-[#1a0dab] text-xl font-medium cursor-pointer hover:underline truncate" x-text="metaTitle || 'Please enter a title'"></h4>
            <p class="text-[#4d5156] text-sm mt-1" x-text="metaDesc || 'Please enter a description to see how it looks.'"></p>
        </div>
        
        <div class="bg-surface-container-low border border-outline-variant rounded p-4 flex items-start gap-3 mt-6">
            <span class="material-symbols-outlined text-primary mt-0.5">info</span>
            <div>
                <h4 class="font-semibold text-sm">Dynamic SEO</h4>
                <p class="text-sm text-on-surface-variant">Individual property pages automatically generate their own SEO tags based on the property title, location, and description.</p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>