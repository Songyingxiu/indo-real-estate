<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>

<style>
    .wysiwyg-toolbar button {
        @apply p-2 rounded hover:bg-surface-container-high transition-colors text-on-surface-variant;
    }
    .variable-chip {
        @apply bg-primary-fixed text-on-primary-fixed px-2 py-0.5 rounded font-label-md text-caption inline-block border border-primary-fixed-dim;
    }
</style>

<div class="mb-stack-lg flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mt-4">
    <div>
        <h1 class="font-headline-lg-mobile md:font-headline-lg text-primary mb-unit">Email Templates</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Manage and edit system notification emails.</p>
    </div>
    <button class="bg-primary text-on-primary px-stack-md py-2 rounded font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors flex items-center gap-unit shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        New Template
    </button>
</div>

<div class="flex-1 flex flex-col md:flex-row gap-gutter overflow-hidden h-[calc(100vh-200px)] min-h-[600px]">
    
    <div class="w-full md:w-1/3 lg:w-1/4 bg-surface-container-lowest border border-outline-variant rounded flex flex-col overflow-hidden shadow-sm">
        <div class="p-stack-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
            <h2 class="font-label-md text-label-md text-on-surface">System Templates</h2>
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">filter_list</span>
        </div>
        <div class="flex-1 overflow-y-auto p-unit flex flex-col gap-unit custom-scrollbar">
            
            <button class="w-full text-left p-stack-sm rounded border border-transparent hover:bg-surface-container-low transition-colors group flex items-start gap-stack-sm">
                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">waving_hand</span>
                <div>
                    <div class="font-label-md text-label-md text-on-surface group-hover:text-primary">Welcome User</div>
                    <div class="font-caption text-caption text-on-surface-variant">Sent upon successful registration.</div>
                </div>
            </button>
            
            <button class="w-full text-left p-stack-sm rounded border border-transparent hover:bg-surface-container-low transition-colors group flex items-start gap-stack-sm">
                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">lock_reset</span>
                <div>
                    <div class="font-label-md text-label-md text-on-surface group-hover:text-primary">Password Reset</div>
                    <div class="font-caption text-caption text-on-surface-variant">Contains secure reset link.</div>
                </div>
            </button>
            
            <button class="w-full text-left p-stack-sm rounded border border-transparent hover:bg-surface-container-low transition-colors group flex items-start gap-stack-sm">
                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">verified</span>
                <div>
                    <div class="font-label-md text-label-md text-on-surface group-hover:text-primary">Subscription Activation</div>
                    <div class="font-caption text-caption text-on-surface-variant">Confirmation of premium status.</div>
                </div>
            </button>
            
            <button class="w-full text-left p-stack-sm rounded border-l-4 border-primary bg-surface-container-low flex items-start gap-stack-sm shadow-sm">
                <span class="material-symbols-outlined text-primary mt-0.5">campaign</span>
                <div>
                    <div class="font-label-md text-label-md text-primary">New Lead Alert</div>
                    <div class="font-caption text-caption text-on-surface-variant">Notifies agents of new inquiries.</div>
                </div>
            </button>
            
            <button class="w-full text-left p-stack-sm rounded border border-transparent hover:bg-surface-container-low transition-colors group flex items-start gap-stack-sm mt-stack-md">
                <span class="material-symbols-outlined text-on-surface-variant mt-0.5">event</span>
                <div>
                    <div class="font-label-md text-label-md text-on-surface group-hover:text-primary">Viewing Scheduled</div>
                    <div class="font-caption text-caption text-on-surface-variant">Calendar invite and details.</div>
                </div>
            </button>
            
        </div>
    </div>

    <div class="flex-1 bg-surface-container-lowest border border-outline-variant rounded flex flex-col shadow-sm overflow-hidden">
        
        <div class="p-stack-md border-b border-outline-variant bg-surface-container-low flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-primary leading-tight">New Lead Alert</h2>
                <p class="font-caption text-caption text-on-surface-variant mt-1">ID: TPL_LEAD_01 • Last modified: 2 hours ago by Admin</p>
            </div>
            <div class="flex gap-stack-sm w-full sm:w-auto">
                <button class="flex-1 sm:flex-none px-stack-sm py-1.5 border border-primary text-primary rounded font-label-md text-label-md hover:bg-primary-fixed transition-colors">Test Email</button>
                <button class="flex-1 sm:flex-none px-stack-sm py-1.5 bg-primary text-on-primary rounded font-label-md text-label-md hover:opacity-90 transition-opacity shadow-sm">Save Changes</button>
            </div>
        </div>

        <div class="p-stack-md border-b border-outline-variant flex flex-col gap-stack-sm bg-surface-container-lowest">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-stack-md">
                <label class="w-24 font-label-md text-label-md text-on-surface-variant">Subject Line:</label>
                <div class="flex-1 relative">
                    <input class="w-full border border-outline-variant rounded p-2 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary-fixed-dim transition-all" type="text" value="New Inquiry for [Property Address]">
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-stack-md mt-2 sm:mt-0">
                <label class="w-24 font-label-md text-label-md text-on-surface-variant">From:</label>
                <div class="flex-1">
                    <span class="font-body-md text-body-md text-on-surface bg-surface-container px-3 py-1.5 rounded inline-block">leads@appname.com</span>
                </div>
            </div>
        </div>

        <div class="px-stack-sm py-2 border-b border-outline-variant bg-surface-container-low flex flex-wrap gap-unit items-center wysiwyg-toolbar">
            <div class="flex items-center border-r border-outline-variant pr-unit mr-unit gap-unit">
                <select class="border-transparent bg-transparent font-label-md text-label-md text-on-surface-variant focus:ring-0 py-1 pl-2 pr-8 cursor-pointer hover:bg-surface-container-high rounded">
                    <option>Normal Text</option>
                    <option>Heading 1</option>
                    <option>Heading 2</option>
                </select>
            </div>
            <div class="flex items-center border-r border-outline-variant pr-unit mr-unit gap-unit">
                <button title="Bold"><span class="material-symbols-outlined text-[20px]">format_bold</span></button>
                <button title="Italic"><span class="material-symbols-outlined text-[20px]">format_italic</span></button>
                <button title="Underline"><span class="material-symbols-outlined text-[20px]">format_underlined</span></button>
            </div>
            <div class="flex items-center border-r border-outline-variant pr-unit mr-unit gap-unit">
                <button title="Align Left"><span class="material-symbols-outlined text-[20px]">format_align_left</span></button>
                <button title="Align Center"><span class="material-symbols-outlined text-[20px]">format_align_center</span></button>
                <button title="Align Right"><span class="material-symbols-outlined text-[20px]">format_align_right</span></button>
            </div>
            <div class="flex items-center gap-unit">
                <button title="Insert Link"><span class="material-symbols-outlined text-[20px]">link</span></button>
                <button title="Insert Image"><span class="material-symbols-outlined text-[20px]">image</span></button>
            </div>
            <div class="ml-auto mt-2 sm:mt-0">
                <button class="flex items-center gap-unit px-3 py-1.5 bg-surface-lowest border border-outline-variant rounded text-primary hover:border-primary font-label-md text-caption transition-colors">
                    <span class="material-symbols-outlined text-[16px]">data_object</span>
                    Insert Variable
                </button>
            </div>
        </div>

        <div class="flex-1 p-margin-mobile sm:p-margin-desktop overflow-y-auto bg-surface-container-lowest font-body-md text-body-md text-on-surface leading-relaxed relative custom-scrollbar">
            <div class="max-w-3xl mx-auto min-h-full outline-none">
                
                <div class="mb-stack-md p-stack-md bg-surface-bright border border-outline-variant rounded flex justify-center border-dashed">
                    <div class="w-12 h-12 bg-surface-container-highest rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline">image</span>
                    </div>
                </div>
                
                <p class="mb-stack-sm">Hello <span class="variable-chip">[Agent Name]</span>,</p>
                <p class="mb-stack-md">You have received a new inquiry through the platform from <span class="variable-chip">[Buyer Name]</span> regarding the property listing at <strong class="text-primary"><span class="variable-chip">[Property Address]</span></strong>.</p>
                
                <div class="bg-surface-container-low p-stack-md rounded border-l-4 border-primary mb-stack-md">
                    <h3 class="font-label-md text-label-md text-primary mb-unit">Inquiry Details:</h3>
                    <ul class="list-disc pl-5 space-y-1 text-on-surface-variant font-body-md text-body-md">
                        <li><strong>Lead Source:</strong> Direct Website Search</li>
                        <li><strong>Interest Level:</strong> High (Requested immediate viewing)</li>
                        <li><strong>Message:</strong> "<span class="variable-chip">[Buyer Message]</span>"</li>
                    </ul>
                </div>
                
                <p class="mb-stack-lg">Please contact them at your earliest convenience at <span class="variable-chip">[Buyer Phone]</span> or via email at <span class="variable-chip">[Buyer Email]</span>.</p>
                
                <div class="mt-stack-lg pt-stack-sm border-t border-outline-variant text-on-surface-variant font-caption text-caption">
                    <p>EstateAdmin Pro Console • Automated Message</p>
                    <p>Do not reply directly to this email.</p>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?= $this->endSection() ?>