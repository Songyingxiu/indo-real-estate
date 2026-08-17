<?= $this->extend('admin/layout/master') ?>
<?= $this->section('content') ?>

<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div class="max-w-4xl mx-auto mt-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-on-surface mb-2">Help & Support</h1>
        <p class="text-on-surface-variant">Find resources and contact information for the EstateAdmin portal.</p>
    </div>

    <!-- 1. Direct Support Chat (Hidden for Admins) -->
    <?php if (session()->get('role_id') != 4): ?>
        <div class="bg-surface border border-outline-variant rounded-lg p-8 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row items-start gap-4">
                <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center shrink-0 mt-1">
                    <span class="material-symbols-outlined text-2xl">mail</span>
                </div>
                <div class="flex-1 w-full">
                    <h3 class="font-bold text-lg text-on-surface">Direct Support Chat</h3>
                    <p class="text-on-surface-variant text-sm mb-5">Need a system adjustment or reporting a bug? Message the administration team directly. Your conversation will be saved in your Inbox.</p>
                    
                    <form id="supportChatForm" onsubmit="submitSupportChat(event, this)" novalidate class="flex flex-col gap-4">
                        
                        <div id="support-global-error" class="hidden bg-[#c9302c] text-white p-3 font-bold items-center gap-2 rounded shadow-sm text-sm">
                            <span class="material-symbols-outlined text-[20px]">warning</span> There are items that require your attention
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Email Address <span class="text-[#c9302c]">*</span></label>
                            <input type="email" name="email" id="support-email" required value="<?= esc(session()->get('email')) ?>" oninput="clearSupportError('email')" 
                                   class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded focus:border-primary outline-none text-sm transition-colors">
                            
                            <div id="error-support-email" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Subject <span class="text-[#c9302c]">*</span></label>
                            <input type="text" name="subject" id="support-subject" required oninput="clearSupportError('subject')" 
                                   class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded focus:border-primary outline-none text-sm transition-colors">
                            
                            <div id="error-support-subject" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Message <span class="text-[#c9302c]">*</span></label>
                            <textarea name="message" id="support-message" rows="4" required oninput="clearSupportError('message')" 
                                      class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded focus:border-primary outline-none text-sm resize-y transition-colors" placeholder="Describe your issue..."></textarea>
                            
                            <div id="error-support-message" class="hidden bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                                <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <span class="error-text"></span>
                            </div>
                        </div>
                        
                        <input type="hidden" name="name" value="<?= esc(session()->get('first_name')) ?>">

                        <div class="text-right mt-2">
                            <button type="submit" id="supportSubmitBtn" class="bg-primary text-on-primary font-bold px-8 py-2.5 rounded hover:bg-primary-container transition-colors shadow-sm inline-flex items-center gap-2 text-sm">
                                <span>Send to Admin</span>
                                <span class="material-symbols-outlined text-[16px]">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Admin Notice Block -->
        <div class="bg-surface-container-low border border-outline-variant rounded-lg p-8 shadow-sm mb-6 text-center">
            <span class="material-symbols-outlined text-[48px] text-outline-variant mb-2">admin_panel_settings</span>
            <h3 class="font-bold text-lg text-on-surface mb-1">Administrator Mode</h3>
            <p class="text-on-surface-variant text-sm">As an administrator, you receive support inquiries directly in your <a href="<?= base_url('admin/inquiries') ?>" class="text-primary font-semibold hover:underline">Inbox</a>. The ticketing system is disabled for your role.</p>
        </div>
    <?php endif; ?>

    <!-- 2. Headquarters & Contact Info -->
    <div class="bg-surface border border-outline-variant rounded-lg p-8 shadow-sm mb-6">
        <div class="flex flex-col md:flex-row items-start gap-4">
            <div class="w-12 h-12 bg-surface-container-high text-on-surface rounded-full flex items-center justify-center shrink-0 mt-1">
                <span class="material-symbols-outlined text-2xl">business</span>
            </div>
            <div class="flex-1 w-full">
                <h3 class="font-bold text-lg text-on-surface mb-4">Headquarters & Contact Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant">
                        <p class="text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">location_on</span> Office Address
                        </p>
                        <p class="text-on-surface-variant text-sm leading-relaxed">
                            HuniKita Tower, 15th Floor<br>
                            Jl. Jend. Sudirman Kav. 52-53<br>
                            Jakarta Selatan, DKI Jakarta 12190<br>
                            Indonesia
                        </p>
                    </div>
                    <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant">
                        <p class="text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">contact_support</span> Direct Lines
                        </p>
                        <div class="flex flex-col gap-3 mt-1">
                            <a href="mailto:support@hunikita.com" class="text-sm text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">email</span> support@hunikita.com
                            </a>
                            <a href="tel:+628112345678" class="text-sm text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">call</span> +62 811 2345 678
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. System Documentation -->
    <div class="bg-surface border border-outline-variant rounded-lg p-8 shadow-sm">
        <div class="flex flex-col md:flex-row items-start gap-4">
            <div class="w-12 h-12 bg-surface-container-high text-on-surface rounded-full flex items-center justify-center shrink-0 mt-1">
                <span class="material-symbols-outlined text-2xl">menu_book</span>
            </div>
            <div>
                <h3 class="font-bold text-lg text-on-surface">System Documentation</h3>
                <p class="text-on-surface-variant text-sm mt-1 mb-4">Review the platform guidelines, component specifications, and SQA testing standards.</p>
                <button class="bg-surface-container-lowest border border-outline-variant text-primary font-semibold hover:bg-surface-container-low transition-colors text-sm px-5 py-2 rounded inline-flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span> Download PDF Guide
                </button>
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
            Your support request has been routed to the administration team. You can view their replies directly in your Inbox.
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