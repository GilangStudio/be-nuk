@extends('layouts.main')

@section('title', 'Home Page Management')

@push('styles')
<style>
.banner-card {
    transition: all 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
}

.banner-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.banner-image {
    height: 200px;
    object-fit: cover;
    background-color: #f8f9fa;
}

.about-image-preview {
    max-height: 200px;
    overflow: hidden;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.about-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.sortable-handle {
    cursor: grab;
    color: #6c757d;
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255,255,255,0.9);
    border-radius: 4px;
    padding: 4px;
    z-index: 10;
}

.sortable-handle:hover {
    color: var(--bs-primary);
    background: rgba(255,255,255,1);
}

.sortable-ghost {
    opacity: 0.5;
}

.sortable-chosen {
    transform: rotate(2deg);
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.7) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-card:hover .banner-overlay {
    opacity: 1;
}

.banner-actions {
    position: absolute;
    bottom: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-card:hover .banner-actions {
    opacity: 1;
}

.banner-status {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
}

.banner-order {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 10;
}

.loading {
    pointer-events: none;
    opacity: 0.6;
}

.image-preview-container {
    max-height: 300px;
    overflow: hidden;
    border-radius: 8px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.service-card {
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.service-card:hover {
    border-color: var(--bs-primary);
    transform: translateY(-1px);
}

.service-card.selected {
    border-color: var(--bs-primary);
    background-color: var(--bs-primary-bg);
}

.logo-preview {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.logo-preview img {
    max-height: 60px;
    max-width: 100%;
    object-fit: contain;
}

.banner-item, .logo-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #fff;
}

.remove-item-btn {
    margin-top: 0.5rem;
}
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
<div>
    <h2 class="page-title">Home Page Management</h2>
    <div class="page-subtitle">Manage homepage content, banners, about section, services, and company logos</div>
</div>
</div>
@endsection

@section('content')

{{-- About Section Form --}}
<form method="POST" action="{{ route('home-page.about.update') }}" enctype="multipart/form-data" id="about-form">
@csrf
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ti ti-info-circle me-2"></i>
                About Us Section
            </h3>
            @if($homePageSetting)
            <div class="card-actions">
                <span class="badge bg-green-lt">
                    <i class="ti ti-check me-1"></i>
                    Configured
                </span>
            </div>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">About Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('about_title') is-invalid @enderror" 
                               name="about_title" value="{{ old('about_title', $homePageSetting->about_title ?? '') }}" 
                               required placeholder="Enter about title">
                        @error('about_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">About Description <span class="text-danger">*</span></label>
                        <textarea name="content" id="editor" class="form-control @error('about_description') is-invalid @enderror" 
                                  style="display: none;" required>{{ old('about_description', $homePageSetting->about_description ?? '') }}</textarea>
                        <input type="hidden" name="about_description" value="{{ old('about_description', $homePageSetting->about_description ?? '') }}">
                        @error('about_description')
                            <div class="invalid-feedback" id="content-error">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="about-description-error" style="display: none;"></div>
                        <small class="form-hint">
                            Describe your company and what makes you unique.
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    @if($homePageSetting && $homePageSetting->about_image_path)
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div class="about-image-preview">
                                <img src="{{ $homePageSetting->about_image_url }}" class="about-image" alt="Current about image">
                            </div>
                            <small class="text-secondary mt-1 d-block">
                                <i class="ti ti-info-circle me-1"></i>
                                Current about image
                            </small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">
                            {{ $homePageSetting ? 'Update About Image' : 'About Image' }}
                            @if(!$homePageSetting)<span class="text-danger">*</span>@endif
                        </label>
                        <input type="file" class="form-control @error('about_image') is-invalid @enderror" 
                               name="about_image" accept="image/*" {{ $homePageSetting ? '' : 'required' }} id="about-input">
                        @error('about_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">
                            @if($homePageSetting)Leave empty to keep current image. @endif
                            Recommended: 800x600px, Max: 15MB
                        </small>
                        <div class="mt-3" id="about-preview"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image Alt Text</label>
                        <input type="text" class="form-control" name="about_image_alt_text" 
                               value="{{ old('about_image_alt_text', $homePageSetting->about_image_alt_text ?? '') }}" 
                               placeholder="Image description">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEO Settings --}}
    @include('components.seo-meta-form', ['type' => $homePageSetting ? 'edit' : 'create', 'data' => $homePageSetting])
    
    <div class="card mt-3">
        <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
                @if($homePageSetting)
                <div class="d-flex align-items-center text-secondary">
                    <i class="ti ti-clock me-2"></i>
                    <div>
                        <small class="fw-medium">Last updated:</small>
                        <small class="d-block">{{ $homePageSetting->updated_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
                @else
                <div class="text-secondary">
                    <i class="ti ti-info-circle me-1"></i>
                    Configure your homepage about section and SEO settings
                </div>
                @endif
                
                <button type="submit" class="btn btn-primary" id="save-about-btn">
                    <i class="ti ti-device-floppy me-1"></i>
                    {{ $homePageSetting ? 'Update' : 'Save' }} About Section
                </button>
            </div>
        </div>
    </div>
</div>
</form>

{{-- Banners Section --}}
<div class="col-12 mt-4">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-photo me-2"></i>
            Homepage Banners
        </h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-banner-modal">
                <i class="ti ti-plus me-1"></i> Add Banner
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($banners->count() > 0)
        <div class="row g-3 p-3" id="sortable-container">
            @foreach($banners as $banner)
            <div class="col-sm-6 col-md-4 col-lg-3" data-id="{{ $banner->id }}">
                <div class="card banner-card h-100 position-relative">
                    <div class="position-relative">
                        <img src="{{ $banner->image_url }}" class="card-img-top banner-image" alt="{{ $banner->image_alt_text }}">
                        
                        {{-- Order Number --}}
                        <div class="banner-order">{{ $banner->order }}</div>
                        
                        {{-- Status Badge --}}
                        <div class="banner-status">
                            @if($banner->is_active)
                            <span class="badge bg-success text-white">Active</span>
                            @else
                            <span class="badge bg-secondary text-white">Inactive</span>
                            @endif
                        </div>
                        
                        {{-- Drag Handle --}}
                        @if($banners->count() > 1)
                        <div class="sortable-handle" title="Drag to reorder">
                            <i class="ti ti-grip-vertical"></i>
                        </div>
                        @endif
                        
                        {{-- Overlay --}}
                        <div class="banner-overlay"></div>
                        
                        {{-- Actions --}}
                        <div class="banner-actions">
                            <div class="btn-list">
                                <a href="{{ $banner->image_url }}" target="_blank" class="btn btn-sm btn-warning" title="View Image">
                                    <i class="ti ti-external-link"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-primary edit-banner-btn" 
                                        data-id="{{ $banner->id }}"
                                        data-image-url="{{ $banner->image_url }}"
                                        data-image-alt="{{ $banner->image_alt_text }}"
                                        data-is-active="{{ $banner->is_active ? '1' : '0' }}"
                                        title="Edit Banner">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $banner->id }}"
                                        data-name="{{ $banner->image_alt_text ?: 'Banner #' . $banner->id }}"
                                        data-url="{{ route('home-page.banners.destroy', $banner) }}"
                                        title="Delete Banner">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty py-5">
            <div class="empty-icon">
                <i class="ti ti-photo icon icon-lg"></i>
            </div>
            <p class="empty-title h3">No banners yet</p>
            <p class="empty-subtitle text-secondary">
                Get started by creating your first homepage banner.<br>
                Showcase your best content to welcome visitors.
            </p>
            <div class="empty-action">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-banner-modal">
                    <i class="ti ti-plus me-1"></i> Create First Banner
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
</div>

{{-- Services Section --}}
<form method="POST" action="{{ route('home-page.services.update') }}" id="services-form">
@csrf
<div class="col-12 mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ti ti-tools me-2"></i>
                Our Services Section
            </h3>
            <div class="card-actions">
                <span class="badge bg-blue-lt">
                    <i class="ti ti-info-circle me-1"></i>
                    Select services to display on home page
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($services->count() > 0)
                <div class="row g-3">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="card service-card h-100 {{ $service->show_in_home ? 'selected' : '' }}">
                                <div class="card-body p-3">
                                    <div class="form-check">
                                        <input class="form-check-input service-checkbox" type="checkbox" 
                                               name="home_services[]" value="{{ $service->id }}" 
                                               id="service_{{ $service->id }}"
                                               {{ $service->show_in_home ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="service_{{ $service->id }}">
                                            <div class="d-flex align-items-start">
                                                @if($service->icon_path)
                                                    <img src="{{ $service->icon_url }}" alt="{{ $service->icon_alt_text }}" 
                                                         class="me-3" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                                @endif
                                                <div class="flex-fill">
                                                    <strong class="d-block">{{ $service->name }}</strong>
                                                    <div class="text-muted small">{{ Str::limit($service->short_description, 80) }}</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" id="save-services-btn">
                        <i class="ti ti-device-floppy me-1"></i>
                        Update Services Selection
                    </button>
                </div>
            @else
                <div class="empty">
                    <div class="empty-icon">
                        <i class="ti ti-tools icon icon-lg"></i>
                    </div>
                    <p class="empty-title">No services available</p>
                    <p class="empty-subtitle text-muted">Create services first to display them on the home page.</p>
                </div>
            @endif
        </div>
    </div>
</div>
</form>

{{-- Company Logos Section --}}
<div class="col-12 mt-4">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-building me-2"></i>
            Company Logos
        </h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-logo-modal">
                <i class="ti ti-plus me-1"></i> Add Logo
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($companyLogos->count() > 0)
        <div class="row g-3 p-3" id="sortable-logo-container">
            @foreach($companyLogos as $logo)
            <div class="col-sm-6 col-md-4 col-lg-3" data-id="{{ $logo->id }}">
                <div class="card banner-card h-100 position-relative">
                    <div class="position-relative">
                        <div class="card-img-top d-flex align-items-center justify-content-center banner-image" 
                             style="background-color: #f8f9fa;">
                            <img src="{{ $logo->image_url }}" alt="{{ $logo->image_alt_text }}" 
                                 style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        
                        {{-- Order Number --}}
                        <div class="banner-order">{{ $logo->order }}</div>
                        
                        {{-- Status Badge --}}
                        <div class="banner-status">
                            @if($logo->is_active)
                            <span class="badge bg-success text-white">Active</span>
                            @else
                            <span class="badge bg-secondary text-white">Inactive</span>
                            @endif
                        </div>
                        
                        {{-- Drag Handle --}}
                        @if($companyLogos->count() > 1)
                        <div class="sortable-handle" title="Drag to reorder">
                            <i class="ti ti-grip-vertical"></i>
                        </div>
                        @endif
                        
                        {{-- Overlay --}}
                        <div class="banner-overlay"></div>
                        
                        {{-- Actions --}}
                        <div class="banner-actions">
                            <div class="btn-list">
                                <a href="{{ $logo->image_url }}" target="_blank" class="btn btn-sm btn-warning" title="View Image">
                                    <i class="ti ti-external-link"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-primary edit-logo-btn" 
                                        data-id="{{ $logo->id }}"
                                        data-image-url="{{ $logo->image_url }}"
                                        data-image-alt="{{ $logo->image_alt_text }}"
                                        data-is-active="{{ $logo->is_active ? '1' : '0' }}"
                                        title="Edit Logo">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $logo->id }}"
                                        data-name="{{ $logo->image_alt_text ?: 'Company Logo #' . $logo->id }}"
                                        data-url="{{ route('home-page.logos.destroy', $logo) }}"
                                        title="Delete Logo">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty py-5">
            <div class="empty-icon">
                <i class="ti ti-building icon icon-lg"></i>
            </div>
            <p class="empty-title h3">No company logos yet</p>
            <p class="empty-subtitle text-secondary">
                Get started by adding your first company logo.<br>
                Showcase your trusted partners and clients.
            </p>
            <div class="empty-action">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-logo-modal">
                    <i class="ti ti-plus me-1"></i> Add First Logo
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
</div>

{{-- Create Banner Modal --}}
<div class="modal modal-blur fade" id="create-banner-modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" action="{{ route('home-page.banners.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="ti ti-plus me-2"></i>
                Create Homepage Banner
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Banner Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="image" accept="image/*" required id="create-image-input">
                <small class="form-hint">
                    <i class="ti ti-info-circle me-1"></i>
                    Recommended: 1920x800px, Max: 15MB (JPG, PNG, WebP)
                </small>
                <div class="mt-3" id="create-image-preview"></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Image Alt Text</label>
                <input type="text" class="form-control" name="image_alt_text"
                       placeholder="Enter image description for accessibility">
            </div>
            
            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <span class="form-check-label">Active Status</span>
                </label>
                <small class="form-hint d-block">
                    <i class="ti ti-info-circle me-1"></i>
                    Only active banners will be displayed on the homepage.
                </small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="create-banner-submit-btn">
                <i class="ti ti-device-floppy me-1"></i>Create Banner
            </button>
        </div>
    </form>
</div>
</div>

{{-- Edit Banner Modal --}}
<div class="modal modal-blur fade" id="edit-banner-modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" enctype="multipart/form-data" id="edit-banner-form">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="ti ti-edit me-2"></i>
                Edit Homepage Banner
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3" id="edit-current-image-section">
                <label class="form-label">Current Image</label>
                <div class="image-preview-container">
                    <img id="edit-current-image" src="" class="img-fluid rounded">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Update Image (Optional)</label>
                <input type="file" class="form-control" name="image" accept="image/*" id="edit-image-input">
                <small class="form-hint">
                    <i class="ti ti-info-circle me-1"></i>
                    Leave empty to keep current image. Max: 15MB (JPG, PNG, WebP)
                </small>
                <div class="mt-3" id="edit-image-preview"></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Image Alt Text</label>
                <input type="text" class="form-control" name="image_alt_text" id="edit-image-alt-input">
            </div>
            
            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit-is-active">
                    <span class="form-check-label">Active Status</span>
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="edit-banner-submit-btn">
                <i class="ti ti-device-floppy me-1"></i>Update Banner
            </button>
        </div>
    </form>
</div>
</div>

{{-- Create Logo Modal --}}
<div class="modal modal-blur fade" id="create-logo-modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" action="{{ route('home-page.logos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="ti ti-plus me-2"></i>
                Add Company Logo
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Company Logo <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="image" accept="image/*" required id="create-logo-input">
                <small class="form-hint">
                    <i class="ti ti-info-circle me-1"></i>
                    Recommended: 300x150px, Max: 15MB (JPG, PNG, WebP)
                </small>
                <div class="mt-3" id="create-logo-preview"></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="image_alt_text"
                       placeholder="Enter company name for accessibility">
            </div>
            
            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    <span class="form-check-label">Active Status</span>
                </label>
                <small class="form-hint d-block">
                    <i class="ti ti-info-circle me-1"></i>
                    Only active logos will be displayed on the homepage.
                </small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="create-logo-submit-btn">
                <i class="ti ti-device-floppy me-1"></i>Add Logo
            </button>
        </div>
    </form>
</div>
</div>

{{-- Edit Logo Modal --}}
<div class="modal modal-blur fade" id="edit-logo-modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form class="modal-content" method="POST" enctype="multipart/form-data" id="edit-logo-form">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="ti ti-edit me-2"></i>
                Edit Company Logo
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3" id="edit-current-logo-section">
                <label class="form-label">Current Logo</label>
                <div class="image-preview-container text-center" style="background-color: #f8f9fa; padding: 2rem;">
                    <img id="edit-current-logo" src="" style="max-height: 150px; object-fit: contain;">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Update Logo (Optional)</label>
                <input type="file" class="form-control" name="image" accept="image/*" id="edit-logo-input">
                <small class="form-hint">
                    <i class="ti ti-info-circle me-1"></i>
                    Leave empty to keep current logo. Max: 15MB (JPG, PNG, WebP)
                </small>
                <div class="mt-3" id="edit-logo-preview"></div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="image_alt_text" id="edit-logo-alt-input">
            </div>
            
            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit-logo-is-active">
                    <span class="form-check-label">Active Status</span>
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="edit-logo-submit-btn">
                <i class="ti ti-device-floppy me-1"></i>Update Logo
            </button>
        </div>
    </form>
</div>
</div>

{{-- Include Global Delete Modal --}}
@include('components.delete-modal')

@endsection

@push('scripts')
@include('components.scripts.wysiwyg')
@include('components.toast')
@include('components.alert')

@if(session('success'))
<script>
    showToast('{{ session('success') }}', 'success');
</script>
@endif
@if(session('error'))
<script>
    showToast('{{ session('error') }}','error');
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    setupSortable();
    setupModals();
    setupImagePreviews();
    setupFormSubmissions();
    setupServiceCards();
    setupLogoManagement();
    setupToggleButtons();
});

function setupSortable() {
    @if($banners->count() > 1)
    const sortableContainer = document.getElementById('sortable-container');
    if (sortableContainer) {
        new Sortable(sortableContainer, {
            handle: '.sortable-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onStart: function(evt) {
                evt.item.style.opacity = '0.7';
            },
            onEnd: function(evt) {
                evt.item.style.opacity = '1';
                
                const orders = [];
                const items = sortableContainer.children;
                
                Array.from(items).forEach((item, index) => {
                    const itemId = item.getAttribute('data-id');
                    if (itemId) {
                        orders.push({
                            id: itemId,
                            order: index + 1
                        });
                    }
                });
                
                updateVisualOrderNumbers();
                updateBannerOrder(orders);
            }
        });
    }
    @endif
}

function updateBannerOrder(orders) {
    fetch('{{ route('home-page.banners.update-order') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ orders: orders })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateVisualOrderNumbers();
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
            location.reload();
        }
    })
    .catch(error => {
        showToast('Failed to update banner order', 'error');
        location.reload();
    });
}

function updateVisualOrderNumbers() {
    const sortableContainer = document.getElementById('sortable-container');
    if (sortableContainer) {
        const items = sortableContainer.children;
        Array.from(items).forEach((item, index) => {
            const orderElement = item.querySelector('.banner-order');
            if (orderElement) {
                orderElement.textContent = index + 1;
            }
        });
    }
}

function setupModals() {
    // Edit banner button handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-banner-btn') || e.target.closest('.edit-banner-btn')) {
            const button = e.target.classList.contains('edit-banner-btn') ? e.target : e.target.closest('.edit-banner-btn');
            const bannerId = button.getAttribute('data-id');
            
            document.getElementById('edit-banner-form').action = `{{ url('home-page/banners') }}/${bannerId}`;
            document.getElementById('edit-image-alt-input').value = button.getAttribute('data-image-alt') || '';
            document.getElementById('edit-is-active').checked = button.getAttribute('data-is-active') === '1';
            document.getElementById('edit-current-image').src = button.getAttribute('data-image-url');
            
            const modal = new bootstrap.Modal(document.getElementById('edit-banner-modal'));
            modal.show();
        }
    });

    // Edit logo button handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-logo-btn') || e.target.closest('.edit-logo-btn')) {
            const button = e.target.classList.contains('edit-logo-btn') ? e.target : e.target.closest('.edit-logo-btn');
            const logoId = button.getAttribute('data-id');
            
            document.getElementById('edit-logo-form').action = `{{ url('home-page/logos') }}/${logoId}`;
            document.getElementById('edit-logo-alt-input').value = button.getAttribute('data-image-alt') || '';
            document.getElementById('edit-logo-is-active').checked = button.getAttribute('data-is-active') === '1';
            document.getElementById('edit-current-logo').src = button.getAttribute('data-image-url');
            
            const modal = new bootstrap.Modal(document.getElementById('edit-logo-modal'));
            modal.show();
        }
    });

    // Reset modals when closed
    document.getElementById('create-banner-modal').addEventListener('hidden.bs.modal', function() {
        const form = this.querySelector('form');
        form.reset();
        document.getElementById('create-image-preview').innerHTML = '';
        resetSubmitButton('create-banner-submit-btn', 'Create Banner');
    });

    document.getElementById('edit-banner-modal').addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('edit-banner-form');
        form.reset();
        document.getElementById('edit-image-preview').innerHTML = '';
        resetSubmitButton('edit-banner-submit-btn', 'Update Banner');
    });

    document.getElementById('create-logo-modal').addEventListener('hidden.bs.modal', function() {
        const form = this.querySelector('form');
        form.reset();
        document.getElementById('create-logo-preview').innerHTML = '';
        resetSubmitButton('create-logo-submit-btn', 'Add Logo');
    });

    document.getElementById('edit-logo-modal').addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('edit-logo-form');
        form.reset();
        document.getElementById('edit-logo-preview').innerHTML = '';
        resetSubmitButton('edit-logo-submit-btn', 'Update Logo');
    });
}

function setupImagePreviews() {
    const aboutInput = document.getElementById('about-input');
    const createImageInput = document.getElementById('create-image-input');
    const editImageInput = document.getElementById('edit-image-input');
    const createLogoInput = document.getElementById('create-logo-input');
    const editLogoInput = document.getElementById('edit-logo-input');
    
    if (aboutInput) {
        aboutInput.addEventListener('change', function(e) {
            handleImagePreview(e, 'about-preview', aboutInput, 15);
        });
    }
    
    if (createImageInput) {
        createImageInput.addEventListener('change', function(e) {
            handleImagePreview(e, 'create-image-preview', createImageInput, 15);
        });
    }
    
    if (editImageInput) {
        editImageInput.addEventListener('change', function(e) {
            handleImagePreview(e, 'edit-image-preview', editImageInput, 15);
        });
    }

    if (createLogoInput) {
        createLogoInput.addEventListener('change', function(e) {
            handleImagePreview(e, 'create-logo-preview', createLogoInput, 15);
        });
    }
    
    if (editLogoInput) {
        editLogoInput.addEventListener('change', function(e) {
            handleImagePreview(e, 'edit-logo-preview', editLogoInput, 15);
        });
    }
}

function setupLogoImagePreview(index) {
    const logoInput = document.getElementById(`logo-input-${index}`);
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            handleImagePreview(e, `logo-preview-${index}`, logoInput, 15);
        });
    }
}

function handleImagePreview(event, previewId, inputElement, maxSizeMB) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById(previewId);
    
    if (file) {
        const maxSize = maxSizeMB * 1024 * 1024;
        if (file.size > maxSize) {
            showAlert(inputElement, 'danger', `File size too large. Maximum ${maxSizeMB}MB allowed.`);
            inputElement.value = '';
            return;
        }

        if (!file.type.startsWith('image/')) {
            showAlert(inputElement, 'danger', 'Please select a valid image file.');
            inputElement.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <div class="card border-success">
                    <img src="${e.target.result}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-fill">
                                <h6 class="card-title mb-1">${file.name}</h6>
                                <small class="text-secondary d-block">
                                    ${(file.size / 1024 / 1024).toFixed(2)} MB
                                </small>
                                <small class="text-success">
                                    <i class="ti ti-check me-1"></i>
                                    Ready to upload
                                </small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearImagePreview('${previewId}', '${inputElement.id}')">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.innerHTML = '';
    }
}

function setupFormSubmissions() {
    // About Form
    const aboutForm = document.getElementById('about-form');
    const saveAboutBtn = document.getElementById('save-about-btn');
    
    if (aboutForm && saveAboutBtn) {
        aboutForm.addEventListener('submit', function(e) {
            // Validate about description from editor
            try {
                const aboutEditorContent = hugeRTE.get('editor').getContent();
                const aboutError = document.getElementById('about-description-error');
                
                if (!aboutEditorContent.trim() || aboutEditorContent.trim() === '<p></p>' || aboutEditorContent.trim() === '<p><br></p>') {
                    e.preventDefault();
                    aboutError.textContent = 'About description is required.';
                    aboutError.style.display = 'block';
                    document.getElementById('editor').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                } else {
                    aboutError.style.display = 'none';
                }
            } catch (err) {
                console.warn('About editor validation error:', err);
            }

            saveAboutBtn.disabled = true;
            saveAboutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            aboutForm.classList.add('loading');
        });
    }

    // Services Form
    const servicesForm = document.getElementById('services-form');
    const saveServicesBtn = document.getElementById('save-services-btn');
    
    if (servicesForm && saveServicesBtn) {
        servicesForm.addEventListener('submit', function(e) {
            saveServicesBtn.disabled = true;
            saveServicesBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        });
    }

    // Logos Form
    const logosForm = document.getElementById('logos-form');
    const saveLogosBtn = document.getElementById('save-logos-btn');
    
    if (logosForm && saveLogosBtn) {
        logosForm.addEventListener('submit', function(e) {
            saveLogosBtn.disabled = true;
            saveLogosBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }

    // Create Banner Form
    const createBannerForm = document.querySelector('#create-banner-modal form');
    const createBannerSubmitBtn = document.getElementById('create-banner-submit-btn');

    if (createBannerForm && createBannerSubmitBtn) {
        createBannerForm.addEventListener('submit', function(e) {
            const imageInput = document.getElementById('create-image-input');
            if (!imageInput.files.length) {
                e.preventDefault();
                showAlert(imageInput, 'danger', 'Please select an image for the banner.');
                return false;
            }

            createBannerSubmitBtn.disabled = true;
            createBannerSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
        });
    }

    // Edit Banner Form
    const editBannerForm = document.getElementById('edit-banner-form');
    const editBannerSubmitBtn = document.getElementById('edit-banner-submit-btn');

    if (editBannerForm && editBannerSubmitBtn) {
        editBannerForm.addEventListener('submit', function(e) {
            editBannerSubmitBtn.disabled = true;
            editBannerSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        });
    }
}

function setupServiceCards() {
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('service-checkbox')) {
            const card = e.target.closest('.service-card');
            if (e.target.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }
    });
}

function setupLogoManagement() {
    let logoIndex = 1;

    // Add logo
    document.getElementById('add-logo').addEventListener('click', function() {
        const container = document.getElementById('logo-container');
        const newLogo = `
            <div class="logo-item">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Company Logo</label>
                            <input type="file" class="form-control" name="company_logo_images[]" accept="image/*" id="logo-input-${logoIndex}">
                            <small class="form-hint">
                                Recommended: 300x150px, Max: 15MB (JPG, PNG, WebP)
                            </small>
                            <div class="mt-3" id="logo-preview-${logoIndex}"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_logo_alt_texts[]" placeholder="Company name">
                        </div>
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="company_logo_is_active[]" value="1" checked>
                                <span class="form-check-label">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-logo remove-item-btn">
                    <i class="ti ti-trash"></i> Remove Logo
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newLogo);
        setupLogoImagePreview(logoIndex);
        logoIndex++;
    });

    // Remove logo
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-logo') || e.target.closest('.remove-logo')) {
            const logoItem = e.target.closest('.logo-item');
            logoItem.remove();
        }
    });

    // Delete existing logo
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-logo') || e.target.closest('.delete-logo')) {
            e.preventDefault();
            const button = e.target.classList.contains('delete-logo') ? e.target : e.target.closest('.delete-logo');
            const logoId = button.getAttribute('data-logo-id');
            const logoName = button.getAttribute('data-name');
            
            const deleteForm = document.getElementById('delete-form');
            const deleteMessage = document.getElementById('delete-message');
            
            deleteForm.action = `{{ route('home-page.index') }}/logo/${logoId}`;
            deleteMessage.innerHTML = `Do you really want to delete "<strong>${logoName}</strong>"? This process cannot be undone.`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
            deleteModal.show();
        }
    });
}

function setupToggleButtons() {
    // Toggle logo status
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('logo-status-toggle')) {
            const logoId = e.target.getAttribute('data-logo-id');
            
            fetch(`{{ route('home-page.index') }}/logo/${logoId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                    e.target.checked = !e.target.checked;
                }
            })
            .catch(error => {
                showToast('An error occurred while updating logo status.', 'error');
                e.target.checked = !e.target.checked;
            });
        }
    });
}

function resetSubmitButton(buttonId, originalText) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = false;
        button.innerHTML = `<i class="ti ti-device-floppy me-1"></i>${originalText}`;
    }
}

// Clear image preview function
window.clearImagePreview = function(previewId, inputId) {
    document.getElementById(inputId).value = '';
    document.getElementById(previewId).innerHTML = '';
};
</script>
@endpush