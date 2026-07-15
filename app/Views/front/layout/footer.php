<footer class="bg-primary text-on-primary font-body-md w-full mt-auto">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-4 md:px-10 py-8 w-full max-w-[1280px] mx-auto">
        <div class="flex flex-col gap-4">
            <span class="font-brand-text text-[24px] font-bold text-on-primary">HuniKita</span>
            <p class="text-on-primary/80 font-caption text-[12px] leading-relaxed max-w-xs">
                Premium real estate platform connecting discerning buyers with verified agents and exclusive properties.
            </p>
        </div>
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold">Company</h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/about-us') ?>">About Us</a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/contact-us') ?>">Contact</a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('search') ?>">Properties</a>
        </div>
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold">Legal</h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/terms-of-service') ?>">Terms of Service</a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/privacy-policy') ?>">Privacy Policy</a>
        </div>
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold">Preferences</h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm flex items-center gap-1" href="#">
                <span class="material-symbols-outlined text-[16px]">language</span> Language: ID/EN
            </a>
        </div>
    </div>
    <div class="border-t border-white/20 px-4 md:px-10 py-4">
        <div class="max-w-[1280px] mx-auto text-center md:text-left text-on-primary/60 text-[12px]">
            © <?= date('Y') ?> HuniKita Real Estate. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>