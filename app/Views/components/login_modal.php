<div x-data="{ showAuthModal: false, authTab: 'login' }" 
     @open-auth-modal.window="showAuthModal = true; document.getElementById('modalAuthError').style.display='none';"
     @close-auth-modal.window="showAuthModal = false"
     x-show="showAuthModal" 
     class="fixed inset-0 z-[500] flex items-center justify-center p-4 bg-[#181c1e]/80 backdrop-blur-sm" 
     style="display: none;"
     x-transition.opacity>
    
    <div @click.outside="showAuthModal = false" 
         x-show="showAuthModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="bg-surface-container-lowest w-full max-w-md rounded-2xl border border-outline-variant shadow-2xl relative flex flex-col overflow-hidden">
        
        <button type="button" @click="showAuthModal = false" aria-label="Close modal" class="absolute top-4 right-4 z-20 bg-surface-container-high text-on-surface-variant hover:text-error hover:bg-error-container transition-colors p-2 rounded-full flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <div class="p-8 flex flex-col gap-6 mt-2">
            <div class="flex items-center gap-2 mb-2 justify-center">
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-on-primary text-[20px]">real_estate_agent</span>
                </div>
                <span class="font-brand-text text-[22px] font-bold text-primary">HuniKita</span>
            </div>

            <!-- Tab Toggles -->
            <div class="flex bg-surface-container-high p-1 rounded-lg">
                <button @click="authTab = 'login'; document.getElementById('modalAuthError').style.display='none';" :class="authTab === 'login' ? 'bg-surface text-primary shadow-sm' : 'text-on-surface-variant'" class="flex-1 py-2 text-[14px] font-bold rounded-md transition-all">Sign In</button>
                <button @click="authTab = 'register'; document.getElementById('modalAuthError').style.display='none';" :class="authTab === 'register' ? 'bg-surface text-primary shadow-sm' : 'text-on-surface-variant'" class="flex-1 py-2 text-[14px] font-bold rounded-md transition-all">Create Account</button>
            </div>

            <div id="modalAuthError" class="bg-error-container text-on-error-container p-3 rounded-lg text-[13px] font-medium" style="display:none;"></div>

            <!-- LOGIN FORM -->
            <form x-show="authTab === 'login'" id="ajaxLoginForm" onsubmit="handleAuthSubmit(event, '<?= base_url('login') ?>')" class="flex flex-col gap-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                
                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-[13px] font-bold text-on-surface">Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                        <input class="w-full pl-10 pr-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" name="email" placeholder="name@example.com" required type="email">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center">
                        <label class="font-label-md text-[13px] font-bold text-on-surface">Password</label>
                        <a class="font-label-md text-[12px] font-bold text-primary hover:underline" href="<?= base_url('forgot-password') ?>">Forgot?</a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                        <input class="w-full pl-10 pr-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" name="password" placeholder="••••••••" required type="password">
                    </div>
                </div>

                <button class="w-full bg-primary-container text-on-primary font-label-md text-[15px] font-bold py-3 rounded-lg hover:bg-primary transition-colors flex items-center justify-center gap-2 mt-2 shadow-sm disabled:opacity-50" type="submit">
                    <span>Sign In</span>
                    <span class="material-symbols-outlined text-[18px]">login</span>
                </button>
            </form>

            <!-- REGISTER FORM -->
            <form x-show="authTab === 'register'" style="display:none;" id="ajaxRegisterForm" onsubmit="handleAuthSubmit(event, '<?= base_url('register') ?>')" class="flex flex-col gap-4">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-[13px] font-bold text-on-surface">First Name</label>
                        <input class="w-full px-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 transition-all" name="first_name" required type="text">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-[13px] font-bold text-on-surface">Last Name</label>
                        <input class="w-full px-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 transition-all" name="last_name" required type="text">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-[13px] font-bold text-on-surface">Email Address</label>
                    <input class="w-full px-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 transition-all" name="email" required type="email">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-[13px] font-bold text-on-surface">Password</label>
                        <input class="w-full px-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 transition-all" name="password" required type="password">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-label-md text-[13px] font-bold text-on-surface">Confirm</label>
                        <input class="w-full px-3 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 transition-all" name="password_confirm" required type="password">
                    </div>
                </div>

                <input type="hidden" name="role" value="buyer">
                <input type="hidden" name="phone_number" value="08000000000">

                <button class="w-full bg-primary-container text-on-primary font-label-md text-[15px] font-bold py-3 rounded-lg hover:bg-primary transition-colors flex items-center justify-center gap-2 mt-2 shadow-sm disabled:opacity-50" type="submit">
                    <span>Create Account</span>
                </button>
            </form>

            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-outline-variant"></div>
                <span class="flex-shrink-0 mx-4 font-caption text-[12px] text-outline font-bold uppercase tracking-wider">or</span>
                <div class="flex-grow border-t border-outline-variant"></div>
            </div>

            <button type="button" id="googleLoginBtn" onclick="handleGoogleLoginMock()" class="w-full bg-surface border border-outline-variant text-on-surface-variant font-label-md text-[14px] font-bold py-3 rounded-lg hover:bg-surface-container-low hover:text-on-surface transition-colors flex items-center justify-center gap-3 shadow-sm disabled:opacity-50">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path></svg>
                Continue with Google
            </button>
        </div>
    </div>
</div>

<script>
    window.pendingAuthAction = null;

    function openAuthModal() {
        window.dispatchEvent(new CustomEvent('open-auth-modal'));
    }

    function closeAuthModal() {
        window.dispatchEvent(new CustomEvent('close-auth-modal'));
    }

    function handleAuthSubmit(e, targetUrl) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        const errorDiv = document.getElementById('modalAuthError');
        
        submitBtn.innerHTML = 'Processing...';
        submitBtn.disabled = true;
        errorDiv.style.display = 'none';

        const formData = new FormData(form);
        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');
        formData.set(csrfName, csrfHash);

        fetch(targetUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(async response => {
            const isJson = response.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await response.json() : null;
            if (!response.ok && !data) throw new Error('Server returned an unexpected response.');
            return data;
        })
        .then(data => {
            if (data && data.status === 'success') {
                closeAuthModal();
                processPendingAction();
            } else {
                errorDiv.innerHTML = data?.message || 'Authentication failed. Please try again.';
                errorDiv.style.display = 'block';
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            errorDiv.innerHTML = error.message;
            errorDiv.style.display = 'block';
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    function handleGoogleLoginMock() {
        const btn = document.getElementById('googleLoginBtn');
        const originalText = btn.innerHTML;
        const errorDiv = document.getElementById('modalAuthError');
        
        btn.innerHTML = 'Connecting...';
        btn.disabled = true;
        errorDiv.style.display = 'none';

        const csrfName = document.querySelector('meta[name="csrf_token_name"]')?.getAttribute('content') || 'csrf_test_name';
        const csrfHash = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || document.querySelector('meta[name="csrf_token"]')?.getAttribute('content');

        const payload = {
            email: 'demo.user@gmail.com',
            uid: 'google-oauth-123456789',
            displayName: 'Demo User',
            [csrfName]: csrfHash
        };

        fetch('<?= base_url('auth/google-login') ?>', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfName]: csrfHash 
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            const isJson = response.headers.get('content-type')?.includes('application/json');
            if (isJson) return response.json();
            throw new Error('Server error.');
        })
        .then(data => {
            if (data && data.status === 'success') {
                closeAuthModal();
                processPendingAction();
            } else {
                errorDiv.innerHTML = data?.message || 'Google Login failed.';
                errorDiv.style.display = 'block';
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(err => {
            errorDiv.innerHTML = err.message;
            errorDiv.style.display = 'block';
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function processPendingAction() {
        if (typeof window.pendingAuthAction === 'function') {
            window.pendingAuthAction();
            window.pendingAuthAction = null; 
        } else {
            // If there's no pending action (like they just clicked "Sign in to Contact Agent")
            // refresh the page to show the logged-in state!
            window.location.reload(); 
        }
    }
</script>