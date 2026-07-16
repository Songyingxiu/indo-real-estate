<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div x-data="{ showEditor: false, postId: '', postTitle: '', postCategory: 'Blog', postBody: '' }">
    <div class="flex justify-between items-center mt-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Content Management</h1>
            <p class="text-on-surface-variant">Manage platform news, blog posts, and static legal/informational pages.</p>
        </div>
        <button @click="showEditor = true; postId = ''; postTitle = ''; postCategory = 'Blog'; postBody = ''" class="bg-primary text-on-primary px-4 py-2 rounded font-semibold flex items-center gap-2 hover:opacity-90 transition shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> New Content
        </button>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-4 font-semibold text-sm shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
                    <tr>
                        <th class="p-4 font-semibold text-on-surface-variant">Title</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Category</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Published Date</th>
                        <th class="p-4 font-semibold text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                                <td class="p-4 font-medium text-on-surface"><?= esc($post->title) ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $post->category === 'Page' ? 'bg-secondary-container text-on-secondary-container' : 'bg-tertiary-container text-on-tertiary-container' ?>">
                                        <?= esc($post->category) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-on-surface-variant"><?= date('M d, Y', strtotime($post->published_at)) ?></td>
                                <td class="p-4 text-right">
                                    <button @click="showEditor = true; 
                                                    postId = '<?= $post->id ?>'; 
                                                    postTitle = '<?= esc($post->title, 'js') ?>'; 
                                                    postCategory = '<?= esc($post->category, 'js') ?>'; 
                                                    postBody = '<?= esc($post->content_body, 'js') ?>';" 
                                            class="text-primary hover:underline font-medium">Edit</button>
                                        </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-6 text-center text-on-surface-variant italic">No posts or pages have been published yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDITOR WINDOW -->
    <div x-show="showEditor" 
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        
        <div @click.outside="showEditor = false" 
             x-show="showEditor"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             class="bg-surface w-full max-w-3xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface" x-text="postId ? 'Edit Content Details' : 'Create New Content'"></h2>
                <button @click="showEditor = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="<?= base_url('admin/cms/save') ?>" method="POST">
                <input type="hidden" name="id" x-model="postId">
                
                <div class="p-6 flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-on-surface mb-1">Title</label>
                            <input name="title" type="text" x-model="postTitle" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Type Category</label>
                            <select name="category" x-model="postCategory" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                <option value="Blog">Blog Post / News</option>
                                <option value="Page">Static Info Page</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Content Body</label>
                        <textarea name="content_body" x-model="postBody" required rows="10" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none font-sans" placeholder="Write your content markup or text here..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                    <button type="button" @click="showEditor = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save & Publish</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>