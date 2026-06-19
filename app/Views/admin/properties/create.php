<?= $this->extend('admin/layout/master') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 fade-in">
    <h2 class="font-headline-lg text-[28px] font-bold text-on-surface mb-6">Create New Listing</h2>

    <div class="flex items-center gap-4 mb-8 border-b border-outline-variant pb-6">
        <div class="flex items-center gap-2 text-primary font-semibold">
            <span class="bg-primary text-on-primary w-6 h-6 rounded-full flex items-center justify-center text-sm">1</span> Details
        </div>
        <div class="w-12 h-px bg-primary"></div>
        <div class="flex items-center gap-2 text-primary font-semibold">
            <span class="bg-primary text-on-primary w-6 h-6 rounded-full flex items-center justify-center text-sm">2</span> Media
        </div>
        <div class="w-12 h-px bg-primary"></div>
        <div class="flex items-center gap-2 text-primary font-semibold">
            <span class="bg-primary text-on-primary w-6 h-6 rounded-full flex items-center justify-center text-sm">3</span> Verification
        </div>
    </div>
    
    <form action="<?= base_url('admin/properties/store') ?>" method="POST" enctype="multipart/form-data" class="bg-surface-container-lowest shadow-sm rounded-lg border border-outline-variant p-6 md:p-8 space-y-8">
        
        <div>
            <h3 class="font-headline-md text-lg font-semibold text-on-surface mb-4">Property Details</h3>
            <p class="text-on-surface-variant text-sm mb-6">Provide the foundational information about your property to attract the right buyers or renters.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Property Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all">
                </div>
                
                <div>
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Listing Type *</label>
                    <select name="listing_type" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-fixed-dim transition-all">
                        <option value="Sale">For Sale</option>
                        <option value="Rent">For Rent</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Asking Price (IDR) *</label>
                    <input type="number" name="tax_price" required class="w-full px-4 py-3 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 transition-all" placeholder="e.g. 1500000000">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Complete Address *</label>
                    <input type="text" name="address_line_1" required placeholder="e.g., Jl. Sudirman No. 1, Jakarta" class="w-full px-4 py-3 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Property Description *</label>
                    <textarea name="description" rows="4" placeholder="Highlight the key features, neighborhood benefits..." class="w-full px-4 py-3 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container focus:ring-2 transition-all"></textarea>
                </div>
            </div>

            <h3 class="font-headline-md text-md font-semibold text-on-surface mt-6 mb-4">Features & Dimensions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Bedrooms</label>
                    <input type="number" name="bed" min="0" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Bathrooms</label>
                    <input type="number" name="bath" min="0" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Land Area (m&sup2;)</label>
                    <input type="number" step="0.01" name="total_land_area" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Building Area (m&sup2;)</label>
                    <input type="number" step="0.01" name="usable_area" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Parking</label>
                    <select name="parking" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Total Parking</label>
                    <input type="number" name="total_parking" min="0" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Total Floors</label>
                    <input type="number" name="total_floors" min="1" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
                <div>
                    <label class="block font-label-md text-[12px] font-semibold text-on-surface mb-1">Year Built</label>
                    <input type="number" name="year_built" min="1900" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded text-on-surface focus:outline-none focus:border-primary-container">
                </div>
            </div>
        </div>

        <hr class="border-outline-variant">

        <div>
            <h3 class="font-headline-md text-lg font-semibold text-on-surface mb-4">Media & Legal Verification</h3>
            <p class="text-on-surface-variant text-sm mb-6">Please upload the required documents to verify property ownership. This ensures a secure marketplace.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">Property Photos (Max 5) *</label>
                    <div class="w-full px-4 py-8 border-2 border-dashed border-outline-variant rounded text-center bg-surface hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[32px] text-on-surface-variant mb-2">add_photo_alternate</span>
                        <input type="file" name="property_images[]" multiple accept="image/*" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-container file:text-on-primary hover:file:bg-primary transition-colors cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-[14px] font-semibold text-on-surface mb-2">SHM Document (PDF/JPG) *</label>
                    <div class="w-full px-4 py-8 border-2 border-dashed border-outline-variant rounded text-center bg-surface hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[32px] text-on-surface-variant mb-2">description</span>
                        <input type="file" name="shm_document" accept=".pdf,.jpg,.png" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-secondary-container file:text-on-secondary-container hover:file:bg-secondary transition-colors cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-outline-variant flex justify-between items-center">
            <a href="<?= base_url('admin/properties') ?>" class="text-on-surface-variant hover:text-primary font-semibold text-sm transition-colors">Cancel & Exit</a>
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded font-label-md text-[15px] font-semibold hover:bg-[#004a77] transition-colors">Submit for Admin Review</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>