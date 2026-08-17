<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div x-data="adminChatApp()" class="max-w-[1400px] mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in h-[calc(100vh-100px)] flex flex-col">
    <div class="flex justify-between items-center mb-6 shrink-0">
        <div>
            <h2 class="font-headline-lg text-[28px] font-bold text-on-surface">Client Messages</h2>
            <p class="text-on-surface-variant">Live chat with potential buyers and general support inquiries.</p>
        </div>
        <div id="ajaxAlert" class="hidden px-4 py-2 rounded border flex items-center gap-2 transition-all"></div>
    </div>

    <!-- Main Chat Interface -->
    <div class="flex-1 bg-surface shadow-sm rounded-xl border border-outline-variant overflow-hidden flex">
        
        <!-- Left Panel: Threads List -->
        <div class="w-1/3 border-r border-outline-variant flex flex-col bg-surface-container-lowest">
            <div class="p-4 border-b border-outline-variant bg-surface-container-low font-bold text-on-surface flex justify-between items-center">
                Active Conversations
                <?php if(!$canReply): ?>
                    <span class="bg-surface-container-highest text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider text-on-surface-variant">Read Only</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <?php if (!empty($threads)): ?>
                    <script> const initThreads = <?= json_encode($threads) ?>; </script>
                    <template x-for="thread in threads" :key="thread.id">
                        <div @click="loadThread(thread)" 
                             :class="activeThread?.id === thread.id ? 'bg-primary/10 border-l-4 border-primary' : 'border-l-4 border-transparent hover:bg-surface-container-low'"
                             class="p-4 border-b border-outline-variant cursor-pointer transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-on-surface text-[14px]" x-text="thread.first_name + ' ' + thread.last_name"></span>
                                <span class="text-[11px] text-on-surface-variant" x-text="formatDate(thread.created_at)"></span>
                            </div>
                            <div class="text-primary font-semibold text-[13px] truncate mb-2" x-text="thread.property_title || 'General Support Inquiry'"></div>
                            
                            <!-- Status Dropdown -->
                            <select @click.stop @change="updateThreadStatus(thread.id, $event.target.value)" class="w-full px-2 py-1 rounded bg-surface border border-outline-variant text-[12px] font-semibold cursor-pointer focus:ring-1 focus:ring-primary outline-none">
                                <option value="Pending" :selected="thread.status == 'Pending'">Pending</option>
                                <option value="In Discussion" :selected="thread.status == 'In Discussion'">In Discussion</option>
                                <option value="Negotiating" :selected="thread.status == 'Negotiating'">Negotiating</option>
                                <option value="Under Contract" :selected="thread.status == 'Under Contract'">Under Contract</option>
                                <option value="Replied" :selected="thread.status == 'Replied'">Replied</option>
                                <option value="Closed" :selected="thread.status == 'Closed'">Closed</option>
                                <option value="Cancelled" :selected="thread.status == 'Cancelled'">Cancelled</option>
                            </select>
                        </div>
                    </template>
                <?php else: ?>
                    <script> const initThreads = []; </script>
                    <div class="p-8 text-center text-on-surface-variant text-sm">No messages yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Panel: Chat Area -->
        <div class="w-2/3 flex flex-col bg-surface relative">
            
            <template x-if="!activeThread">
                <div class="flex-1 flex flex-col items-center justify-center opacity-40">
                    <span class="material-symbols-outlined text-[64px] mb-4">forum</span>
                    <p class="font-bold text-lg text-on-surface">Select a conversation to start chatting</p>
                </div>
            </template>

            <template x-if="activeThread">
                <div class="flex flex-col h-full w-full">
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center shadow-sm z-10">
                        <div>
                            <h3 class="font-bold text-on-surface text-[16px]" x-text="activeThread.first_name + ' ' + activeThread.last_name"></h3>
                            <template x-if="activeThread.property_id">
                                <a :href="'<?= base_url('property/') ?>' + activeThread.property_id" target="_blank" class="text-[13px] text-primary hover:underline flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">link</span> <span x-text="activeThread.property_title"></span>
                                </a>
                            </template>
                            <template x-if="!activeThread.property_id">
                                <span class="text-[13px] text-primary flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[14px]">support_agent</span> General Support Inquiry
                                </span>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <a :href="'https://wa.me/' + (activeThread.phone_number || '').replace(/[^0-9]/g, '')" target="_blank" class="bg-surface-container-high px-3 py-1.5 rounded text-[13px] font-bold text-on-surface flex items-center gap-2 hover:bg-outline-variant/30 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">call</span> Direct Chat
                            </a>
                            <a :href="'mailto:' + activeThread.email" class="bg-surface-container-high px-3 py-1.5 rounded text-[13px] font-bold text-on-surface flex items-center gap-2 hover:bg-outline-variant/30 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">mail</span> Email Client
                            </a>
                        </div>
                    </div>

                    <!-- Messages Window -->
                    <div id="chatBox" class="flex-1 overflow-y-auto p-6 flex flex-col gap-4 bg-[#f8fafd]">
                        <template x-for="msg in messages" :key="msg.id">
                            <div :class="msg.sender_id == myId ? 'self-end' : 'self-start'" class="max-w-[75%]">
                                <div class="text-[11px] text-on-surface-variant mb-1 mx-1" :class="msg.sender_id == myId ? 'text-right' : 'text-left'">
                                    <span x-text="msg.sender_id == myId ? 'You' : msg.first_name"></span> &bull; <span x-text="formatDate(msg.created_at)"></span>
                                </div>
                                <div :class="msg.sender_id == myId ? 'bg-primary text-on-primary rounded-l-2xl rounded-tr-2xl' : 'bg-surface border border-outline-variant text-on-surface rounded-r-2xl rounded-tl-2xl'" 
                                     class="px-4 py-3 text-[14px] shadow-sm whitespace-pre-wrap" x-text="msg.message">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Area with Subscription Gate -->
                    <div class="p-4 border-t border-outline-variant bg-surface-container-lowest relative">
                        
                        <?php if(!$canReply): ?>
                            <div class="absolute inset-0 bg-surface/80 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center border-t border-outline-variant">
                                <div class="bg-surface border border-outline-variant p-4 rounded-lg shadow-lg text-center max-w-sm">
                                    <span class="material-symbols-outlined text-primary mb-2 text-3xl">lock</span>
                                    <p class="text-[15px] font-bold text-on-surface mb-1">2-Way Live Chat is a Premium Feature</p>
                                    <p class="text-[13px] text-on-surface-variant mb-4">You have received this lead, and an auto-reply was sent to them! Upgrade your plan to reply natively in-app.</p>
                                    <a href="<?= base_url('admin/pricing') ?>" class="inline-block bg-primary text-on-primary px-5 py-2 rounded text-[13px] font-bold hover:opacity-90 shadow-sm">Upgrade Plan</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form @submit.prevent="sendReply" class="flex gap-3">
                            <textarea x-model="replyText" required rows="2" class="flex-1 px-4 py-3 bg-surface border border-outline-variant rounded-xl outline-none focus:border-primary resize-none text-[14px]" placeholder="Type your reply to the client..." @keydown.enter.prevent="sendReply"></textarea>
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

<script>
    function adminChatApp() {
        return {
            threads: initThreads,
            activeThread: null,
            messages: [],
            myId: <?= session()->get('id') ?? session()->get('user_id') ?? 0 ?>,
            replyText: '',
            isLoading: false,

            loadThread(thread) {
                this.activeThread = thread;
                this.messages = [];
                fetch('<?= base_url('admin/inquiries/thread/') ?>' + thread.id)
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
                
                fetch('<?= base_url('admin/inquiries/reply') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfName]: csrfHash
                    },
                    body: JSON.stringify({
                        parent_id: this.activeThread.id,
                        property_id: this.activeThread.property_id,
                        receiver_id: this.activeThread.sender_id, 
                        message: this.replyText
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.isLoading = false;
                    if(data.status === 'success') {
                        this.messages.push(data.message_data);
                        this.replyText = '';
                        this.activeThread.status = 'Replied'; 
                        setTimeout(this.scrollToBottom, 100);
                    } else if (data.status === 'error') {
                        alert(data.message || 'An error occurred.');
                    }
                }).catch(() => this.isLoading = false);
            },

            updateThreadStatus(id, newStatus) {
                const alertBox = document.getElementById('ajaxAlert');
                const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
                const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');
                
                fetch('<?= base_url('admin/inquiries/update-status/') ?>' + id, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', [csrfName]: csrfHash },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(res => res.json())
                .then(data => {
                    alertBox.classList.remove('hidden', 'bg-error-container', 'text-on-error-container', 'bg-[#d3e3fd]', 'text-[#041e49]');
                    if(data.status === 'success') {
                        alertBox.classList.add('bg-[#d3e3fd]', 'text-[#041e49]');
                        alertBox.innerHTML = `<span class="material-symbols-outlined mt-0.5 text-[18px]">check_circle</span> Thread status updated.`;
                    } else {
                        alertBox.classList.add('bg-error-container', 'text-on-error-container');
                        alertBox.innerHTML = `<span class="material-symbols-outlined mt-0.5 text-[18px]">warning</span> Failed to update.`;
                    }
                    setTimeout(() => { alertBox.classList.add('hidden'); }, 3000);
                });
            },

            scrollToBottom() {
                const box = document.getElementById('chatBox');
                if(box) box.scrollTop = box.scrollHeight;
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            }
        }
    }
</script>
<?= $this->endSection() ?>