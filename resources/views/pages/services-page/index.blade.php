@extends('layouts.main')

@section('title', 'Services Page Management')

@push('styles')
<style>
    .banner-preview {
        max-height: 300px;
        overflow: hidden;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    
    .banner-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .stats-card {
        background: linear-gradient(135deg, var(--tblr-primary) 0%, var(--tblr-primary-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: none;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .loading {
        pointer-events: none;
        opacity: 0.6;
    }
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">Services Page Management</h2>
        <div class="page-subtitle">Manage services page settings and service listings</div>
    </div>
    <div class="btn-list">
        @if($servicesPage)
        <a href="{{ route('services.services.index') }}" class="btn btn-primary">
            <i class="ti ti-list me-1"></i> Manage Services
        </a>
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- Services Statistics --}}
@if($servicesPage)
<div class="row mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="stats-number">{{ $servicesCount }}</div>
            <div class="text-white-75">Total Services</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ $activeServicesCount }}</div>
                <div class="text-white-75">Active Services</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ $homeServicesCount }}</div>
                <div class="text-white-75">Shown in Home</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="stats-number">{{ $servicesCount - $activeServicesCount }}</div>
                <div class="text-white-75">Inactive Services</div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Banner Settings Form --}}
<form method="POST" action="{{ route('services.updateOrCreate') }}" enctype="multipart/form-data" id="banner-form">
    @csrf
    <div class="row g-3">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Banner Section --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo me-2"></i>
                        Banner Settings
                    </h3>
                    @if($servicesPage)
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
                        {{-- Current Banner Preview --}}
                        @if($servicesPage && $servicesPage->banner_image_url)
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Current Banner</label>
                                <div class="banner-preview">
                                    <img src="{{ $servicesPage->banner_image_url }}" class="banner-image" alt="Current Banner">
                                </div>
                                <small class="text-secondary mt-1 d-block">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Current banner image
                                </small>
                            </div>
                        </div>
                        @endif
                        
                        {{-- Banner Form Fields --}}
                        <div class="{{ $servicesPage && $servicesPage->banner_image_url ? 'col-md-8' : 'col-12' }}">
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $servicesPage ? 'Update Banner Image' : 'Banner Image' }}
                                    @if(!$servicesPage)<span class="text-danger">*</span>@endif
                                </label>
                                <input type="file" class="form-control @error('banner_image') is-invalid @enderror" 
                                       name="banner_image" accept="image/*" {{ $servicesPage ? '' : 'required' }} id="banner-input">
                                @error('banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    <i class="ti ti-info-circle me-1"></i>
                                    @if($servicesPage)Leave empty to keep current image. @endif
                                    Recommended: 1920x800px, Max: 15MB (JPG, PNG, WebP)
                                </small>
                                <div class="mt-3" id="banner-preview"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Banner Alt Text</label>
                                <input type="text" class="form-control @error('banner_image_alt_text') is-invalid @enderror" 
                                       name="banner_image_alt_text" value="{{ old('banner_image_alt_text', $servicesPage->banner_image_alt_text ?? '') }}"
                                       placeholder="Enter image description for accessibility">
                                @error('banner_image_alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    Describe the image for screen readers and SEO.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO Settings --}}
            @include('components.seo-meta-form', ['data' => $servicesPage, 'type' => $servicesPage ? 'edit' : 'create'])
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            @if($servicesPage)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-bolt me-2"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('services.services.index') }}" class="btn btn-outline-primary">
                            <i class="ti ti-list me-1"></i>
                            Manage Services
                        </a>
                        <a href="{{ route('services.services.create') }}" class="btn btn-outline-success">
                            <i class="ti ti-plus me-1"></i>
                            Create New Service
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Page Info --}}
            @if($servicesPage)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-info-circle me-2"></i>
                        Page Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <small class="text-secondary">Page Status:</small>
                                <div class="fw-bold text-success">
                                    <i class="ti ti-check me-1"></i>
                                    Configured & Ready
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <small class="text-secondary">Last Updated:</small>
                                <div>{{ $servicesPage->updated_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-0">
                                <small class="text-secondary">Created:</small>
                                <div>{{ $servicesPage->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Setup Required
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="ti ti-settings icon icon-lg text-warning"></i>
                        </div>
                        <p class="text-secondary mb-3">
                            Configure your services page banner and SEO settings to get started.
                        </p>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 25%"></div>
                        </div>
                        <small class="text-secondary mt-2 d-block">
                            Page setup: 25% complete
                        </small>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Submit Button --}}
        <div class="col-12">
            <div class="card">
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        @if($servicesPage)
                        <div class="d-flex align-items-center text-secondary">
                            <i class="ti ti-clock me-2"></i>
                            <div>
                                <small class="fw-medium">Last saved:</small>
                                <small class="d-block">{{ $servicesPage->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @else
                        <div class="text-secondary">
                            <i class="ti ti-info-circle me-1"></i>
                            Create your Services page settings to continue
                        </div>
                        @endif
                        
                        <div class="btn-list">
                            <button type="submit" class="btn btn-primary" id="save-banner-btn">
                                <i class="ti ti-device-floppy me-1"></i>
                                {{ $servicesPage ? 'Update' : 'Create' }} Page Settings
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
    document.addEventListener("DOMContentLoaded", function () {
        setupImagePreviews();
        setupCharacterCounters();
        setupFormSubmission();
    });

    function setupImagePreviews() {
        const bannerInput = document.getElementById('banner-input');
        
        if (bannerInput) {
            bannerInput.addEventListener('change', function(e) {
                handleImagePreview(e, 'banner-preview', bannerInput, 15);
            });
        }
    }

    function setupCharacterCounters() {
        setupCharacterCounter('meta-title-input', 'meta-title-count', 255);
        setupCharacterCounter('meta-desc-input', 'meta-desc-count', 500);
        setupCharacterCounter('meta-keywords-input', 'meta-keywords-count', 255);
    }

    function setupCharacterCounter(inputId, countId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(countId);
        
        if (input && counter) {
            input.addEventListener('input', function() {
                const currentLength = this.value.length;
                counter.textContent = currentLength;
                
                const percentage = (currentLength / maxLength) * 100;
                const parent = counter.parentElement;
                
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
    }

    function setupFormSubmission() {
        const bannerForm = document.getElementById('banner-form');
        const saveBannerBtn = document.getElementById('save-banner-btn');
        
        if (bannerForm && saveBannerBtn) {
            bannerForm.addEventListener('submit', function(e) {
                saveBannerBtn.disabled = true;
                saveBannerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                bannerForm.classList.add('loading');
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
                                <small class="text-success">
                                    <i class="ti ti-check me-1"></i>
                                    Ready to upload
                                </small>
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

    // Clear image preview function
    window.clearImagePreview = function(previewId, inputId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).innerHTML = '';
    };
</script>
@endpush