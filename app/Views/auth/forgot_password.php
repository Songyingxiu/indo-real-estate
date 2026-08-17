<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - HuniKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-2">Forgot Password</h2>
        <p class="text-sm text-slate-600 text-center mb-6">Enter your email and we'll send you a password reset link.</p>

        <?php if (session()->getFlashdata('error') && !session()->has('errors')): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('forgot-password') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?= session('errors.email') ? '<p class="text-red-600 text-xs mt-1 font-medium">'.esc(session('errors.email')).'</p>' : '' ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 font-semibold transition">Send Reset Link</button>
        </form>

        <div class="mt-4 text-center">
            <a href="<?= base_url('login') ?>" class="text-sm text-blue-600 hover:underline">Back to Sign In</a>
        </div>
    </div>
</body>
</html>