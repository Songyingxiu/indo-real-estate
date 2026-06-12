<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-stack-lg">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface mb-unit">Leads Overview</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Track, assign, and monitor property inquiries.</p>
    </div>
    
    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
        <div class="relative w-full md:w-64">
            <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">search</span>
            <input class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-DEFAULT bg-surface-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary-fixed-dim transition-all outline-none" placeholder="Search leads..." type="text"/>
        </div>
        
        <div class="relative flex-1 md:w-40">
            <select class="w-full appearance-none pl-4 pr-10 py-2 border border-outline-variant rounded-DEFAULT bg-surface-lowest text-on-surface font-body-md text-body-md outline-none">
                <option value="">Status: All</option>
                <option value="new">New</option>
                <option value="contacted">Contacted</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 transform -translate-y-1/2 text-outline pointer-events-none">expand_more</span>
        </div>
    </div>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container-low text-on-surface-variant font-label-md text-label-md">
                    <th class="p-4 font-semibold w-24">Lead ID</th>
                    <th class="p-4 font-semibold">Property</th>
                    <th class="p-4 font-semibold">Inquirer</th>
                    <th class="p-4 font-semibold">Assigned Agent</th>
                    <th class="p-4 font-semibold w-32">Status</th>
                    <th class="p-4 font-semibold w-32">Date</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-body-md divide-y divide-outline-variant">
                
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface-variant font-mono text-sm">#LD-8924</td>
                    <td class="p-4">
                        <p class="text-on-surface font-semibold">Sudirman Executive Tower</p>
                        <p class="text-on-surface-variant text-caption">Jakarta Selatan</p>
                    </td>
                    <td class="p-4 text-on-surface">Budi Santoso</td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-label-md text-[10px]">RR</div>
                            <span>Reza Rahadian</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-fixed text-on-primary-fixed">New</span>
                    </td>
                    <td class="p-4 text-on-surface-variant">Oct 24, 2023</td>
                </tr>
                
                <tr class="bg-surface-bright hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface-variant font-mono text-sm">#LD-8923</td>
                    <td class="p-4">
                        <p class="text-on-surface font-semibold">Seminyak Villa Breeze</p>
                        <p class="text-on-surface-variant text-caption">Bali</p>
                    </td>
                    <td class="p-4 text-on-surface">Siti Aminah</td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-tertiary-fixed text-on-tertiary-fixed flex items-center justify-center font-label-md text-[10px]">DS</div>
                            <span>Dian Sastrowardoyo</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-variant text-on-surface-variant border border-outline-variant">Contacted</span>
                    </td>
                    <td class="p-4 text-on-surface-variant">Oct 23, 2023</td>
                </tr>

                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface-variant font-mono text-sm">#LD-8920</td>
                    <td class="p-4">
                        <p class="text-on-surface font-semibold">Pakuwon Indah Residence</p>
                        <p class="text-on-surface-variant text-caption">Surabaya</p>
                    </td>
                    <td class="p-4 text-on-surface">Agus Pratama</td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-label-md text-[10px]">RR</div>
                            <span>Reza Rahadian</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-secondary-fixed text-on-secondary-fixed-variant">Qualified</span>
                    </td>
                    <td class="p-4 text-on-surface-variant">Oct 21, 2023</td>
                </tr>

            </tbody>
        </table>
    </div>
    
    <div class="border-t border-outline-variant p-4 flex items-center justify-between bg-surface-container-low">
        <span class="text-on-surface-variant font-body-md text-sm">Showing 1 to 3 of 42 entries</span>
        <div class="flex gap-1">
            <button class="p-1 px-3 border border-outline-variant rounded text-on-surface disabled:opacity-50" disabled>Prev</button>
            <button class="p-1 px-3 bg-primary text-on-primary rounded">1</button>
            <button class="p-1 px-3 border border-outline-variant rounded text-on-surface hover:bg-surface-container transition-colors">2</button>
            <button class="p-1 px-3 border border-outline-variant rounded text-on-surface hover:bg-surface-container transition-colors">Next</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>