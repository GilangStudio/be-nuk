@extends('layouts.main')

@section('title', 'Certifications Management')

@push('styles')
<style>
    .certification-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .certification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .certification-image {
        height: 200px;
        object-fit: cover;
        background-color: #f8f9fa;
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
    
    .certification-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.7) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .certification-card:hover .certification-overlay {
        opacity: 1;
    }
    
    .certification-actions {
        position: absolute;
        bottom: 10px;
        right: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .certification-card:hover .certification-actions {
        opacity: 1;
    }
    
    .certification-status {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }
    
    .certification-order {
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
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">Certifications Management</h2>
        <div class="page-subtitle">Manage certification images for About page</div>
    </div>
    <div class="btn-list">
        <a href="{{ route('about-page.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to About Page
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-certification-modal">
            <i class="ti ti-plus me-1"></i> Add Certification
        </button>
    </div>
</div>
@endsection

@section('content')

{{-- Search and Filter Form --}}
<div class="col-12">
    <form method="GET" action="{{ route('about-page.certifications.index') }}" id="filter-form">
        <div class="d-flex justify-content-start align-items-center gap-2">
            <div class="input-icon" style="max-width: 350px;">
                <span class="input-icon-addon">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       placeholder="Search certification name..." 
                       value="{{ request('search') }}" 
                       autocomplete="off" 
                       id="search-input">
            </div>
            <div class="d-flex gap-2 align-items-center">
                <select class="form-select" name="status" id="status-filter" style="min-width: 130px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @if(request('search') || request('status'))
                <a href="{{ route('about-page.certifications.index') }}" class="btn btn-outline-secondary" title="Clear all filters">
                    <i class="ti ti-x"></i>
                </a>
                @endif
            </div>
        </div>
        
        {{-- Active Filters Display --}}
        @if(request('search') || request('status'))
        <div class="mt-2 d-flex gap-2 align-items-center flex-wrap">
            <small class="text-secondary">Active filters:</small>
            @if(request('search'))
            <span class="badge bg-blue-lt">
                <i class="ti ti-search me-1"></i>
                Search: "{{ request('search') }}"
            </span>
            @endif
            @if(request('status'))
            <span class="badge bg-green-lt">
                <i class="ti ti-filter me-1"></i>
                Status: {{ request('status') === 'active' ? 'Active' : 'Inactive' }}
            </span>
            @endif
        </div>
        @endif
    </form>
</div>

{{-- Certifications Grid --}}
<div class="col-12">
    <div class="card">
        <div class="card-body p-0">
            @if($items->count() > 0)
            <div class="row g-3 p-3" id="sortable-container">
                @foreach($items as $item)
                <div class="col-sm-6 col-md-4 col-lg-3" data-id="{{ $item->id }}">
                    <div class="card certification-card h-100 position-relative">
                        <div class="position-relative">
                            <img src="{{ $item->image_url }}" class="card-img-top certification-image" alt="{{ $item->image_alt_text }}">
                            
                            {{-- Order Number --}}
                            <div class="certification-order">{{ $item->order }}</div>
                            
                            {{-- Status Badge --}}
                            <div class="certification-status">
                                @if($item->is_active)
                                <span class="badge bg-success text-white">Active</span>
                                @else
                                <span class="badge bg-secondary text-white">Inactive</span>
                                @endif
                            </div>
                            
                            {{-- Drag Handle --}}
                            @if(!request('search') && !request('status'))
                            <div class="sortable-handle" title="Drag to reorder">
                                <i class="ti ti-grip-vertical"></i>
                            </div>
                            @endif
                            
                            {{-- Overlay --}}
                            <div class="certification-overlay"></div>
                            
                            {{-- Actions --}}
                            <div class="certification-actions">
                                <div class="btn-list">
                                    <a href="{{ $item->image_url }}" target="_blank" class="btn btn-sm btn-warning" title="View Image">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary edit-btn" 
                                            data-id="{{ $item->id }}"
                                            data-image-url="{{ $item->image_url }}"
                                            data-image-alt="{{ $item->image_alt_text }}"
                                            data-is-active="{{ $item->is_active ? '1' : '0' }}"
                                            title="Edit Certification">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-btn"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->image_alt_text ?: 'Certification #' . $item->id }}"
                                            data-url="{{ route('about-page.certifications.destroy', $item) }}"
                                            title="Delete Certification">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if($item->image_alt_text)
                        <div class="card-body">
                            <h6 class="card-title mb-0">{{ $item->image_alt_text }}</h6>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty py-5">
                <div class="empty-icon">
                    @if(request('search') || request('status') || request('page'))
                    <i class="ti ti-search icon icon-lg"></i>
                    @else
                    <i class="ti ti-certificate icon icon-lg"></i>
                    @endif
                </div>
                <p class="empty-title h3">
                    @if(request('search') || request('status') || request('page'))
                    No certifications found
                    @else
                    No certification items yet
                    @endif
                </p>
                <p class="empty-subtitle text-secondary">
                    @if(request('search') || request('status') || request('page'))
                    Try adjusting your search terms or clear the filters to see all certifications.
                    @else
                    Get started by adding your first certification.<br>
                    Showcase your company's certifications and achievements.
                    @endif
                </p>
                <div class="empty-action">
                    @if(request('search') || request('status') || request('page'))
                    <a href="{{ route('about-page.certifications.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x me-1"></i> Clear Filters
                    </a>
                    @else
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-certification-modal">
                        <i class="ti ti-plus me-1"></i> Create First Certification
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        {{-- Footer with Results Info and Pagination --}}
        @if($items->total() > 0 || request('search') || request('status'))
        <div class="card-footer d-flex align-items-center">
            <div class="text-secondary">
                @if($items->total() > 0)
                    Showing <strong>{{ $items->firstItem() }}</strong> to <strong>{{ $items->lastItem() }}</strong> 
                    of <strong>{{ $items->total() }}</strong> results
                    @if(request('search'))
                        for "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if($items->hasPages() && !request('search') && !request('status'))
                    <br><small class="text-warning">
                        <i class="ti ti-info-circle me-1"></i>
                        Drag & drop reordering works within current page.
                    </small>
                    @endif
                @else
                    No results found
                    @if(request('search') || request('status'))
                        with current filters
                    @endif
                @endif
            </div>
            
            @include('components.pagination', ['paginator' => $items])
        </div>
        @endif
    </div>
</div>

{{-- Create Certification Modal --}}
<div class="modal modal-blur fade" id="create-certification-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="modal-content" method="POST" action="{{ route('about-page.certifications.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-certificate me-2"></i>
                    Add Certification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Certification Image <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="image" accept="image/*" required id="create-image-input">
                    <small class="form-hint">
                        <i class="ti ti-info-circle me-1"></i>
                        Recommended: 600x400px, Max: 15MB (JPG, PNG, WebP)
                    </small>
                    <div class="mt-3" id="create-image-preview"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Certification Name</label>
                    <input type="text" class="form-control" name="image_alt_text"
                           placeholder="e.g., ISO 9001:2015 Certificate">
                    <small class="form-hint">
                        Enter the name or description of this certification.
                    </small>
                </div>
                <div class="mb-3">
                    <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <span class="form-check-label">Active Status</span>
                    </label>
                    <small class="form-hint d-block">
                        <i class="ti ti-info-circle me-1"></i>
                        Only active certifications will be displayed on the About page.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="create-submit-btn">
                    <i class="ti ti-device-floppy me-1"></i>Add Certification
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Certification Modal --}}
<div class="modal modal-blur fade" id="edit-certification-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="edit-form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-edit me-2"></i>
                    Edit Certification
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
                    <label class="form-label">Certification Name</label>
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
                <button type="submit" class="btn btn-primary" id="edit-submit-btn">
                    <i class="ti ti-device-floppy me-1"></i>Update Certification
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Include Global Delete Modal --}}
@include('components.delete-modal')

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
        setupSearch();
        setupSortable();
        setupModals();
        setupImagePreviews();
    });

    function setupSearch() {
        const filterForm = document.getElementById('filter-form');
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 600);
        });
        
        statusFilter.addEventListener('change', function() {
            filterForm.submit();
        });
        
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                filterForm.submit();
            }
        });
    }

    function setupSortable() {
        @if(!request('search') && !request('status') && $items->count() > 1)
        const sortableContainer = document.getElementById('sortable-container');
        if (sortableContainer) {
            new Sortable(sortableContainer, {
                handle: '.sortable-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
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
                    
                    updateOrder(orders);
                }
            });
        }
        @endif
    }

    function updateOrder(orders) {
        fetch('{{ route('about-page.certifications.update-order') }}', {
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
                showToast(data.message, 'success');
                orders.forEach((item, index) => {
                    const element = document.querySelector(`[data-id="${item.id}"] .certification-order`);
                    if (element) {
                        element.textContent = item.order;
                    }
                });
            } else {
                showToast(data.message, 'error');
                location.reload();
            }
        })
        .catch(error => {
            showToast('Failed to update order', 'error');
            location.reload();
        });
    }

    function setupModals() {
        // Edit button handler
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit-btn') || e.target.closest('.edit-btn')) {
                const button = e.target.classList.contains('edit-btn') ? e.target : e.target.closest('.edit-btn');
                const itemId = button.getAttribute('data-id');
                
                document.getElementById('edit-form').action = `{{ url('about-page/certifications') }}/${itemId}`;
                document.getElementById('edit-image-alt-input').value = button.getAttribute('data-image-alt');
                document.getElementById('edit-is-active').checked = button.getAttribute('data-is-active') === '1';
                document.getElementById('edit-current-image').src = button.getAttribute('data-image-url');
                
                const modal = new bootstrap.Modal(document.getElementById('edit-certification-modal'));
                modal.show();
            }
        });

        // Form submission handlers
        const createForm = document.querySelector('#create-certification-modal form');
        const editForm = document.getElementById('edit-form');
        const createSubmitBtn = document.getElementById('create-submit-btn');
        const editSubmitBtn = document.getElementById('edit-submit-btn');

        createForm.addEventListener('submit', function(e) {
            const imageInput = document.getElementById('create-image-input');
            if (!imageInput.files.length) {
                e.preventDefault();
                showAlert(imageInput, 'danger', 'Please select an image for the certification.');
                return false;
            }

            createSubmitBtn.disabled = true;
            createSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
        });

        editForm.addEventListener('submit', function(e) {
            editSubmitBtn.disabled = true;
            editSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        });

        // Reset modals when closed
        document.getElementById('create-certification-modal').addEventListener('hidden.bs.modal', function() {
            createForm.reset();
            createSubmitBtn.disabled = false;
            createSubmitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i>Add Certification';
            document.getElementById('create-image-preview').innerHTML = '';
        });

        document.getElementById('edit-certification-modal').addEventListener('hidden.bs.modal', function() {
            editForm.reset();
            editSubmitBtn.disabled = false;
            editSubmitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i>Update Certification';
            document.getElementById('edit-image-preview').innerHTML = '';
        });
    }

    function setupImagePreviews() {
        const createImageInput = document.getElementById('create-image-input');
        const editImageInput = document.getElementById('edit-image-input');
        
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
                    <div class="card">
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