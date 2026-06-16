<div id="auth-modal-overlay" class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300" style="background-color: rgba(24, 28, 30, 0.4); backdrop-filter: blur(4px); display: none;">
    
    <div aria-labelledby="modal-title" aria-modal="true" class="bg-surface-container-lowest w-full max-w-md rounded-lg border border-outline-variant shadow-2xl relative overflow-hidden flex flex-col transform transition-transform duration-300 scale-95 opacity-0" role="dialog" id="auth-modal-box">
        
        <button onclick="closeAuthModal()" aria-label="Close modal" class="absolute top-4 right-4 text-on-surface-variant hover:text-on-surface transition-colors p-2 rounded-full hover:bg-surface-container-low">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <div class="p-8 flex flex-col gap-6">
            <div class="flex items-center gap-2 mb-2 justify-center">
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[20px]">real_estate_agent</span>
                </div>
                <span class="font-brand-text text-[20px] font-bold text-primary">App Name</span>
            </div>

            <div class="text-center">
                <h2 class="font-headline-lg text-[24px] font-bold text-on-surface mb-2" id="modal-title">Log in to Contact Agent</h2>
                <p class="font-body-md text-[14px] text-on-surface-variant">Join our trusted community to connect with verified agents and save your favorite homes.</p>
            </div>

            <form action="<?= base_url('login') ?>" method="POST" class="flex flex-col gap-4 mt-2">
                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-[13px] font-semibold text-on-surface" for="modal-email">Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                        <input class="w-full pl-10 pr-3 py-2 bg-surface-container-lowest border border-outline-variant rounded font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all" id="modal-email" name="email" placeholder="name@example.com" required type="email">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center">
                        <label class="font-label-md text-[13px] font-semibold text-on-surface" for="modal-password">Password</label>
                        <a class="font-label-md text-[13px] font-semibold text-primary hover:underline" href="#">Forgot?</a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                        <input class="w-full pl-10 pr-3 py-2 bg-surface-container-lowest border border-outline-variant rounded font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all" id="modal-password" name="password" placeholder="••••••••" required type="password">
                    </div>
                </div>

                <button class="w-full bg-primary-container text-on-primary font-label-md text-[15px] font-semibold py-3 rounded hover:bg-primary transition-colors flex items-center justify-center gap-2 mt-2" type="submit">
                    <span>Continue</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>

                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-outline-variant"></div>
                    <span class="flex-shrink-0 mx-4 font-caption text-[12px] text-outline">or</span>
                    <div class="flex-grow border-t border-outline-variant"></div>
                </div>

                <a href="<?= base_url('login') ?>" class="w-full bg-transparent border border-primary-container text-primary-container font-label-md text-[15px] font-semibold py-3 rounded hover:bg-surface-container-low transition-colors flex items-center justify-center text-center">
                    Create an account
                </a>
            </form>

            <div class="mt-2 flex justify-center items-center gap-2 text-on-surface-variant bg-surface-container-low p-2 rounded-lg border border-outline-variant">
                <span class="material-symbols-outlined text-[16px] text-tertiary-container">verified_user</span>
                <span class="font-caption text-[12px]">Secure, encrypted connection</span>
            </div>
        </div>
    </div>
</div>

<script>
    function openAuthModal() {
        const overlay = document.getElementById('auth-modal-overlay');
        const box = document.getElementById('auth-modal-box');
        overlay.style.display = 'flex';
        // Tiny timeout to allow display:flex to apply before adding opacity classes for transition
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAuthModal() {
        const overlay = document.getElementById('auth-modal-overlay');
        const box = document.getElementById('auth-modal-box');
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }
</script>