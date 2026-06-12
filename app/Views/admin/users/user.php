<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<header class="mb-stack-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-stack-md mt-4">
    <div>
        <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-unit">User Management</h2>
        <p class="text-on-surface-variant font-body-md text-body-md">Oversee and manage registered marketplace accounts.</p>
    </div>
    <button class="bg-primary text-on-primary px-4 py-2 rounded flex items-center gap-2 hover:opacity-90 transition-opacity font-label-md text-label-md">
        <span class="material-symbols-outlined text-sm">person_add</span>
        Add New User
    </button>
</header>

<div class="bg-surface-container-lowest p-stack-md rounded border border-outline-variant mb-stack-lg flex flex-col md:flex-row justify-between items-center gap-stack-md shadow-sm">
    <div class="relative w-full md:w-96">
        <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-outline">search</span>
        <input class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all font-body-md text-body-md bg-surface-lowest" placeholder="Search by name or email..." type="text"/>
    </div>
    <div class="flex items-center gap-stack-sm w-full md:w-auto">
        <span class="font-label-md text-label-md text-on-surface-variant whitespace-nowrap">Filter by Role:</span>
        <select class="w-full md:w-48 border border-outline-variant rounded py-2 px-3 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none font-body-md text-body-md bg-surface-lowest">
            <option value="all">All Roles</option>
            <option value="visitor">Visitor</option>
            <option value="buyer">Buyer</option>
            <option value="owner">Property Owner</option>
            <option value="agent">Agent</option>
        </select>
    </div>
</div>

<div class="flex flex-col gap-unit">
    <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-surface border-b border-outline-variant font-label-md text-label-md text-on-surface-variant uppercase tracking-wider rounded-t">
        <div class="col-span-4">User Details</div>
        <div class="col-span-3">Role</div>
        <div class="col-span-3">Registration Date</div>
        <div class="col-span-2 text-right">Actions</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 md:px-6 md:py-4 bg-surface-container-lowest border border-outline-variant rounded transition-all table-row-hover group">
        <div class="col-span-1 md:col-span-4 flex items-center gap-stack-md">
            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold uppercase shrink-0">BS</div>
            <div>
                <p class="font-label-md text-label-md text-primary">Budi Santoso</p>
                <p class="font-caption text-caption text-outline">budi.santoso@example.co.id</p>
            </div>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center">
            <span class="md:hidden font-caption text-outline">Role:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-tertiary-fixed text-on-tertiary-fixed border border-tertiary-fixed-dim">
                <span class="material-symbols-outlined text-[14px] mr-1">verified</span> Property Owner
            </span>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center text-on-surface-variant">
            <span class="md:hidden font-caption text-outline">Reg. Date:</span>
            Oct 12, 2023
        </div>
        <div class="col-span-1 md:col-span-2 flex justify-end">
            <button aria-label="Actions" class="text-outline hover:text-primary p-2 rounded-full hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 md:px-6 md:py-4 bg-surface border border-outline-variant rounded transition-all table-row-hover group">
        <div class="col-span-1 md:col-span-4 flex items-center gap-stack-md">
            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold uppercase shrink-0">SA</div>
            <div>
                <p class="font-label-md text-label-md text-primary">Siti Aminah</p>
                <p class="font-caption text-caption text-outline">siti.a@example.com</p>
            </div>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center">
            <span class="md:hidden font-caption text-outline">Role:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-secondary-fixed text-on-secondary-fixed border border-secondary-fixed-dim">
                Agent
            </span>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center text-on-surface-variant">
            <span class="md:hidden font-caption text-outline">Reg. Date:</span>
            Nov 05, 2023
        </div>
        <div class="col-span-1 md:col-span-2 flex justify-end">
            <button aria-label="Actions" class="text-outline hover:text-primary p-2 rounded-full hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 md:px-6 md:py-4 bg-surface-container-lowest border border-outline-variant rounded transition-all table-row-hover group">
        <div class="col-span-1 md:col-span-4 flex items-center gap-stack-md">
            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold uppercase shrink-0">RP</div>
            <div>
                <p class="font-label-md text-label-md text-primary">Reza Pratama</p>
                <p class="font-caption text-caption text-outline">reza.p@domain.id</p>
            </div>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center">
            <span class="md:hidden font-caption text-outline">Role:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-container-high text-on-surface-variant border border-outline-variant">
                Buyer
            </span>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center text-on-surface-variant">
            <span class="md:hidden font-caption text-outline">Reg. Date:</span>
            Dec 20, 2023
        </div>
        <div class="col-span-1 md:col-span-2 flex justify-end">
            <button aria-label="Actions" class="text-outline hover:text-primary p-2 rounded-full hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center p-4 md:px-6 md:py-4 bg-surface border border-outline-variant rounded transition-all table-row-hover group opacity-60">
        <div class="col-span-1 md:col-span-4 flex items-center gap-stack-md">
            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold uppercase shrink-0">DL</div>
            <div>
                <p class="font-label-md text-label-md text-primary">Dewi Lestari</p>
                <p class="font-caption text-caption text-outline">dewi.l@example.com (Suspended)</p>
            </div>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center">
            <span class="md:hidden font-caption text-outline">Role:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-container-high text-on-surface-variant border border-outline-variant">
                Visitor
            </span>
        </div>
        <div class="col-span-1 md:col-span-3 flex justify-between md:justify-start items-center text-on-surface-variant">
            <span class="md:hidden font-caption text-outline">Reg. Date:</span>
            Jan 15, 2024
        </div>
        <div class="col-span-1 md:col-span-2 flex justify-end">
            <button aria-label="Actions" class="text-outline hover:text-primary p-2 rounded-full hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </div>
    </div>
</div>

<div class="mt-stack-lg flex flex-col md:flex-row justify-between items-center text-on-surface-variant font-label-md text-label-md gap-4">
    <span>Showing 1 to 4 of 240 entries</span>
    <div class="flex gap-unit">
        <button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low transition-colors disabled:opacity-50" disabled>Prev</button>
        <button class="px-3 py-1 bg-primary text-on-primary rounded border border-primary">1</button>
        <button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low transition-colors">2</button>
        <button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low transition-colors">3</button>
        <button class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low transition-colors">Next</button>
    </div>
</div>

<?= $this->endSection() ?>