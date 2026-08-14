<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<div x-data="{ 
    showEditor: false, 
    postId: '', 
    postTitleEN: '', 
    postTitleID: '', 
    postCategory: 'Blog', 
    postFaqCategory: '',
    postBodyEN: '', 
    postBodyID: '', 
    showDeleteModal: false, 
    deleteUrl: '',
    translateText(text, targetInput) {
        if (!text.trim()) return;
        fetch('http://127.0.0.1:5000/translate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                q: text,
                source: 'auto',
                target: 'id',
                format: 'text'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.translatedText) {
                this[targetInput] = data.translatedText;
            }
        })
        .catch(err => console.error('Translation error:', err));
    }
}">
    <div class="flex justify-between items-center mt-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Content Management</h1>
            <p class="text-on-surface-variant">Manage platform news, blog posts, FAQs, and static legal/informational pages.</p>
        </div>
        <button @click="showEditor = true; postId = ''; postTitleEN = ''; postTitleID = ''; postCategory = 'Blog'; postFaqCategory = ''; postBodyEN = ''; postBodyID = ''" class="bg-primary text-on-primary px-4 py-2 rounded font-semibold flex items-center gap-2 hover:opacity-90 transition shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> New Content
        </button>
    </div>

    <!-- SUCCESS NOTIFICATION -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms
             class="flex items-center justify-between bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow-sm mb-6">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="font-semibold text-sm"><?= session()->getFlashdata('success') ?></p>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800 focus:outline-none">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- ERROR NOTIFICATION -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms
             class="flex items-center justify-between bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded shadow-sm mb-6">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600">error</span>
                <p class="font-semibold text-sm"><?= session()->getFlashdata('error') ?></p>
            </div>
            <button @click="show = false" class="text-red-600 hover:text-red-800 focus:outline-none">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant text-sm">
                    <tr>
                        <th class="p-4 font-semibold text-on-surface-variant">Title (EN)</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Category</th>
                        <th class="p-4 font-semibold text-on-surface-variant">Published Date</th>
                        <th class="p-4 font-semibold text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <tr class="border-b border-outline-variant hover:bg-surface-bright transition">
                                <td class="p-4 font-medium text-on-surface"><?= esc($post->title_en ?? $post->title) ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $post->category === 'Page' ? 'bg-secondary-container text-on-secondary-container' : 'bg-tertiary-container text-on-tertiary-container' ?>">
                                        <?= esc($post->category) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-on-surface-variant"><?= date('M d, Y', strtotime($post->published_at)) ?></td>
                                <td class="p-4 text-right flex justify-end gap-3 items-center">
                                    <button @click="showEditor = true; 
                                                    postId = '<?= $post->id ?>'; 
                                                    postTitleEN = '<?= esc($post->title_en ?? $post->title, 'js') ?>'; 
                                                    postTitleID = '<?= esc($post->title_id ?? $post->title, 'js') ?>'; 
                                                    postCategory = '<?= esc($post->category, 'js') ?>'; 
                                                    postFaqCategory = '<?= esc($post->faq_category ?? '', 'js') ?>'; 
                                                    postBodyEN = '<?= esc($post->content_body_en ?? $post->content_body, 'js') ?>';
                                                    postBodyID = '<?= esc($post->content_body_id ?? $post->content_body, 'js') ?>';" 
                                            class="text-primary hover:underline font-medium">Edit</button>
                                    
                                    <button type="button" 
                                            @click="showDeleteModal = true; deleteUrl = '<?= base_url('admin/cms/delete/' . $post->id) ?>';" 
                                            class="text-red-600 hover:underline font-medium">Delete</button>
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

    <!-- CUSTOM DELETE CONFIRMATION MODAL -->
    <div x-show="showDeleteModal" 
         style="display: none;"
         class="fixed inset-0 z-[110] flex items-center justify-center bg-[#1a1c1e]/60 backdrop-blur-sm p-4">
        
        <div @click.outside="showDeleteModal = false" 
             x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             class="bg-surface w-full max-w-md rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 border-b border-outline-variant flex items-center gap-3 bg-red-50">
                <span class="material-symbols-outlined text-red-600">warning</span>
                <h2 class="text-lg font-bold text-red-800">Confirm Deletion</h2>
            </div>
            
            <div class="p-6">
                <p class="text-on-surface-variant font-medium text-center">Are you sure you want to delete this content? <br><span class="text-red-600 font-bold">This action cannot be undone.</span></p>
            </div>
            
            <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest">
                <button type="button" @click="showDeleteModal = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                <form :action="deleteUrl" method="POST" class="m-0 p-0">
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded font-semibold hover:bg-red-700 transition shadow-sm">Yes, Delete</button>
                </form>
            </div>
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
             class="bg-surface w-full max-w-4xl rounded-xl shadow-2xl border border-outline-variant flex flex-col overflow-hidden max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="text-xl font-bold text-on-surface" x-text="postId ? 'Edit Content Details' : 'Create New Content'"></h2>
                <button type="button" @click="showEditor = false" class="text-on-surface-variant hover:text-on-surface p-2 rounded-full hover:bg-surface-container transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="<?= base_url('admin/cms/save') ?>" method="POST" class="overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="id" x-model="postId">
                
                <div class="p-6 flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Title (EN)</label>
                            <input name="title_en" type="text" x-model="postTitleEN" @blur="translateText($event.target.value, 'postTitleID')" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Title (ID)</label>
                            <input name="title_id" type="text" x-model="postTitleID" required class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Type Category</label>
                            <select name="category" x-model="postCategory" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                <option value="Blog">Blog Post / News</option>
                                <option value="Page">Static Info Page</option>
                                <option value="Tips">Tips & Guides</option>
                                <option value="Announcement">Announcement</option>
                                <option value="FAQ">FAQ</option>
                            </select>
                        </div>
                        
                        <div x-show="postCategory === 'FAQ'" style="display: none;">
                            <label class="block text-sm font-semibold text-on-surface mb-1">FAQ Topic</label>
                            <select name="faq_category" x-model="postFaqCategory" :required="postCategory === 'FAQ'" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                <option value="" disabled selected>Select Topic...</option>
                                <option value="User/Profile">User/Profile</option>
                                <option value="Property">Property</option>
                                <option value="Chat">Chat</option>
                                <option value="Subscription">Subscription</option>
                                <option value="Payment">Payment</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Content Body (EN)</label>
                            <textarea name="content_body_en" x-model="postBodyEN" @blur="translateText($event.target.value, 'postBodyID')" required rows="10" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none font-sans" placeholder="Write your content markup or text here..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Content Body (ID)</label>
                            <textarea name="content_body_id" x-model="postBodyID" required rows="10" class="w-full px-4 py-2 border border-outline-variant rounded bg-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none font-sans"></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-lowest sticky bottom-0">
                    <button type="button" @click="showEditor = false" class="px-6 py-2 border border-outline-variant text-on-surface-variant rounded font-semibold hover:bg-surface-container transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded font-semibold hover:opacity-90 transition">Save & Publish</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>