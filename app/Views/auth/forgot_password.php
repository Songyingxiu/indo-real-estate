<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - HuniKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-2">Forgot Password</h2>
        <p class="text-sm text-slate-600 text-center mb-6">Enter your email and we'll send you a password reset link.</p>

        <?php if (session()->has('errors') || session()->getFlashdata('error')): ?>
            <div class="bg-[#c9302c] text-white p-3 font-bold flex items-center gap-2 mb-4 rounded shadow-sm text-sm">
                <span class="material-symbols-outlined text-[18px]">warning</span> 
                <?= session()->getFlashdata('error') ?: 'There are items that require your attention' ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('forgot-password') ?>" method="POST" novalidate>
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-[#c9302c]">*</span></label>
                <?php $err = session('errors.email'); ?>
                <input type="email" name="email" value="<?= old('email') ?>" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 <?= $err ? 'border-[#c9302c] focus:border-[#c9302c] focus:ring-[#c9302c] bg-[#fff8f8]' : 'border-gray-300 focus:ring-blue-500' ?>">
                <?php if ($err): ?>
                    <div class="bg-[#f2dede] text-[#a94442] text-[13px] p-2 mt-1 flex items-start gap-1 rounded-sm shadow-sm border border-[#ebcccc]">
                        <span class="material-symbols-outlined text-[16px] mt-0.5">warning</span> <?= esc($err) ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 font-semibold transition">Send Reset Link</button>
        </form>

        <div class="mt-4 text-center">
            <a href="<?= base_url('login') ?>" class="text-sm text-blue-600 hover:underline">Back to Sign In</a>
        </div>
    </div>
</body>
</html>