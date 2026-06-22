<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Subscription Plans</h1>
        <p class="text-on-surface-variant">Manage platform pricing tiers and billing cycles.</p>
    </div>
    <button class="bg-primary text-on-primary px-4 py-2 rounded font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">add</span> Create Plan
    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="border border-outline-variant rounded-lg p-6 bg-surface">
        <span class="bg-surface-container-high px-2 py-1 rounded text-xs font-bold uppercase">Basic</span>
        <div class="text-3xl font-bold mt-4 mb-1">Free</div>
        <p class="text-sm text-on-surface-variant mb-6">Standard listing access.</p>
        <button class="w-full border border-primary text-primary font-semibold py-2 rounded">Edit Plan</button>
    </div>
    <div class="border-2 border-primary rounded-lg p-6 bg-primary-container/10">
        <span class="bg-primary text-on-primary px-2 py-1 rounded text-xs font-bold uppercase">Premium</span>
        <div class="text-3xl font-bold mt-4 mb-1">Rp 500K<span class="text-sm font-normal text-on-surface-variant">/mo</span></div>
        <p class="text-sm text-on-surface-variant mb-6">Up to 20 listings.</p>
        <button class="w-full bg-primary text-on-primary font-semibold py-2 rounded">Edit Plan</button>
    </div>
</div>
<?= $this->endSection() ?>