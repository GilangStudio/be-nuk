@extends('layouts.main')

@section('title', 'About Page Management')

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
    
    .thumbnail-preview {
        max-height: 200px;
        overflow: hidden;
        border-radius: 8px;
        background-color: #f8f9fa;
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

    .item-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .item-image {
        height: 150px;
        object-fit: cover;
        background-color: #f8f9fa;
    }
    
    .item-icon {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f9fa;
        padding: 10px;
    }
    
    .stats-preview {
        background: var(--tblr-primary);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">About Page Management</h2>
        <div class="page-subtitle">Manage about page content, certifications, and sections</div>
    </div>
    <div class="btn-list">
        <a href="{{ route('about-page.certifications.index') }}" class="btn btn-outline-primary">
            <i class="ti ti-certificate me-1"></i> Certifications
        </a>
        <a href="{{ route('about-page.what-different.index') }}" class="btn btn-outline-success">
            <i class="ti ti-star me-1"></i> What Different
        </a>
        <a href="{{ route('about-page.why-choose.index') }}" class="btn btn-outline-info">
            <i class="ti ti-heart me-1"></i> Why Choose
        </a>
    </div>
</div>
@endsection

@section('content')

<form method="POST" action="{{ route('about-page.updateOrCreate') }}" enctype="multipart/form-data" id="main-form">
    @csrf
    <div class="row g-3">
        
        {{-- Left Column --}}
        <div class="col-lg-8">
            
            {{-- Banner Section --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo me-2"></i>
                        Banner Section
                    </h3>
                    @if($aboutPage)
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
                        @if($aboutPage && $aboutPage->banner_image_url)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Current Banner</label>
                                <div class="banner-preview">
                                    <img src="{{ $aboutPage->banner_image_url }}" class="banner-image" alt="Current Banner">
                                </div>
                                <small class="text-secondary mt-1 d-block">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Current banner image
                                </small>
                            </div>
                        </div>
                        @endif
                        
                        <div class="{{ $aboutPage && $aboutPage->banner_image_url ? 'col-md-8' : 'col-12' }}">
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $aboutPage ? 'Update Banner Image' : 'Banner Image' }}
                                    @if(!$aboutPage)<span class="text-danger">*</span>@endif
                                </label>
                                <input type="file" class="form-control @error('banner_image') is-invalid @enderror" 
                                       name="banner_image" accept="image/*" {{ $aboutPage ? '' : 'required' }} id="banner-input">
                                @error('banner_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    @if($aboutPage)Leave empty to keep current image. @endif
                                    Recommended: 1920x800px, Max: 15MB (JPG, PNG, WebP)
                                </small>
                                <div class="mt-3" id="banner-preview"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Banner Alt Text</label>
                                <input type="text" class="form-control @error('banner_image_alt_text') is-invalid @enderror" 
                                       name="banner_image_alt_text" value="{{ old('banner_image_alt_text', $aboutPage->banner_image_alt_text ?? '') }}"
                                       placeholder="Enter image description for accessibility">
                                @error('banner_image_alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- About Section --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-info-circle me-2"></i>
                        About Section
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">About Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('about_title') is-invalid @enderror" 
                               name="about_title" value="{{ old('about_title', $aboutPage->about_title ?? '') }}" 
                               required placeholder="Enter about title">
                        @error('about_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">About Description <span class="text-danger">*</span></label>
                        <textarea name="about_description" id="about-editor" class="form-control @error('about_description') is-invalid @enderror" 
                                  required>{{ old('about_description', $aboutPage->about_description ?? '') }}</textarea>
                        @error('about_description')
                            <div class="invalid-feedback" id="about-description-error-server">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="about-description-error" style="display: none;"></div>
                    </div>

                    @if($aboutPage && $aboutPage->about_image_path)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="thumbnail-preview">
                            <img src="{{ $aboutPage->about_image_url }}" class="img-fluid rounded" alt="Current about image">
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">
                            About Image @if(!$aboutPage)<span class="text-danger">*</span>@endif
                        </label>
                        <input type="file" class="form-control @error('about_image') is-invalid @enderror" 
                                name="about_image" accept="image/*" {{ $aboutPage ? '' : 'required' }} id="about-input">
                        @error('about_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">
                            @if($aboutPage)Leave empty to keep current image. @endif
                            Recommended: 800x600px, Max: 15MB
                        </small>
                        <div class="mt-3" id="about-preview"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image Alt Text</label>
                        <input type="text" class="form-control" name="about_image_alt_text" 
                                value="{{ old('about_image_alt_text', $aboutPage->about_image_alt_text ?? '') }}" 
                                placeholder="Image description">
                    </div>
                    
                </div>
            </div>

            {{-- Description Section --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-file-text me-2"></i>
                        Description Section
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Description Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('description_title') is-invalid @enderror" 
                               name="description_title" value="{{ old('description_title', $aboutPage->description_title ?? '') }}" 
                               required placeholder="Enter description title">
                        @error('description_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description Content <span class="text-danger">*</span></label>
                        <textarea name="description_content" id="description-editor" class="form-control @error('description_content') is-invalid @enderror" 
                                  required>{{ old('description_content', $aboutPage->description_content ?? '') }}</textarea>
                        @error('description_content')
                            <div class="invalid-feedback" id="description-content-error-server">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="description-content-error-custom" style="display: none;"></div>
                    </div>

                    @if($aboutPage && $aboutPage->description_image_path)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="thumbnail-preview">
                            <img src="{{ $aboutPage->description_image_url }}" class="img-fluid rounded" alt="Current description image">
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Description Image @if(!$aboutPage)<span class="text-danger">*</span>@endif
                        </label>
                        <input type="file" class="form-control @error('description_image') is-invalid @enderror" 
                                name="description_image" accept="image/*" {{ $aboutPage ? '' : 'required' }} id="description-input">
                        @error('description_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">
                            @if($aboutPage)Leave empty to keep current image. @endif
                            Recommended: 800x600px, Max: 15MB
                        </small>
                        <div class="mt-3" id="description-preview"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image Alt Text</label>
                        <input type="text" class="form-control" name="description_image_alt_text" 
                                value="{{ old('description_image_alt_text', $aboutPage->description_image_alt_text ?? '') }}" 
                                placeholder="Image description">
                    </div>

                </div>
            </div>

            {{-- SEO Settings --}}
            @include('components.seo-meta-form', ['data' => $aboutPage, 'type' => $aboutPage ? 'edit' : 'create'])
            
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            
            {{-- Quick Overview --}}
            @if($aboutPage)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-eye me-2"></i>
                        Quick Overview
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-sm bg-green-lt">
                                        <i class="ti ti-certificate"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="font-weight-medium">{{ $certifications->count() }} Certifications</div>
                                    <div class="text-secondary">{{ $certifications->where('is_active', true)->count() }} active</div>
                                </div>
                                <div>
                                    <a href="{{ route('about-page.certifications.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-sm bg-yellow-lt">
                                        <i class="ti ti-star"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="font-weight-medium">{{ $whatDifferentItems->count() }} What Different Items</div>
                                    <div class="text-secondary">{{ $whatDifferentItems->where('is_active', true)->count() }} active</div>
                                </div>
                                <div>
                                    <a href="{{ route('about-page.what-different.index') }}" class="btn btn-sm btn-outline-success">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-sm bg-blue-lt">
                                        <i class="ti ti-heart"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="font-weight-medium">{{ $whyChooseItems->count() }} Why Choose Items</div>
                                    <div class="text-secondary">{{ $whyChooseItems->where('is_active', true)->count() }} active</div>
                                </div>
                                <div>
                                    <a href="{{ route('about-page.why-choose.index') }}" class="btn btn-sm btn-outline-info">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Recent Certifications Preview --}}
            @if($certifications->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-certificate me-2"></i>
                        Recent Certifications
                    </h3>
                    <div class="card-actions">
                        <a href="{{ route('about-page.certifications.index') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Manage
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-2 p-3">
                        @foreach($certifications->take(4) as $cert)
                        <div class="col-6">
                            <div class="card item-card">
                                <img src="{{ $cert->image_url }}" class="card-img-top item-image" alt="{{ $cert->image_alt_text }}">
                                @if(!$cert->is_active)
                                <div class="ribbon ribbon-top bg-secondary">
                                    <i class="ti ti-eye-off"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- What Different Items Preview --}}
            @if($whatDifferentItems->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-star me-2"></i>
                        What Different Items
                    </h3>
                    <div class="card-actions">
                        <a href="{{ route('about-page.what-different.index') }}" class="btn btn-success btn-sm">
                            <i class="ti ti-plus me-1"></i> Manage
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($whatDifferentItems->take(3) as $item)
                    <div class="d-flex align-items-start {{ !$loop->last ? 'mb-3' : '' }}">
                        <div class="me-3">
                            <img src="{{ $item->icon_url }}" class="item-icon" alt="{{ $item->title }}">
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">{{ $item->title }}</div>
                            <div class="text-secondary small">{{ Str::limit($item->description, 60) }}</div>
                            @if(!$item->is_active)
                            <span class="badge bg-secondary mt-1">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Why Choose Items Preview --}}
            @if($whyChooseItems->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-heart me-2"></i>
                        Why Choose Items
                    </h3>
                    <div class="card-actions">
                        <a href="{{ route('about-page.why-choose.index') }}" class="btn btn-info btn-sm">
                            <i class="ti ti-plus me-1"></i> Manage
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($whyChooseItems->take(3) as $item)
                    <div class="d-flex align-items-start {{ !$loop->last ? 'mb-3' : '' }}">
                        <div class="me-3">
                            <img src="{{ $item->icon_url }}" class="item-icon" alt="{{ $item->title }}">
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">{{ $item->title }}</div>
                            <div class="text-secondary small">{{ Str::limit($item->description, 60) }}</div>
                            @if(!$item->is_active)
                            <span class="badge bg-secondary mt-1">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>

        {{-- Submit Button --}}
        <div class="col-12">
            <div class="card">
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        @if($aboutPage)
                        <div class="d-flex align-items-center text-secondary">
                            <i class="ti ti-clock me-2"></i>
                            <div>
                                <small class="fw-medium">Last updated:</small>
                                <small class="d-block">{{ $aboutPage->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @else
                        <div class="text-secondary">
                            <i class="ti ti-info-circle me-1"></i>
                            Configure your about page settings
                        </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary" id="save-main-btn">
                            <i class="ti ti-device-floppy me-1"></i>
                            {{ $aboutPage ? 'Update' : 'Save' }} About Page
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

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
        setupImagePreviews();
        setupFormSubmission();
        initializeEditors();
    });

    function initializeEditors() {
        // Initialize multiple WYSIWYG editors
        const editorConfigs = [
            {
                selector: "#about-editor",
                height: 250
            },
            {
                selector: "#description-editor", 
                height: 250
            }
        ];

        editorConfigs.forEach(config => {
            let options = {
                selector: config.selector,
                height: config.height,
                menubar: false,
                statusbar: false,
                license_key: "gpl",
                toolbar:
                    "undo redo | styles | " +
                    "bold italic backcolor | alignleft aligncenter " +
                    "alignright alignjustify | bullist numlist outdent indent | " +
                    "removeformat",
                content_style:
                    "body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }",
                setup: function (editor) {
                    // Update textarea when editor content changes
                    editor.on('change', function () {
                        const content = editor.getContent();
                        const textarea = document.querySelector(config.selector);
                        if (textarea) {
                            textarea.value = content;
                            
                            // Clear validation error if content is added
                            if (content.trim() && content.trim() !== '<p></p>' && content.trim() !== '<p><br></p>') {
                                textarea.classList.remove('is-invalid');
                                const errorDiv = textarea.parentElement.querySelector('.invalid-feedback[style*="block"]');
                                if (errorDiv) {
                                    errorDiv.style.display = 'none';
                                }
                            }
                        }
                    });
                    
                    // Also update on keyup for real-time sync
                    editor.on('keyup', function () {
                        const content = editor.getContent();
                        const textarea = document.querySelector(config.selector);
                        if (textarea) {
                            textarea.value = content;
                        }
                    });
                }
            };
            
            if (localStorage.getItem("tablerTheme") === "dark") {
                options.skin = "oxide-dark";
                options.content_css = "dark";
            }
            
            hugeRTE.init(options);
        });
    }

    function setupImagePreviews() {
        const imageInputs = [
            { input: 'banner-input', preview: 'banner-preview', maxSize: 15 },
            { input: 'about-input', preview: 'about-preview', maxSize: 15 },
            { input: 'description-input', preview: 'description-preview', maxSize: 15 }
        ];
        
        imageInputs.forEach(({ input, preview, maxSize }) => {
            const inputElement = document.getElementById(input);
            const previewElement = document.getElementById(preview);
            
            if (inputElement && previewElement) {
                inputElement.addEventListener('change', function(e) {
                    handleImagePreview(e, previewElement, inputElement, maxSize);
                });
            }
        });
    }

    function handleImagePreview(event, previewContainer, inputElement, maxSizeMB) {
        const file = event.target.files[0];
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
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearImagePreview('${previewContainer.id}', '${inputElement.id}')">
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

    function setupFormSubmission() {
        const mainForm = document.getElementById('main-form');
        const saveMainBtn = document.getElementById('save-main-btn');
        
        if (mainForm && saveMainBtn) {
            mainForm.addEventListener('submit', function(e) {
                let hasError = false;

                // Validate about description from editor
                try {
                    const aboutEditor = hugeRTE.get('#about-editor');
                    if (aboutEditor) {
                        const aboutEditorContent = aboutEditor.getContent();
                        const aboutError = document.getElementById('about-description-error');
                        
                        if (!aboutEditorContent.trim() || aboutEditorContent.trim() === '<p></p>' || aboutEditorContent.trim() === '<p><br></p>') {
                            e.preventDefault();
                            aboutError.textContent = 'About description is required.';
                            aboutError.style.display = 'block';
                            document.getElementById('about-editor').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            hasError = true;
                        } else {
                            aboutError.style.display = 'none';
                        }
                    }
                } catch (err) {
                    console.warn('About editor validation error:', err);
                }

                // Validate description content from editor
                try {
                    const descriptionEditor = hugeRTE.get('#description-editor');
                    if (descriptionEditor) {
                        const descriptionEditorContent = descriptionEditor.getContent();
                        const descriptionError = document.getElementById('description-content-error-custom');
                        
                        if (!descriptionEditorContent.trim() || descriptionEditorContent.trim() === '<p></p>' || descriptionEditorContent.trim() === '<p><br></p>') {
                            e.preventDefault();
                            descriptionError.textContent = 'Description content is required.';
                            descriptionError.style.display = 'block';
                            if (!hasError) {
                                document.getElementById('description-editor').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                            hasError = true;
                        } else {
                            descriptionError.style.display = 'none';
                        }
                    }
                } catch (err) {
                    console.warn('Description editor validation error:', err);
                }

                // If validation passed, show loading state
                if (!hasError) {
                    saveMainBtn.disabled = true;
                    saveMainBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                    mainForm.classList.add('loading');
                }
            });
        }
    }

    // Clear image preview function
    window.clearImagePreview = function(previewId, inputId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).innerHTML = '';
    };
</script>
@endpush