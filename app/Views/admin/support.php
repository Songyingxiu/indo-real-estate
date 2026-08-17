<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div class="max-w-3xl mx-auto mt-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-on-surface mb-2">Help & Support</h1>
        <p class="text-on-surface-variant">Find resources and contact information for the EstateAdmin portal.</p>
    </div>

    <div class="bg-surface border border-outline-variant rounded-lg p-8 shadow-sm mb-6">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-outline-variant">
            <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">mail</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg text-on-surface">Direct Support Chat</h3>
                <p class="text-on-surface-variant text-sm mb-3">Need a system adjustment or reporting a bug? Message the administration team directly. Your conversation will be saved in your Inbox.</p>
                
                <form id="supportChatForm" onsubmit="submitSupportChat(event, this)" novalidate class="flex flex-col gap-3 mt-4">
                    
                    <div id="support-global-error" class="hidden bg-[#c9302c] text-white p-3 font-bold items-center gap-2 rounded shadow-sm text-sm mb-1">
                        <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Subject <span class="text-[#c9302c]">*</span></label>
                        <input type="text" name="subject" id="support-subject" required oninput="clearSupportError('subject')" 
                               class="w-full px-3 py-2 bg-surface border border-outline-variant rounded focus:border-primary outline-none text-sm transition-colors">
                        
                        <div id="error-support-subject" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                            <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Message <span class="text-[#c9302c]">*</span></label>
                        <textarea name="message" id="support-message" rows="3" required oninput="clearSupportError('message')" 
                                  class="w-full px-3 py-2 bg-surface border border-outline-variant rounded focus:border-primary outline-none text-sm resize-y transition-colors" placeholder="Describe your issue..."></textarea>
                        
                        <div id="error-support-message" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                            <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                        </div>
                    </div>
                    
                    <input type="hidden" name="name" value="<?= esc(session()->get('first_name')) ?>">
                    <input type="hidden" name="email" value="<?= esc(session()->get('email')) ?>">

                    <div class="text-right mt-2">
                        <button type="submit" id="supportSubmitBtn" class="bg-primary text-on-primary font-bold px-6 py-2.5 rounded hover:bg-primary-container transition-colors shadow-sm inline-flex items-center gap-2 text-sm">
                            <span>Send to Admin</span>
                            <span class="material-symbols-outlined text-[16px]">send</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-surface-container-high text-on-surface rounded-full flex items-center justify-center shrink-0">
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

<div x-data="{ show: false }" @show-support-success.window="show = true" x-show="show" style="display:none;" class="fixed inset-0 z-[600] flex items-center justify-center p-4 bg-[#1a1c1e]/80 backdrop-blur-sm">
    <div @click.outside="show = false" class="bg-surface rounded-2xl shadow-2xl border border-outline-variant max-w-md w-full p-8 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-[#d3e3fd] text-primary flex items-center justify-center mb-4 shadow-inner">
            <span class="material-symbols-outlined text-[32px]">support_agent</span>
        </div>
        <h2 class="font-headline-lg text-2xl font-bold text-on-surface mb-2">Message Sent!</h2>
        <p class="text-on-surface-variant text-[15px] mb-6 leading-relaxed">
            Your support request has been routed to the administration team. You can view their replies in your Inbox.
        </p>
        <div class="flex gap-3 w-full">
            <button @click="show = false" class="flex-1 py-3 px-4 border border-outline-variant text-on-surface-variant rounded-lg font-bold hover:bg-surface-container transition-colors">Close</button>
            <a href="<?= base_url('admin/inquiries') ?>" class="flex-1 py-3 px-4 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container transition-colors shadow-md">Go to Inbox</a>
        </div>
    </div>
</div>

<script>
    function clearSupportError(field) {
        const input = document.getElementById('support-' + field);
        const errorDiv = document.getElementById('error-support-' + field);
        
        if (input) {
            input.classList.remove('border-[#c9302c]', 'focus:border-[#c9302c]', 'focus:ring-[#c9302c]', 'focus:ring-1', 'bg-[#fff8f8]');
            input.classList.add('border-outline-variant', 'focus:border-primary');
        }
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            errorDiv.classList.remove('flex');
        }
        
        const activeErrors = document.querySelectorAll('[id^="error-support-"]:not(.hidden)');
        if (activeErrors.length === 0) {
            document.getElementById('support-global-error').classList.add('hidden');
            document.getElementById('support-global-error').classList.remove('flex');
        }
    }

    function submitSupportChat(e, formElement) {
        e.preventDefault();
        const btn = document.getElementById('supportSubmitBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Sending...';
        btn.disabled = true;

        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');

        const formData = new FormData(formElement);
        formData.append(csrfName, csrfHash);

        fetch('<?= base_url('contact/submit') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (data.status === 'validation_error') {
                const globalErr = document.getElementById('support-global-error');
                globalErr.classList.remove('hidden');
                globalErr.classList.add('flex');
                
                for (const [field, message] of Object.entries(data.errors)) {
                    const input = document.getElementById('support-' + field);
                    const errorDiv = document.getElementById('error-support-' + field);
                    
                    if (input) {
                        input.classList.remove('border-outline-variant', 'focus:border-primary');
                        input.classList.add('border-[#c9302c]', 'focus:border-[#c9302c]', 'focus:ring-[#c9302c]', 'focus:ring-1', 'bg-[#fff8f8]');
                    }
                    if (errorDiv) {
                        errorDiv.querySelector('.error-text').textContent = message;
                        errorDiv.classList.remove('hidden');
                        errorDiv.classList.add('flex');
                    }
                }
            } else if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('show-support-success'));
                formElement.reset();
            } else {
                alert(data.message || 'Error sending message. Please try again.');
            }
        })
        .catch(error => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

<?= $this->endSection() ?>