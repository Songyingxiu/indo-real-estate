<footer class="bg-primary text-on-primary font-body-md w-full mt-auto">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-4 md:px-10 py-8 w-full max-w-[1280px] mx-auto">
        <div class="flex flex-col gap-4">
            <span class="font-brand-text text-[24px] font-bold text-on-primary">HuniKita</span>
            <p class="text-on-primary/80 font-caption text-[12px] leading-relaxed max-w-xs">
                <?= lang('Front.ft_desc') ?>
            </p>
        </div>
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold"><?= lang('Front.ft_company') ?></h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/about-us') ?>"><?= lang('Front.nav_about') ?></a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/contact-us') ?>"><?= lang('Front.ft_contact') ?></a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('search/sale') ?>"><?= lang('Front.ft_properties') ?></a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('faq') ?>"><?= lang('Front.ft_faq') ?></a>
        </div>
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold"><?= lang('Front.ft_legal') ?></h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/terms-of-service') ?>"><?= lang('Front.ft_tos') ?></a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="<?= base_url('page/privacy-policy') ?>"><?= lang('Front.ft_privacy') ?></a>
        </div>
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold"><?= lang('Front.ft_preferences') ?></h4>
            <div class="flex items-center gap-2 text-on-primary/80 text-sm">
                <span class="material-symbols-outlined text-[16px]">language</span>
                <a href="<?= base_url('lang/en') ?>" class="<?= session()->get('locale') !== 'id' ? 'text-white font-bold' : 'hover:text-white transition-colors' ?>">EN</a>
                <span>|</span>
                <a href="<?= base_url('lang/id') ?>" class="<?= session()->get('locale') === 'id' ? 'text-white font-bold' : 'hover:text-white transition-colors' ?>">ID</a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/20 px-4 md:px-10 py-4">
        <div class="max-w-[1280px] mx-auto text-center md:text-left text-on-primary/60 text-[12px]">
            © <?= date('Y') ?> <?= lang('Front.ft_rights') ?>
        </div>
    </div>
</footer>
</body>
</html>