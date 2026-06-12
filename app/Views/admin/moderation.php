<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<div class="flex flex-col lg:flex-row h-[calc(100vh-4rem)] -mx-margin-mobile md:-mx-margin-desktop -mb-margin-mobile md:-mb-margin-desktop">
    
    <section class="flex-1 overflow-y-auto border-r border-outline-variant p-margin-mobile md:p-margin-desktop flex flex-col gap-stack-lg bg-background">
        
        <div>
            <div class="flex items-center gap-stack-sm mb-stack-sm">
                <span class="bg-secondary-container text-on-secondary-container font-label-md text-caption px-2 py-1 rounded-DEFAULT border border-secondary">Pending Review</span>
                <span class="text-on-surface-variant font-caption text-caption flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">schedule</span> Submitted 2 hours ago
                </span>
            </div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-stack-sm">Moderation: Modern Villa in Canggu, Bali</h2>
            <div class="flex items-center gap-stack-sm text-on-surface-variant font-body-md text-body-md">
                <span class="material-symbols-outlined">location_on</span>
                Jl. Raya Canggu No. 12, Bali
            </div>
        </div>

        <div class="relative w-full h-80 rounded-lg overflow-hidden border border-outline-variant">
            <img alt="Property Exterior" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"/>
            <div class="absolute bottom-4 right-4 bg-surface/90 text-on-surface px-3 py-1 rounded-DEFAULT font-label-md text-caption flex items-center gap-2 backdrop-blur-sm border border-outline-variant">
                <span class="material-symbols-outlined text-[16px]">photo_library</span> 1 / 12
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md">
    
        <div class="bg-surface border border-outline-variant rounded-lg p-stack-md flex flex-col gap-1 min-w-0 overflow-hidden">
            <span class="text-on-surface-variant font-caption text-caption uppercase tracking-wider truncate">Asking Price</span>
            <span class="font-brand-text text-base md:text-lg xl:text-xl text-primary font-bold whitespace-nowrap tracking-tight">Rp 8.200.000.000</span>
        </div>
        
        <div class="bg-surface border border-outline-variant rounded-lg p-stack-md flex flex-col gap-1 min-w-0 overflow-hidden">
            <span class="text-on-surface-variant font-caption text-caption uppercase tracking-wider truncate">Property Type</span>
            <span class="font-body-lg text-lg lg:text-xl text-on-surface font-semibold truncate">Residential Villa</span>
        </div>
        
        <div class="bg-surface border border-outline-variant rounded-lg p-stack-md flex flex-col gap-1 min-w-0 overflow-hidden">
            <span class="text-on-surface-variant font-caption text-caption uppercase tracking-wider truncate">Specs</span>
            <div class="flex flex-wrap gap-x-4 gap-y-2 text-on-surface font-body-md mt-1">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">bed</span> 4</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[18px]">bathtub</span> 4</span>
                <span class="flex items-center gap-1 whitespace-nowrap"><span class="material-symbols-outlined text-[18px]">square_foot</span> 450 sqm</span>
            </div>
        </div>
        
        <div class="bg-surface border border-outline-variant rounded-lg p-stack-md flex flex-col gap-1 min-w-0 overflow-hidden">
            <span class="text-on-surface-variant font-caption text-caption uppercase tracking-wider truncate">Listed By</span>
            <div class="flex items-center gap-2 mt-1">
                <div class="w-8 h-8 shrink-0 bg-surface-variant rounded-full flex items-center justify-center text-primary font-bold text-sm">AK</div>
                <span class="font-body-md text-on-surface truncate">Agent Kadek</span>
            </div>
        </div>
    </div>

        <div class="bg-surface border border-outline-variant rounded-lg p-stack-md pb-24 lg:pb-stack-md">
            <h3 class="font-label-md text-body-lg mb-stack-sm text-on-surface">Description</h3>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                A stunning 4-bedroom modern villa with private pool located in the heart of Canggu. This newly constructed property features open-plan living areas, high-end European appliances, and smart home integration. The lush tropical garden and infinity pool offer a perfect oasis just minutes away from Echo Beach. Ideal for both investment and residential purposes.
            </p>
        </div>
    </section>

    <section class="w-full lg:w-[450px] flex flex-col bg-surface-container-lowest shrink-0 relative border-l border-outline-variant">
        
        <div class="p-stack-md border-b border-outline-variant flex items-center justify-between bg-surface sticky top-0 z-10">
            <h3 class="font-headline-lg-mobile text-body-lg text-on-surface font-semibold">Legal Documents</h3>
            <div class="flex gap-2">
                <button class="p-1 text-on-surface-variant hover:bg-surface-container-low rounded-DEFAULT transition-colors"><span class="material-symbols-outlined">zoom_in</span></button>
                <button class="p-1 text-on-surface-variant hover:bg-surface-container-low rounded-DEFAULT transition-colors"><span class="material-symbols-outlined">download</span></button>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto p-stack-md bg-surface-container-low flex flex-col gap-stack-md pb-32">
            <div class="bg-surface border border-outline-variant shadow-sm rounded-DEFAULT p-stack-md flex flex-col gap-stack-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between border-b border-outline-variant pb-stack-sm">
                    <h4 class="font-label-md text-label-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">description</span> SHM
                    </h4>
                    <span class="text-tertiary-container font-caption text-caption flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Verified
                    </span>
                </div>
                <div class="w-full h-48 bg-white border border-outline-variant rounded-DEFAULT flex flex-col items-center justify-center gap-2 opacity-80 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNlMmU4ZjAiLz48L3N2Zz4=')] opacity-50"></div>
                    <span class="material-symbols-outlined text-[48px] text-outline-variant">shield_lock</span>
                    <span class="font-caption text-on-surface-variant">SHM_Document_01.pdf</span>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant shadow-sm rounded-DEFAULT p-stack-md flex flex-col gap-stack-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between border-b border-outline-variant pb-stack-sm">
                    <h4 class="font-label-md text-label-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">description</span> SHGB
                    </h4>
                    <span class="text-tertiary-container font-caption text-caption flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Verified
                    </span>
                </div>
                <div class="w-full h-48 bg-white border border-outline-variant rounded-DEFAULT flex flex-col items-center justify-center gap-2 opacity-80 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNlMmU4ZjAiLz48L3N2Zz4=')] opacity-50"></div>
                    <span class="material-symbols-outlined text-[48px] text-outline-variant">landscape</span>
                    <span class="font-caption text-on-surface-variant">SHGB_Document_02.pdf</span>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 w-full bg-surface border-t border-outline-variant p-stack-md flex gap-stack-sm shadow-[0px_-4px_20px_rgba(26,54,93,0.08)] z-20">
            <button class="w-12 h-12 shrink-0 bg-error-container text-on-error-container rounded-DEFAULT hover:opacity-90 transition-opacity flex items-center justify-center" title="Reject">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            <button class="flex-1 border border-secondary text-secondary font-label-md text-label-md py-3 rounded-DEFAULT hover:bg-secondary-container hover:text-on-secondary-container transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">edit_note</span> Changes
            </button>
            <button class="flex-1 bg-tertiary-container text-on-tertiary-container font-label-md text-label-md py-3 rounded-DEFAULT hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">check</span> Approve
            </button>
        </div>
    </section>
</div>

<?= $this->endSection() ?>