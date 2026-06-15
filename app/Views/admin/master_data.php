<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="max-w-container-max mx-auto">
    <div class="mb-stack-lg mt-4">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-stack-sm">Master Data Configuration</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Manage core system taxonomies like locations and property types.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        
        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-200">
            <div class="p-gutter border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-stack-md flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">category</span>
                    Property Types
                </h2>
                <div class="flex gap-stack-sm">
                    <input class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-fixed-dim/50 bg-surface-container-lowest" placeholder="Add new type..." type="text"/>
                    <button class="h-10 px-4 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">add</span> Add New
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium">Type Name</th>
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium text-right w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Rumah (House)</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-surface-bright border-b border-outline-variant hover:bg-surface transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Vila (Villa)</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Apartemen (Apartment)</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-surface-bright hover:bg-surface transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Tanah (Land)</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col hover:shadow-[0px_4px_20px_rgba(26,54,93,0.08)] transition-shadow duration-200">
            <div class="p-gutter border-b border-outline-variant bg-surface-container-low rounded-t-lg">
                <h2 class="font-label-md text-label-md text-on-surface mb-stack-md flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">location_on</span>
                    Supported Locations
                </h2>
                <div class="flex gap-stack-sm">
                    <input class="flex-1 h-10 px-3 border border-outline-variant rounded font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-fixed-dim/50 bg-surface-container-lowest" placeholder="Add new location..." type="text"/>
                    <button class="h-10 px-4 bg-primary text-on-primary rounded font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">add</span> Add New
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium">City / Region</th>
                            <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant font-medium text-right w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface flex items-center gap-2">
                                Jakarta
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary-fixed/30 text-on-primary-container border border-primary-fixed">Active</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-outline-variant cursor-not-allowed p-1" disabled title="Cannot delete active region"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-surface-bright border-b border-outline-variant hover:bg-surface transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Surabaya</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Bandung</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-surface-bright border-b border-outline-variant hover:bg-surface transition-colors">
                            <td class="py-3 px-4 font-body-md text-body-md text-on-surface">Bekasi</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>

<?= $this->endSection() ?>