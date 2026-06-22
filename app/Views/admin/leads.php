<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<div class="mt-4 mb-6">
    <h1 class="text-2xl font-bold text-on-surface">Lead Management</h1>
    <p class="text-on-surface-variant">Track buyer inquiries and contact requests.</p>
</div>
<div class="bg-surface border border-outline-variant rounded-lg p-12 flex flex-col items-center justify-center text-center">
    <div class="w-16 h-16 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center mb-4">
        <span class="material-symbols-outlined text-3xl">record_voice_over</span>
    </div>
    <h3 class="text-lg font-semibold mb-2">No Active Leads</h3>
    <p class="text-on-surface-variant max-w-md">Your properties haven't received any inquiries yet. When buyers use the 'Contact Agent' form, they will appear here.</p>
</div>
<?= $this->endSection() ?>