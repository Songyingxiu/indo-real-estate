<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto mt-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-on-surface mb-2">Help & Support</h1>
        <p class="text-on-surface-variant">Find resources and contact information for the EstateAdmin portal.</p>
    </div>

    <div class="bg-surface border border-outline-variant rounded-lg p-8 shadow-sm">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-outline-variant">
            <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">mail</span>
            </div>
            <div>
                <h3 class="font-bold text-lg text-on-surface">Contact IT Administration</h3>
                <p class="text-on-surface-variant text-sm">Need a system adjustment or reporting a bug?</p>
                <a href="mailto:support@estateadmin.com" class="text-primary font-semibold hover:underline text-sm">support@estateadmin.com</a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-surface-container-high text-on-surface rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">menu_book</span>
            </div>
            <div>
                <h3 class="font-bold text-lg text-on-surface">System Documentation</h3>
                <p class="text-on-surface-variant text-sm">Review the platform guidelines and SQA testing standards.</p>
                <button class="text-primary font-semibold hover:underline text-sm mt-1">Download PDF Guide</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>