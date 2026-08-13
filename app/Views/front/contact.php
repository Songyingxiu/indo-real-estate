<?= $this->include('front/layout/header') ?>

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
                <p class="text-on-surface-variant text-sm mb-4">Need help with your account or a listing? We are available Monday through Friday, 9am to 6pm (WIB)[cite: 1].</p>
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
                
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="bg-[#d3e3fd] text-[#041e49] p-4 rounded mb-6 border border-[#a8c7fa] flex items-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="bg-[#ffdad6] text-[#410002] p-4 rounded mb-6 border border-[#ffb4ab] flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('contact/submit') ?>" method="POST" class="flex flex-col gap-5">
                    <?= csrf_field() ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-on-surface mb-1">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?= old('name') ?>" required class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-on-surface mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?= old('email') ?>" required class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-on-surface mb-1">Subject *</label>
                        <input type="text" id="subject" name="subject" value="<?= old('subject') ?>" required class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-on-surface mb-1">Your Message *</label>
                        <textarea id="message" name="message" rows="6" required class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-y"><?= old('message') ?></textarea>
                    </div>

                    <div class="mt-2 text-right">
                        <button type="submit" class="bg-primary text-on-primary font-bold px-8 py-3 rounded hover:bg-primary-container transition-colors shadow-sm inline-flex items-center gap-2">
                            <span>Send Message</span>
                            <span class="material-symbols-outlined text-[18px]">send</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </div>
</main>

<?= $this->include('front/layout/footer') ?>