@extends('layouts.main')

@section('title', 'Edit Article')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">Edit Article</h2>
        <div class="page-subtitle">{{ $article->title }}</div>
    </div>
    <div class="btn-list">
        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Articles
        </a>
    </div>
</div>
@endsection

@section('content')

<form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="edit-form">
    @csrf
    @method('PUT')
    <div class="row g-3">
        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit me-2"></i>
                        Article Content
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       name="title" value="{{ old('title', $article->title) }}" required 
                                       placeholder="Enter article title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-hint">
                                    <span id="title-count">{{ strlen($article->title ?? '') }}</span>/255 characters
                                </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          name="content" id="editor" rows="15" 
                                          placeholder="Write your article content here...">{{ old('content', $article->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="content-error" style="display: none;"></div>
                                <small class="form-hint">Write the full content of your article.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO Meta --}}
            @include('components.seo-meta-form', ['data' => $article, 'type' => 'edit'])
            
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Publishing Options --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-settings me-2"></i>
                        Publishing
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required id="status-select">
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Choose whether to save as draft or publish.</small>
                    </div>
                    
                    <div class="mb-3" id="published-date-group" style="display: none;">
                        <label class="form-label">Published Date</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                               name="published_at" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" id="published-date">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Leave empty to use current date and time when publishing.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" 
                                {{ old('is_featured', $article->is_featured) ? 'checked' : '' }} id="featured-checkbox">
                            <span class="form-check-label">Featured Article</span>
                        </label>
                        @error('is_featured')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">
                            <i class="ti ti-star me-1"></i>
                            Only published articles can be featured. Only one article can be featured at a time.
                        </small>
                        @if($article->is_featured)
                        <small class="text-success d-block mt-1">
                            <i class="ti ti-check me-1"></i>
                            This article is currently featured.
                        </small>
                        @endif
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label text-secondary">Article Slug</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">{{ url('/') }}/</span>
                            <input type="text" class="form-control bg-light" value="{{ $article->slug }}" readonly>
                        </div>
                        <small class="form-hint">URL slug will be automatically generated from title.</small>
                    </div>
                </div>
            </div>

            {{-- Current Image --}}
            @if($article->image_url)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-photo me-2"></i>
                        Current Image
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card border">
                        <img src="{{ $article->image_url }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <small class="text-secondary fw-medium">Featured Image</small>
                                <a href="{{ $article->image_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-external-link"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Update Image --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-upload me-2"></i>
                        {{ $article->image_url ? 'Update Image' : 'Add Image' }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               name="image" accept="image/*" id="image-input">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">
                            <i class="ti ti-info-circle me-1"></i>
                            {{ $article->image_url ? 'Leave empty to keep current image.' : '' }} Recommended: 1200x630px, Max: 15MB
                        </small>
                        <div class="mt-2" id="image-preview"></div>
                    </div>
                </div>
            </div>

            {{-- Article Meta --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-info-square me-2"></i>
                        Article Meta
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <small class="text-secondary">Created:</small>
                                <div>{{ $article->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <small class="text-secondary">Last Updated:</small>
                                <div>{{ $article->updated_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        @if($article->published_at)
                        <div class="col-12">
                            <div class="mb-0">
                                <small class="text-secondary">Published:</small>
                                <div>{{ $article->published_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        @endif
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
                                Last saved: {{ $article->updated_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                        <div class="btn-list">
                            <a href="{{ route('articles.index') }}" class="btn btn-link">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="ti ti-device-floppy me-1"></i> 
                                Update Article
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
@include('components.scripts.wysiwyg')
@include('components.alert')
@include('components.toast')

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
        // Title character counter
        const titleInput = document.querySelector('input[name="title"]');
        const titleCount = document.getElementById('title-count');
        
        titleInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            titleCount.textContent = currentLength;
            
            if (currentLength > 200) {
                titleCount.parentElement.classList.add('text-warning');
            } else if (currentLength > 255) {
                titleCount.parentElement.classList.remove('text-warning');
                titleCount.parentElement.classList.add('text-danger');
            } else {
                titleCount.parentElement.classList.remove('text-warning', 'text-danger');
            }
        });

        // Status change handler
        const statusSelect = document.getElementById('status-select');
        const publishedDateGroup = document.getElementById('published-date-group');
        const publishedDateInput = document.getElementById('published-date');
        
        statusSelect.addEventListener('change', function() {
            if (this.value === 'published') {
                publishedDateGroup.style.display = 'block';
                // Set current date/time as default if empty and status is changing to published
                if (!publishedDateInput.value && {{ $article->status === 'draft' ? 'true' : 'false' }}) {
                    const now = new Date();
                    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000);
                    publishedDateInput.value = localDateTime.toISOString().slice(0, 16);
                }
            } else {
                publishedDateGroup.style.display = 'none';
            }
        });

        // Trigger change event on page load
        statusSelect.dispatchEvent(new Event('change'));

        // Image preview functionality
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size
                const maxSize = 15 * 1024 * 1024; // 15MB
                if (file.size > maxSize) {
                    showAlert(imageInput, 'danger', 'File size too large. Maximum 15MB allowed.');
                    imageInput.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showAlert(imageInput, 'danger', 'Please select a valid image file.');
                    imageInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `
                        <div class="card image-preview-card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title h6 mb-1">${file.name}</h5>
                                        <small class="text-secondary">
                                            ${(file.size / 1024 / 1024).toFixed(2)} MB
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearImagePreview()">
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
                imagePreview.innerHTML = '';
            }
        });

        // Clear image preview function
        window.clearImagePreview = function() {
            imageInput.value = '';
            imagePreview.innerHTML = '';
        };

        const featuredCheckbox = document.getElementById('featured-checkbox');

        function toggleFeaturedAvailability() {
            if (statusSelect.value === 'published') {
                featuredCheckbox.disabled = false;
                removeAlert();
            } else {
                featuredCheckbox.disabled = true;
                featuredCheckbox.checked = false;
                
                // Show warning if currently featured
                if ({{ $article->is_featured ? 'true' : 'false' }}) {
                    showAlert(statusSelect, 'warning', 'Changing to draft will remove featured status from this article.', -1);
                }
            }
        }

        statusSelect.addEventListener('change', toggleFeaturedAvailability);
        toggleFeaturedAvailability();

        // Form submission with loading state
        const form = document.getElementById('edit-form');
        const submitBtn = document.getElementById('submit-btn');
        
        form.addEventListener('submit', function(e) {
            // Validate content from editor
            const editorContent = hugeRTE.get('editor').getContent();
            const contentTextarea = document.querySelector('textarea[name="content"]');
            const contentError = document.getElementById('content-error');
            
            // Clear previous errors
            contentTextarea.classList.remove('is-invalid');
            contentError.style.display = 'none';
            
            // Check if content is empty
            if (!editorContent.trim() || editorContent.trim() === '<p></p>' || editorContent.trim() === '<p><br></p>') {
                e.preventDefault();
                contentTextarea.classList.add('is-invalid');
                contentError.textContent = 'Content is required.';
                contentError.style.display = 'block';
                
                // Scroll to editor
                document.getElementById('editor').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                return false;
            }
            
            // Update textarea value with editor content
            contentTextarea.value = editorContent;
            
            // Add loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating Article...';
            
            // Add loading class to form
            form.classList.add('loading');
        });
    });
</script>
@endpush