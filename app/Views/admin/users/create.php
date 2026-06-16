<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="mb-stack-md mt-4">
    <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center gap-2 text-primary hover:text-on-primary-fixed-variant transition-colors font-label-md text-label-md group">
        <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform" data-icon="arrow_back">arrow_back</span>
        Back to Users List
    </a>
</div>

<div class="mb-stack-lg">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">Create New User</h2>
    <p class="text-on-surface-variant mt-1">Add a new user to the ProEstate platform.</p>
</div>

<div class="bg-surface-container-lowest rounded-lg border border-outline-variant hover:shadow-[0_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-300">
    <form action="#" class="p-gutter" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
            
            <div class="space-y-stack-md">
                <h3 class="font-label-md text-label-md text-on-surface mb-4 border-b border-outline-variant pb-2">Personal Information</h3>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-unit" for="fullName">Full Name</label>
                    <input class="w-full bg-surface-container-lowest border border-outline-variant rounded focus:ring-2 focus:ring-primary-fixed-dim focus:border-primary transition-all px-3 py-2 text-on-surface font-body-md text-body-md placeholder:text-outline" id="fullName" name="fullName" placeholder="Budi Santoso" type="text">
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-unit" for="email">Email Address</label>
                    <input class="w-full bg-surface-container-lowest border border-outline-variant rounded focus:ring-2 focus:ring-primary-fixed-dim focus:border-primary transition-all px-3 py-2 text-on-surface font-body-md text-body-md placeholder:text-outline" id="email" name="email" placeholder="budi.santoso@example.com" type="email">
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-unit" for="phone">Phone Number</label>
                    <input class="w-full bg-surface-container-lowest border border-outline-variant rounded focus:ring-2 focus:ring-primary-fixed-dim focus:border-primary transition-all px-3 py-2 text-on-surface font-body-md text-body-md placeholder:text-outline" id="phone" name="phone" placeholder="+62 812-3456-7890" type="tel">
                </div>
            </div>

            <div class="space-y-stack-md">
                <h3 class="font-label-md text-label-md text-on-surface mb-4 border-b border-outline-variant pb-2">System Access</h3>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-unit" for="role">System Role</label>
                    <div class="relative">
                        <select class="w-full bg-surface-container-lowest border border-outline-variant rounded focus:ring-2 focus:ring-primary-fixed-dim focus:border-primary transition-all px-3 py-2 text-on-surface font-body-md text-body-md appearance-none" id="role" name="role">
                            <option disabled="" selected="" value="">Select a role...</option>
                            <option value="administrator">Administrator</option>
                            <option value="agent">Agent</option>
                            <option value="owner">Property Owner</option>
                            <option value="buyer">Buyer</option>
                            <option value="visitor">Visitor</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-outline" data-icon="expand_more">expand_more</span>
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-unit">Account Status</label>
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input checked="" class="sr-only peer" type="checkbox" name="status" value="active">
                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="ml-3 font-body-md text-body-md text-on-surface peer-checked:text-primary">Active</span>
                        </label>
                    </div>
                    <p class="font-caption text-caption text-on-surface-variant mt-2">Suspended users cannot log in or access platform features.</p>
                </div>
                
                <div class="mt-stack-md p-4 bg-surface-container-low rounded border border-outline-variant flex gap-3">
                    <span class="material-symbols-outlined text-outline shrink-0" data-icon="security">security</span>
                    <div>
                        <p class="font-label-md text-label-md text-on-surface">Password Generation</p>
                        <p class="font-caption text-caption text-on-surface-variant">A temporary password will be automatically generated and sent to the user's email address upon saving.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-stack-lg pt-stack-md border-t border-outline-variant flex justify-end gap-4">
            <a href="<?= base_url('admin/users') ?>" class="px-6 py-2 rounded font-label-md text-label-md text-primary bg-transparent border border-outline-variant hover:border-primary transition-colors focus:ring-2 focus:ring-primary-fixed-dim outline-none text-center inline-flex items-center">
                Cancel
            </a>
            <button class="px-6 py-2 rounded font-label-md text-label-md text-on-primary bg-primary-container hover:bg-on-primary-fixed-variant transition-colors border border-transparent shadow-sm focus:ring-2 focus:ring-primary-fixed-dim focus:ring-offset-1 outline-none" type="submit">
                Save User
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>