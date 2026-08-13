<?= $this->include('front/layout/header') ?>

<main class="flex-grow max-w-[1280px] w-full mx-auto px-4 md:px-10 py-12 flex flex-col md:flex-row gap-8 items-start min-h-[75vh]" x-data="userChatApp()">
    
    <aside class="w-full md:w-64 flex-shrink-0 sticky top-28 hidden md:block">
        <h2 class="font-headline-lg text-[24px] font-bold text-primary mb-6"><?= lang('Front.inbox_workspace') ?></h2>
        <nav class="flex flex-col gap-2">
            <a class="flex items-center justify-between px-4 py-3 rounded-lg bg-primary-container text-white font-label-md text-[14px] font-semibold transition-all shadow-sm" href="<?= base_url('user/inbox') ?>">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">forum</span> <?= lang('Front.inbox_inquiries') ?>
                </div>
                <?php if (($GLOBALS['unread_count'] ?? 0) > 0): ?>
                    <span class="bg-error text-on-error text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $GLOBALS['unread_count'] ?></span>
                <?php endif; ?>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-primary font-label-md text-[14px] font-semibold transition-all" href="<?= base_url('user/profile') ?>">
                <span class="material-symbols-outlined">settings</span> <?= lang('Front.inbox_settings') ?>
            </a>
        </nav>
    </aside>

    <div class="w-full flex flex-col h-[650px]">
        <header class="flex flex-col gap-1 mb-6 shrink-0">
            <h1 class="font-headline-lg text-[28px] font-bold text-on-surface"><?= lang('Front.inbox_inquiries') ?></h1>
            <p class="font-body-md text-[16px] text-on-surface-variant"><?= lang('Front.inbox_subtitle') ?></p>
        </header>

        <!-- Split Chat Interface -->
        <div class="flex-1 bg-surface border border-outline-variant rounded-xl overflow-hidden flex shadow-sm">
            
            <!-- Left Panel -->
            <div class="w-1/3 border-r border-outline-variant flex flex-col bg-surface-container-lowest">
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <?php if (!empty($threads)): ?>
                        <script> const initUserThreads = <?= json_encode($threads) ?>; </script>
                        <template x-for="thread in threads" :key="thread.inquiry_id">
                            <div @click="loadThread(thread)" 
                                 :class="activeThread?.inquiry_id === thread.inquiry_id ? 'bg-primary/5 border-l-4 border-primary' : 'border-l-4 border-transparent hover:bg-surface-container-low'"
                                 class="p-4 border-b border-outline-variant cursor-pointer transition-colors relative">
                                <div class="font-bold text-primary text-[14px] truncate mb-1 pr-3" x-text="thread.property_title"></div>
                                <div class="text-[12px] text-on-surface-variant mb-2"><?= lang('Front.inbox_agent') ?> <span x-text="thread.first_name + ' ' + thread.last_name"></span></div>
                                <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-surface-container-high text-on-surface-variant border border-outline-variant" x-text="thread.status"></span>
                                
                                <template x-if="thread.status == 'Replied'">
                                    <span class="absolute top-5 right-4 w-2.5 h-2.5 bg-error rounded-full shadow-sm"></span>
                                </template>
                            </div>
                        </template>
                    <?php else: ?>
                        <script> const initUserThreads = []; </script>
                        <div class="p-8 text-center text-on-surface-variant text-sm"><?= lang('Front.inbox_no_inquiries') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="w-2/3 flex flex-col bg-surface relative">
                <template x-if="!activeThread">
                    <div class="flex-1 flex flex-col items-center justify-center opacity-40">
                        <span class="material-symbols-outlined text-[64px] mb-4">chat</span>
                        <p class="font-bold text-lg text-on-surface"><?= lang('Front.inbox_select') ?></p>
                    </div>
                </template>

                <template x-if="activeThread">
                    <div class="flex flex-col h-full w-full">
                        <div class="p-4 border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center shadow-sm z-10">
                            <div>
                                <a :href="'<?= base_url('property/') ?>' + activeThread.property_id" target="_blank" class="font-bold text-[16px] text-primary hover:underline flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">home</span> <span x-text="activeThread.property_title"></span>
                                </a>
                                <p class="text-[13px] text-on-surface-variant"><?= lang('Front.inbox_agent') ?> <span x-text="activeThread.first_name + ' ' + activeThread.last_name"></span></p>
                            </div>
                        </div>

                        <div id="userChatBox" class="flex-1 overflow-y-auto p-6 flex flex-col gap-4 bg-[#f8fafd]">
                            <template x-for="msg in messages" :key="msg.inquiry_id">
                                <div :class="msg.sender_id == myId ? 'self-end' : 'self-start'" class="max-w-[75%]">
                                    <div class="text-[11px] text-on-surface-variant mb-1 mx-1" :class="msg.sender_id == myId ? 'text-right' : 'text-left'">
                                        <span x-text="msg.sender_id == myId ? '<?= lang('Front.inbox_you') ?>' : msg.first_name"></span> &bull; <span x-text="formatDate(msg.created_at)"></span>
                                    </div>
                                    <div :class="msg.sender_id == myId ? 'bg-primary text-on-primary rounded-l-2xl rounded-tr-2xl' : 'bg-surface border border-outline-variant text-on-surface rounded-r-2xl rounded-tl-2xl'" 
                                         class="px-4 py-3 text-[14px] shadow-sm whitespace-pre-wrap" x-text="msg.message">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                            <form @submit.prevent="sendReply" class="flex gap-3">
                                <textarea x-model="replyText" required rows="2" class="flex-1 px-4 py-3 bg-surface border border-outline-variant rounded-xl outline-none focus:border-primary resize-none text-[14px]" placeholder="<?= lang('Front.inbox_write_reply') ?>" @keydown.enter.prevent="sendReply"></textarea>
                                <button type="submit" class="bg-primary text-on-primary h-12 w-12 rounded-full flex items-center justify-center self-end hover:bg-primary-container transition-colors disabled:opacity-50" :disabled="!replyText.trim() || isLoading">
                                    <span class="material-symbols-outlined" x-show="!isLoading">send</span>
                                    <span class="material-symbols-outlined animate-spin" x-show="isLoading">progress_activity</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</main>

<script>
    function userChatApp() {
        return {
            threads: initUserThreads,
            activeThread: null,
            messages: [],
            myId: <?= session()->get('id') ?>,
            replyText: '',
            isLoading: false,

            loadThread(thread) {
                this.activeThread = thread;
                this.messages = [];
                if (this.activeThread.status === 'Replied') {
                    this.activeThread.status = 'In Discussion';
                }

                fetch('<?= base_url('user/inbox/thread/') ?>' + thread.inquiry_id)
                    .then(res => res.json())
                    .then(data => {
                        this.messages = data;
                        setTimeout(this.scrollToBottom, 100);
                    });
            },

            sendReply() {
                if(!this.replyText.trim()) return;
                this.isLoading = true;

                const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
                const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');
                
                fetch('<?= base_url('user/inbox/reply') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', [csrfName]: csrfHash },
                    body: JSON.stringify({
                        parent_id: this.activeThread.inquiry_id,
                        property_id: this.activeThread.property_id,
                        receiver_id: this.activeThread.receiver_id, // The agent
                        message: this.replyText
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.isLoading = false;
                    if(data.status === 'success') {
                        this.messages.push(data.message_data);
                        this.replyText = '';
                        this.activeThread.status = 'Pending';
                        setTimeout(this.scrollToBottom, 100);
                    }
                }).catch(() => this.isLoading = false);
            },

            scrollToBottom() {
                const box = document.getElementById('userChatBox');
                if(box) box.scrollTop = box.scrollHeight;
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('<?= session()->get('locale') == 'id' ? 'id-ID' : 'en-US' ?>', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            }
        }
    }
</script>
<?= $this->include('front/layout/footer') ?>