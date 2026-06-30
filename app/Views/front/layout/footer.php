<footer class="bg-primary text-on-primary font-body-md w-full mt-auto">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-4 md:px-10 py-8 w-full max-w-[1280px] mx-auto">
        <!-- Brand Column -->
        <div class="flex flex-col gap-4">
            <span class="font-brand-text text-[24px] font-bold text-on-primary">Lunera</span>
            <p class="text-on-primary/80 font-caption text-[12px] leading-relaxed max-w-xs">
                Premium real estate platform connecting discerning buyers with verified agents and exclusive properties.
            </p>
        </div>
        <!-- Links Column 1 -->
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold">Company</h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="#">About Us</a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="#">Contact</a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="#">Locations</a>
        </div>
        <!-- Links Column 2 -->
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold">Legal</h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="#">Terms of Service</a>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm" href="#">Privacy Policy</a>
        </div>
        <!-- Settings Column -->
        <div class="flex flex-col gap-3">
            <h4 class="font-label-md text-[14px] font-bold">Preferences</h4>
            <a class="text-on-primary/80 hover:text-white transition-colors text-sm flex items-center gap-1" href="#">
                <span class="material-symbols-outlined text-[16px]">language</span> Language: ID/EN
            </a>
        </div>
    </div>
    <!-- Bottom Bar -->
    <div class="border-t border-white/20 px-4 md:px-10 py-4">
        <div class="max-w-[1280px] mx-auto text-center md:text-left text-on-primary/60 text-[12px]">
            © <?= date('Y') ?> Lunera Real Estate. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>