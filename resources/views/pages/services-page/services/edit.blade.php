@extends('layouts.main')

@section('title', 'Edit Service')

@push('styles')
<style>
    .icon-preview {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f9fa;
        padding: 10px;
    }
    
    .current-media-preview {
        max-height: 200px;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .loading {
        pointer-events: none;
        opacity: 0.6;
    }
    
    .layout-preview {
        min-height: 200px;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .layout-preview.image-right {
        flex-direction: row-reverse;
    }
    
    .preview-image-placeholder {
        flex: 0 0 120px;
        height: 80px;
        background: #dee2e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .preview-content-placeholder {
        flex: 1;
    }
    
    .preview-title {
        height: 20px;
        background: var(--tblr-primary);
        border-radius: 4px;
        margin-bottom: 10px;
        width: 70%;
    }
    
    .preview-text {
        height: 12px;
        background: #dee2e6;
        border-radius: 4px;
        margin-bottom: 8px;
    }
    
    .preview-text:last-child {
        width: 50%;
    }
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">Edit Service</h2>
        <div class="page-subtitle">{{ $service->name }}</div>
    </div>
    <div class="btn-list">
        <a href="{{ route('services.services.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Services
        </a>
    </div>
</div>
@endsection

@section('content')

<form action="{{ route('services.services.update', $service) }}" method="POST" enctype="multipart/form-data" id="service-form">
    @csrf
    @method('PUT')
    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Basic Information --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit me-2"></i>
                        Service Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Service Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $service->name) }}" required
                                       placeholder="Enter service name (e.g., Construction Management, Interior Design)">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    <span id="name-count">{{ strlen($service->name ?? '') }}</span>/255 characters
                                </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Service Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                          name="short_description" rows="6" required
                                          placeholder="Enter detailed description of the service...">{{ old('short_description', $service->short_description) }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">Describe the service features, benefits, and what makes it unique.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Current Media --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo me-2"></i>
                        Current Media
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Current Icon --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Icon</label>
                                <div class="d-flex align-items-start gap-3">
                                    <img src="{{ $service->icon_url }}" class="icon-preview" alt="{{ $service->name }}">
                                    <div class="flex-fill">
                                        <div class="fw-medium">Current Service Icon</div>
                                        <small class="text-secondary d-block">
                                            {{ $service->icon_alt_text ?: 'No alt text provided' }}
                                        </small>
                                        <a href="{{ $service->icon_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="ti ti-external-link me-1"></i> View Full Size
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Current Image --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div class="card border">
                                    <img src="{{ $service->image_url }}" class="card-img-top current-media-preview" alt="{{ $service->name }}">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-secondary fw-medium">Service Image</small>
                                            <a href="{{ $service->image_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-external-link"></i>
                                            </a>
                                        </div>
                                        @if($service->image_alt_text)
                                        <small class="text-secondary d-block mt-1">Alt: {{ $service->image_alt_text }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Update Media --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-upload me-2"></i>
                        Update Media
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Update Icon --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Update Icon (Optional)</label>
                                <input type="file" class="form-control @error('icon') is-invalid @enderror" 
                                       name="icon" accept="image/*" id="icon-input">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Leave empty to keep current icon. Max: 2MB
                                </small>
                                <div class="mt-3" id="icon-preview"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Icon Alt Text</label>
                                <input type="text" class="form-control @error('icon_alt_text') is-invalid @enderror" 
                                       name="icon_alt_text" value="{{ old('icon_alt_text', $service->icon_alt_text) }}"
                                       placeholder="Enter icon description for accessibility">
                                @error('icon_alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Update Image --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Update Image (Optional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       name="image" accept="image/*" id="image-input">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Leave empty to keep current image. Max: 15MB
                                </small>
                                <div class="mt-3" id="image-preview"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Image Alt Text</label>
                                <input type="text" class="form-control @error('image_alt_text') is-invalid @enderror" 
                                       name="image_alt_text" value="{{ old('image_alt_text', $service->image_alt_text) }}"
                                       placeholder="Enter image description for accessibility">
                                @error('image_alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Layout Settings --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-layout me-2"></i>
                        Layout Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Service Layout <span class="text-danger">*</span></label>
                        <div class="form-selectgroup form-selectgroup-boxes">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="layout" value="image_left" class="form-selectgroup-input" 
                                       {{ old('layout', $service->layout) === 'image_left' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label d-flex align-items-center p-3">
                                    <span class="me-3">
                                        <span class="form-selectgroup-check"></span>
                                    </span>
                                    <span class="form-selectgroup-label-content">
                                        <i class="ti ti-layout-align-left icon mb-2"></i>
                                        <span class="form-selectgroup-title strong mb-1">Image Left</span>
                                        <span class="d-block text-secondary">Image on left, content on right</span>
                                    </span>
                                </span>
                            </label>
                            <label class="form-selectgroup-item mt-0 mt-md-2">
                                <input type="radio" name="layout" value="image_right" class="form-selectgroup-input" 
                                       {{ old('layout', $service->layout) === 'image_right' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label d-flex align-items-center p-3">
                                    <span class="me-3">
                                        <span class="form-selectgroup-check"></span>
                                    </span>
                                    <span class="form-selectgroup-label-content">
                                        <i class="ti ti-layout-align-right icon mb-2"></i>
                                        <span class="form-selectgroup-title strong mb-1">Image Right</span>
                                        <span class="d-block text-secondary">Content on left, image on right</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        @error('layout')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">
                            Current layout: <strong>{{ $service->layout === 'image_left' ? 'Image Left' : 'Image Right' }}</strong>
                        </small>
                    </div>
                </div>
            </div>

            {{-- Service Options --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-settings me-2"></i>
                        Service Options
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <span class="form-check-label">Active Status</span>
                        </label>
                        @error('is_active')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-hint d-block">
                            <i class="ti ti-info-circle me-1"></i>
                            Only active services will be displayed on the website.
                        </small>
                        @if($service->is_active)
                        <small class="text-success d-block mt-1">
                            <i class="ti ti-check me-1"></i>
                            This service is currently active and visible.
                        </small>
                        @else
                        <small class="text-warning d-block mt-1">
                            <i class="ti ti-alert-triangle me-1"></i>
                            This service is currently inactive and hidden.
                        </small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="show_in_home" value="1" 
                                   {{ old('show_in_home', $service->show_in_home) ? 'checked' : '' }}>
                            <span class="form-check-label">Show in Home Page</span>
                        </label>
                        @error('show_in_home')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-hint d-block">
                            <i class="ti ti-home me-1"></i>
                            Display this service on the homepage services section.
                        </small>
                        @if($service->show_in_home)
                        <small class="text-info d-block mt-1">
                            <i class="ti ti-star me-1"></i>
                            This service is featured on the homepage.
                        </small>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Layout Preview --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-eye me-2"></i>
                        Layout Preview
                    </h3>
                </div>
                <div class="card-body">
                    <div class="layout-preview" id="layout-preview">
                        <div class="preview-image-placeholder" id="preview-image">
                            <i class="ti ti-photo"></i>
                        </div>
                        <div class="preview-content-placeholder" id="preview-content">
                            <div class="preview-title"></div>
                            <div class="preview-text"></div>
                            <div class="preview-text"></div>
                            <div class="preview-text"></div>
                        </div>
                    </div>
                    <small class="text-secondary mt-2 d-block text-center">
                        Preview of how the service will appear on the page
                    </small>
                </div>
            </div>
            
            {{-- Service Meta --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-info-square me-2"></i>
                        Service Meta
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <small class="text-secondary">Service Slug:</small>
                                <div class="fw-bold font-monospace">{{ $service->slug }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <small class="text-secondary">Order Position:</small>
                                <div class="fw-bold">#{{ $service->order }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <small class="text-secondary">Related Projects:</small>
                                <div class="fw-bold">{{ $service->projects()->count() }} projects</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <small class="text-secondary">Created:</small>
                                <div>{{ $service->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-0">
                                <small class="text-secondary">Last Updated:</small>
                                <div>{{ $service->updated_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="col-12">
            <div class="card">
                <div class="card-footer bg-transparent text-end">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-secondary">
                                <i class="ti ti-clock me-1"></i>
                                Last saved: {{ $service->updated_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                        <div class="btn-list">
                            <a href="{{ route('services.services.index') }}" class="btn btn-link">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="ti ti-device-floppy me-1"></i>
                                Update Service
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
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
    document.addEventListener('DOMContentLoaded', function() {
        setupImagePreviews();
        setupCharacterCounter();
        setupLayoutPreview();
        setupFormSubmission();
    });

    function setupImagePreviews() {
        const iconInput = document.getElementById('icon-input');
        const imageInput = document.getElementById('image-input');
        
        if (iconInput) {
            iconInput.addEventListener('change', function(e) {
                handleImagePreview(e, 'icon-preview', iconInput, 2, 'icon');
            });
        }
        
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                handleImagePreview(e, 'image-preview', imageInput, 15, 'image');
            });
        }
    }

    function handleImagePreview(event, previewId, inputElement, maxSizeMB, type) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById(previewId);
        
        if (file) {
            // Validate file size
            const maxSize = maxSizeMB * 1024 * 1024;
            if (file.size > maxSize) {
                showAlert(inputElement, 'danger', `File size too large. Maximum ${maxSizeMB}MB allowed.`);
                inputElement.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showAlert(inputElement, 'danger', 'Please select a valid image file.');
                inputElement.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                if (type === 'icon') {
                    previewContainer.innerHTML = `
                        <div class="d-flex align-items-start gap-3">
                            <img src="${e.target.result}" class="icon-preview" alt="Preview">
                            <div class="flex-fill">
                                <h6 class="mb-1">${file.name}</h6>
                                <small class="text-secondary d-block">
                                    ${(file.size / 1024 / 1024).toFixed(2)} MB
                                </small>
                                <small class="text-warning">
                                    <i class="ti ti-alert-triangle me-1"></i>This will replace the current icon
                                </small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearImagePreview('${previewId}', '${inputElement.id}')">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    `;
                } else {
                    previewContainer.innerHTML = `
                        <div class="card border-warning">
                            <img src="${e.target.result}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title mb-1">${file.name}</h6>
                                        <small class="text-secondary">
                                            ${(file.size / 1024 / 1024).toFixed(2)} MB
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearImagePreview('${previewId}', '${inputElement.id}')">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="ti ti-alert-triangle me-1"></i>This will replace the current image
                                    </small>
                                </div>
                            </div>
                        </div>
                    `;
                }
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = '';
        }
    }

    function setupCharacterCounter() {
        const nameInput = document.querySelector('input[name="name"]');
        const nameCount = document.getElementById('name-count');
        
        nameInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            nameCount.textContent = currentLength;
            
            const percentage = (currentLength / 255) * 100;
            const parent = nameCount.parentElement;
            
            if (percentage > 90) {
                parent.classList.add('text-danger');
                parent.classList.remove('text-warning');
            } else if (percentage > 80) {
                parent.classList.add('text-warning');
                parent.classList.remove('text-danger');
            } else {
                parent.classList.remove('text-warning', 'text-danger');
            }
        });
    }

    function setupLayoutPreview() {
        const layoutRadios = document.querySelectorAll('input[name="layout"]');
        const layoutPreview = document.getElementById('layout-preview');
        
        layoutRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'image_left') {
                    layoutPreview.classList.remove('image-right');
                } else {
                    layoutPreview.classList.add('image-right');
                }
            });
        });
        
        // Set initial preview based on current value
        const checkedLayout = document.querySelector('input[name="layout"]:checked');
        if (checkedLayout) {
            checkedLayout.dispatchEvent(new Event('change'));
        }
    }

    function setupFormSubmission() {
        const form = document.getElementById('service-form');
        const submitBtn = document.getElementById('submit-btn');
        
        form.addEventListener('submit', function(e) {
            let hasError = false;
            
            // Validate name
            const nameInput = document.querySelector('input[name="name"]');
            if (!nameInput.value.trim()) {
                e.preventDefault();
                nameInput.classList.add('is-invalid');
                nameInput.focus();
                showAlert(nameInput, 'danger', 'Service name is required.');
                hasError = true;
            }
            
            // Validate description
            const descriptionInput = document.querySelector('textarea[name="short_description"]');
            if (!descriptionInput.value.trim()) {
                e.preventDefault();
                descriptionInput.classList.add('is-invalid');
                if (!hasError) descriptionInput.focus();
                showAlert(descriptionInput, 'danger', 'Service description is required.');
                hasError = true;
            }
            
            if (!hasError) {
                // Add loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Service...';
                form.classList.add('loading');
            }
        });
        
        // Clear validation errors on input
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                // Remove alert messages
                const alerts = document.querySelectorAll('.alert-custom');
                alerts.forEach(alert => alert.remove());
            });
        });
    }

    // Clear image preview function
    window.clearImagePreview = function(previewId, inputId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).innerHTML = '';
    };
</script>
@endpush