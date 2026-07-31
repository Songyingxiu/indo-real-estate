<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>
<div class="w-full px-6 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-on-background">Email Templates</h1>
            <p class="text-on-surface-variant mt-1 text-sm">Manage automated email structures and variables sent to users.</p>
        </div>
        <a href="<?= base_url('admin/email-templates/create') ?>" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-medium hover:opacity-90 shadow-sm transition-opacity">
            <span class="material-symbols-outlined text-sm">add</span> Create New Template
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="bg-surface rounded-xl shadow-sm border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-sm border-b border-outline-variant">
                        <th class="py-4 px-6 font-semibold">Template Name</th>
                        <th class="py-4 px-6 font-semibold">Subject</th>
                        <th class="py-4 px-6 font-semibold">Status</th>
                        <th class="py-4 px-6 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    <?php if(!empty($templates)): ?>
                        <?php foreach($templates as $template): ?>
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="py-4 px-6">
                                    <span class="font-medium text-on-background"><?= esc($template->name) ?></span>
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant text-sm">
                                    <?= esc($template->subject) ?>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if($template->status === 'Active'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        
                                        <a href="<?= base_url('admin/email-templates/edit/' . $template->id) ?>" class="text-primary hover:text-primary-container p-1 rounded transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <a href="<?= base_url('admin/email-templates/delete/' . $template->id) ?>" class="text-error hover:text-error-container p-1 rounded transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this template?');">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-on-surface-variant">
                                No email templates found. Click "Create New Template" to get started.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>