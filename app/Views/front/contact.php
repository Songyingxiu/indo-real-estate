<?= $this->include('front/layout/header') ?>

<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

<div class="bg-primary text-on-primary py-16 px-4 md:px-10 border-b-4 border-secondary-fixed">
    <div class="max-w-[1280px] mx-auto text-center">
        <h1 class="font-headline-xl text-[40px] md:text-[48px] font-bold mb-4">Get in Touch</h1>
        <p class="font-body-lg text-[18px] text-primary-fixed-dim max-w-2xl mx-auto">Have questions about a property, a subscription plan, or just want to say hello? Our team is here to help.</p>
    </div>
</div>

<main class="max-w-[1280px] mx-auto px-4 md:px-10 py-12 min-h-[60vh]">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Contact Info Side -->
        <aside class="lg:col-span-4 flex flex-col gap-6">
            <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl shadow-sm">
                <span class="material-symbols-outlined text-primary text-[32px] mb-4">support_agent</span>
                <h3 class="font-bold text-lg text-on-surface mb-2">Customer Support</h3>
                <p class="text-on-surface-variant text-sm mb-4">Need help with your account or a listing? We are available Monday through Friday, 9am to 6pm (WIB).</p>
                <a href="mailto:support@hunikita.com" class="font-semibold text-primary hover:underline block mb-1">support@hunikita.com</a>
                <a href="tel:+628112345678" class="font-semibold text-primary hover:underline block">+62 811 2345 678</a>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-xl shadow-sm">
                <span class="material-symbols-outlined text-primary text-[32px] mb-4">business</span>
                <h3 class="font-bold text-lg text-on-surface mb-2">Headquarters</h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    HuniKita Tower, 15th Floor<br>
                    Jl. Jend. Sudirman Kav. 52-53<br>
                    Jakarta Selatan, DKI Jakarta 12190<br>
                    Indonesia
                </p>
            </div>
        </aside>

        <!-- Contact Form Side -->
        <section class="lg:col-span-8">
            <div class="bg-surface border border-outline-variant rounded-xl p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-on-surface mb-6">Send us a Message</h2>
                
                <form id="globalContactForm" onsubmit="submitGlobalContact(event, this)" class="flex flex-col gap-5">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-on-surface mb-1">Full Name *</label>
                            <input type="text" id="name" name="name" required oninput="clearContactError('name')" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                            <span id="error-contact-name" class="text-error text-[12px] hidden mt-1"></span>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-on-surface mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" required oninput="clearContactError('email')" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                            <span id="error-contact-email" class="text-error text-[12px] hidden mt-1"></span>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-on-surface mb-1">Subject *</label>
                        <input type="text" id="subject" name="subject" required oninput="clearContactError('subject')" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        <span id="error-contact-subject" class="text-error text-[12px] hidden mt-1"></span>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-on-surface mb-1">Your Message *</label>
                        <textarea id="message" name="message" rows="6" required oninput="clearContactError('message')" class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y"></textarea>
                        <span id="error-contact-message" class="text-error text-[12px] hidden mt-1"></span>
                    </div>

                    <div class="mt-2 text-right">
                        <button type="submit" id="contactSubmitBtn" class="bg-primary text-on-primary font-bold px-8 py-3 rounded hover:bg-primary-container transition-colors shadow-sm inline-flex items-center gap-2">
                            <span>Send Message</span>
                            <span class="material-symbols-outlined text-[18px]">send</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </div>
</main>

<div x-data="{ show: false }" @show-contact-success.window="show = true" x-show="show" style="display:none;" class="fixed inset-0 z-[600] flex items-center justify-center p-4 bg-[#1a1c1e]/80 backdrop-blur-sm">
    <div @click.outside="show = false" class="bg-surface rounded-2xl shadow-2xl border border-outline-variant max-w-md w-full p-8 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-[#d3e3fd] text-primary flex items-center justify-center mb-4 shadow-inner">
            <span class="material-symbols-outlined text-[32px]">support_agent</span>
        </div>
        <h2 class="font-headline-lg text-2xl font-bold text-on-surface mb-2">Message Sent!</h2>
        <p class="text-on-surface-variant text-[15px] mb-6 leading-relaxed">
            Your inquiry has been successfully sent to our Support Team. You can view their replies directly in your Inbox.
        </p>
        <div class="flex gap-3 w-full">
            <button @click="show = false" class="flex-1 py-3 px-4 border border-outline-variant text-on-surface-variant rounded-lg font-bold hover:bg-surface-container transition-colors">Close</button>
            <a href="<?= base_url('user/inbox') ?>" class="flex-1 py-3 px-4 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container transition-colors shadow-md">Go to Inbox</a>
        </div>
    </div>
</div>

<?= $this->include('components/login_modal') ?>
<?= $this->include('front/layout/footer') ?>

<script>
    function clearContactError(fieldName) {
        const errorEl = document.getElementById('error-contact-' + fieldName);
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        }
    }

    function submitGlobalContact(e, formElement) {
        e.preventDefault();
        const btn = document.getElementById('contactSubmitBtn');
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
        .then(response => {
            if (response.status === 401) {
                window.pendingAuthAction = function() { submitGlobalContact({preventDefault:()=>{}}, formElement); };
                if(typeof openAuthModal === 'function') openAuthModal();
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if (data.status === 'validation_error') {
                for (const [field, message] of Object.entries(data.errors)) {
                    const errorEl = document.getElementById('error-contact-' + field);
                    if (errorEl) {
                        errorEl.textContent = message;
                        errorEl.classList.remove('hidden');
                    }
                }
            } else if (data.status === 'success') {
                window.dispatchEvent(new CustomEvent('show-contact-success'));
                formElement.reset();
            } else {
                alert(data.message || 'Error sending message. Please try again.');
            }
        })
        .catch(error => {
            if(error.message !== 'Unauthorized') {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }
</script>