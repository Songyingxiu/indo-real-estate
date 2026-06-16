<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-stack-lg gap-4 mt-4">
    <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background">CMS Management</h1>
    <button class="bg-primary-container text-on-primary font-label-md text-label-md px-4 py-2 rounded flex items-center gap-2 hover:bg-primary-container/90 transition-colors shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Create New Post
    </button>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface border-b border-outline-variant">
                    <th class="font-label-md text-label-md text-on-surface-variant py-3 px-4 w-1/2">Article Title</th>
                    <th class="font-label-md text-label-md text-on-surface-variant py-3 px-4">Author</th>
                    <th class="font-label-md text-label-md text-on-surface-variant py-3 px-4">Publish Date</th>
                    <th class="font-label-md text-label-md text-on-surface-variant py-3 px-4">Status</th>
                    <th class="font-label-md text-label-md text-on-surface-variant py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-body-md">
                
                <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors group">
                    <td class="py-4 px-4">
                        <div class="font-semibold text-primary">Top 10 Bali Investment Villas</div>
                        <div class="text-sm text-on-surface-variant mt-1">/blog/investment/bali-villas-2024</div>
                    </td>
                    <td class="py-4 px-4 text-on-background">Budi Santoso</td>
                    <td class="py-4 px-4 text-on-background">Oct 12, 2023</td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-tertiary-container/20 text-on-tertiary-container text-xs font-semibold">
                            Published
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded hover:bg-surface-container">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>
                
                <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors group bg-background">
                    <td class="py-4 px-4">
                        <div class="font-semibold text-primary">Jakarta Property Trends 2024</div>
                        <div class="text-sm text-on-surface-variant mt-1">/blog/market-trends/jakarta-2024</div>
                    </td>
                    <td class="py-4 px-4 text-on-background">Siti Rahma</td>
                    <td class="py-4 px-4 text-on-background">Nov 05, 2023</td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-tertiary-container/20 text-on-tertiary-container text-xs font-semibold">
                            Published
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded hover:bg-surface-container">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>

                <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors group">
                    <td class="py-4 px-4">
                        <div class="font-semibold text-primary">Guide to SHM Certification</div>
                        <div class="text-sm text-on-surface-variant mt-1">/guides/legal/shm-certification-explained</div>
                    </td>
                    <td class="py-4 px-4 text-on-background">Ahmad Wijaya</td>
                    <td class="py-4 px-4 text-on-surface-variant italic">Pending</td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-surface-container-highest text-on-surface-variant text-xs font-semibold">
                            Draft
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded hover:bg-surface-container">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-surface-container-low transition-colors group bg-background">
                    <td class="py-4 px-4">
                        <div class="font-semibold text-primary">Bandung Residential Growth</div>
                        <div class="text-sm text-on-surface-variant mt-1">/blog/market-trends/bandung-growth</div>
                    </td>
                    <td class="py-4 px-4 text-on-background">Budi Santoso</td>
                    <td class="py-4 px-4 text-on-background">Nov 18, 2023</td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-tertiary-container/20 text-on-tertiary-container text-xs font-semibold">
                            Published
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded hover:bg-surface-container">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="border-t border-outline-variant px-4 py-3 flex items-center justify-between bg-surface-container-lowest">
        <span class="font-caption text-caption text-on-surface-variant">Showing 1 to 4 of 24 entries</span>
        <div class="flex items-center gap-2">
            <button class="p-1 border border-outline-variant rounded text-on-surface-variant hover:bg-surface-container-low disabled:opacity-50" disabled="">
                <span class="material-symbols-outlined text-[18px]">chevron_left</span>
            </button>
            <button class="p-1 border border-outline-variant rounded text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>