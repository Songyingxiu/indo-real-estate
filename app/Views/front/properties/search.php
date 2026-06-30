<?= $this->include('front/layout/header') ?>

<div class="flex-1 flex overflow-hidden h-[calc(100vh-80px)]">
    <aside class="w-80 bg-surface border-r border-outline-variant flex-shrink-0 flex flex-col h-full overflow-y-auto custom-scrollbar">
        <form action="<?= base_url('search') ?>" method="GET" class="p-6 flex flex-col gap-6">
            <h2 class="text-[24px] font-bold mb-2 text-primary">Filters</h2>
            
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-[14px] font-semibold text-on-surface">Keyword</label>
                <input name="q" value="<?= esc($keyword ?? '') ?>" class="w-full px-4 py-2 border border-outline-variant rounded bg-white" placeholder="e.g. Pondok Indah" type="text"/>
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-label-md text-[14px] font-semibold text-on-surface">Property Type</label>
                <?php if(!empty($propertyTypes)): ?>
                    <?php foreach($propertyTypes as $type): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input name="type[]" value="<?= $type->id ?>" class="rounded border-outline-variant text-primary" type="checkbox" <?= (isset($_GET['type']) && in_array($type->id, $_GET['type'])) ? 'checked' : '' ?>/>
                            <span class="text-[16px] text-on-surface-variant"><?= esc($type->name) ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded hover:bg-primary-container">Apply Filters</button>
        </form>
    </aside>

    <main class="flex-1 flex flex-col h-full bg-background overflow-y-auto">
        <header class="p-6 border-b border-outline-variant">
            <h1 class="text-[24px] font-bold text-on-background">Search Results</h1>
            <p class="text-[14px] text-on-surface-variant">Found <?= esc($total) ?> properties</p>
        </header>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if(!empty($properties)): ?>
                    <?php foreach($properties as $property): ?>
                        <article class="bg-surface border border-outline-variant rounded-xl overflow-hidden hover:shadow-lg transition-shadow flex flex-col">
                            <div class="h-48 w-full bg-surface-container-high"></div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-[18px] font-semibold text-on-surface"><?= esc($property->title) ?></h3>
                                <p class="text-[20px] font-bold text-primary my-2">Rp <?= number_format($property->tax_price, 0, ',', '.') ?></p>
                                <p class="text-[14px] text-on-surface-variant"><?= esc($property->area_name) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="col-span-full text-center py-10">No properties found.</p>
                <?php endif; ?>
            </div>

            <div class="mt-8 flex justify-center">
                <?= $pager->links('default', 'tailwind_pagination') ?>
            </div>
        </div>
    </main>
</div>

<?= $this->include('front/layout/footer') ?>