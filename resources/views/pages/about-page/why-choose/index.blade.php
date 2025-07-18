@extends('layouts.main')

@section('title', 'Why Choose Management')

@push('styles')
<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .sortable-handle {
        cursor: grab;
        color: #6c757d;
    }
    
    .sortable-handle:hover {
        color: #0054a6;
    }
    
    .sortable-ghost {
        opacity: 0.5;
    }
    
    .sortable-chosen {
        background-color: #f8f9fa;
    }
    
    .icon-preview {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f9fa;
        padding: 8px;
    }
    
    .form-control:focus {
        border-color: #0054a6;
        box-shadow: 0 0 0 0.2rem rgba(0, 84, 166, 0.25);
    }
    
    .loading {
        pointer-events: none;
        opacity: 0.6;
    }
    
    /* Search highlighting */
    mark.search-highlight {
        background-color: #fff3cd;
        padding: 0.1rem 0.2rem;
        border-radius: 0.25rem;
        font-weight: 600;
    }
    
    /* Loading state */
    .loading-overlay {
        opacity: 0.6;
        pointer-events: none;
    }
    
    /* Filter indicators */
    .filter-badge {
        font-size: 0.75rem;
    }
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">Why Choose Management</h2>
        <div class="page-subtitle">Manage why choose us items for About page</div>
    </div>
    <div class="btn-list">
        <a href="{{ route('about-page.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to About Page
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-item-modal">
            <i class="ti ti-plus me-1"></i> Add Item
        </button>
    </div>
</div>
@endsection

@section('content')

{{-- Search and Filter Form --}}
<div class="col-12">
    <form method="GET" action="{{ route('about-page.why-choose.index') }}" id="filter-form">
        <div class="d-flex justify-content-start align-items-center gap-2">
            <div class="input-icon" style="max-width: 350px;">
                <span class="input-icon-addon">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       placeholder="Search title or description..." 
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
                <a href="{{ route('about-page.why-choose.index') }}" class="btn btn-outline-secondary" title="Clear all filters">
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
            <span class="badge bg-blue-lt filter-badge">
                <i class="ti ti-search me-1"></i>
                Search: "{{ request('search') }}"
            </span>
            @endif
            @if(request('status'))
            <span class="badge bg-green-lt filter-badge">
                <i class="ti ti-filter me-1"></i>
                Status: {{ request('status') === 'active' ? 'Active' : 'Inactive' }}
            </span>
            @endif
        </div>
        @endif
    </form>
</div>

{{-- Items Table --}}
<div class="col-12">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive" id="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">Order</th>
                            <th width="80" class="text-center">Icon</th>
                            <th>Item Information</th>
                            <th width="120" class="text-center">Status</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-tbody">
                        @forelse($items as $item)
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center">
                                <div class="d-flex align-items-center h-100">
                                    <span class="sortable-handle">
                                        <i class="ti ti-grip-vertical"></i>
                                    </span>
                                    <small class="text-secondary d-block">{{ $item->order }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <img src="{{ $item->icon_url }}" class="icon-preview" alt="{{ $item->title }}">
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold text-dark mb-1" data-searchable="title">
                                        {{ $item->title }}
                                    </div>
                                    @if($item->description)
                                    <div class="text-secondary mb-1" style="line-height: 1.4;" data-searchable="description">
                                        {{ Str::limit($item->description, 120) }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                <span class="badge bg-green-lt">
                                    <i class="ti ti-check me-1"></i>Active
                                </span>
                                @else
                                <span class="badge bg-gray-lt">
                                    <i class="ti ti-x me-1"></i>Inactive
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-list">
                                    <button type="button" 
                                            class="btn btn-primary-lt btn-icon edit-btn" 
                                            data-id="{{ $item->id }}"
                                            data-title="{{ $item->title }}"
                                            data-description="{{ $item->description }}"
                                            data-icon-url="{{ $item->icon_url }}"
                                            data-icon-alt="{{ $item->icon_alt_text }}"
                                            data-is-active="{{ $item->is_active ? '1' : '0' }}"
                                            title="Edit Item">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn bg-danger-lt text-danger btn-icon delete-btn"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->title }}"
                                            data-url="{{ route('about-page.why-choose.destroy', $item) }}"
                                            title="Delete Item">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty">
                                    <div class="empty-icon">
                                        @if(request('search') || request('status') || request('page'))
                                        <i class="ti ti-search icon icon-lg"></i>
                                        @else
                                        <i class="ti ti-heart icon icon-lg"></i>
                                        @endif
                                    </div>
                                    <p class="empty-title h3">
                                        @if(request('search') || request('status') || request('page'))
                                        No items found
                                        @else
                                        No why choose items yet
                                        @endif
                                    </p>
                                    <p class="empty-subtitle text-secondary">
                                        @if(request('search') || request('status') || request('page'))
                                        Try adjusting your search terms or clear the filters to see all items.
                                        @else
                                        Get started by creating your first why choose item.<br>
                                        Highlight the reasons why customers should choose your company.
                                        @endif
                                    </p>
                                    <div class="empty-action">
                                        @if(request('search') || request('status') || request('page'))
                                        <a href="{{ route('about-page.why-choose.index') }}" class="btn btn-outline-secondary">
                                            <i class="ti ti-x me-1"></i> Clear Filters
                                        </a>
                                        @else
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-item-modal">
                                            <i class="ti ti-plus me-1"></i> Create First Item
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

{{-- Create Item Modal --}}
<div class="modal modal-blur fade" id="create-item-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="modal-content" method="POST" action="{{ route('about-page.why-choose.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-heart me-2"></i>
                    Create Why Choose Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required
                           placeholder="e.g., Professional Excellence" id="create-title-input">
                    <small class="form-hint">
                        <span id="create-title-count">0</span>/255 characters
                    </small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" rows="4" required
                              placeholder="Explain why customers should choose this aspect of your company..." id="create-description-input"></textarea>
                    <small class="form-hint">
                        Explain the specific reason why customers should choose your company.
                    </small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="icon" accept="image/*" required id="create-icon-input">
                    <small class="form-hint">Upload an icon image. Max: 2MB (JPG, PNG, WebP, SVG)</small>
                    <div class="mt-3" id="create-icon-preview"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon Alt Text</label>
                    <input type="text" class="form-control" name="icon_alt_text"
                           placeholder="Enter icon description for accessibility">
                </div>
                <div class="mb-3">
                    <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <span class="form-check-label">Active Status</span>
                    </label>
                    <small class="form-hint d-block">
                        <i class="ti ti-info-circle me-1"></i>
                        Only active items will be displayed on the About page.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="create-submit-btn">
                    <i class="ti ti-device-floppy me-1"></i>Create Item
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Item Modal --}}
<div class="modal modal-blur fade" id="edit-item-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="edit-form">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-edit me-2"></i>
                    Edit Why Choose Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3" id="edit-current-icon">
                    <label class="form-label">Current Icon</label>
                    <div>
                        <img id="edit-icon-preview-current" src="" class="icon-preview" alt="">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required id="edit-title-input">
                    <small class="form-hint">
                        <span id="edit-title-count">0</span>/255 characters
                    </small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" rows="4" required id="edit-description-input"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Update Icon (Optional)</label>
                    <input type="file" class="form-control" name="icon" accept="image/*" id="edit-icon-input">
                    <small class="form-hint">Leave empty to keep current icon. Max: 2MB</small>
                    <div class="mt-3" id="edit-icon-preview"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon Alt Text</label>
                    <input type="text" class="form-control" name="icon_alt_text" id="edit-icon-alt-input">
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
                    <i class="ti ti-device-floppy me-1"></i>Update Item
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
        setupCharacterCounters();
        setupImagePreviews();
        
        // Highlight search terms in results
        const searchTerm = '{{ request('search') }}';
        if (searchTerm) {
            highlightSearchResults(searchTerm.toLowerCase());
        }
    });

    function setupSearch() {
        const filterForm = document.getElementById('filter-form');
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const tableContainer = document.getElementById('table-container');
        
        let searchTimeout;
        
        // Search input with debounce (auto-submit after 600ms)
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                submitFilter();
            }, 600);
        });
        
        // Status filter change (immediate submit)
        statusFilter.addEventListener('change', function() {
            submitFilter();
        });
        
        // Handle Enter key in search input
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                submitFilter();
            }
        });
        
        // Submit filter function with loading state
        function submitFilter() {
            // Store current cursor position and search value
            const cursorPosition = searchInput.selectionStart;
            const searchValue = searchInput.value;
            
            // Store in sessionStorage for after page reload
            sessionStorage.setItem('searchInputFocus', 'true');
            sessionStorage.setItem('searchCursorPosition', cursorPosition);
            sessionStorage.setItem('searchValue', searchValue);
            
            // Show loading state
            showLoadingState();
            
            // Submit form
            filterForm.submit();
        }
        
        // Restore focus and cursor position after page load
        function restoreSearchFocus() {
            const shouldFocus = sessionStorage.getItem('searchInputFocus');
            const cursorPosition = sessionStorage.getItem('searchCursorPosition');
            const searchValue = sessionStorage.getItem('searchValue');
            
            if (shouldFocus === 'true' && searchInput.value === searchValue) {
                // Focus input
                searchInput.focus();
                
                // Restore cursor position
                if (cursorPosition !== null) {
                    searchInput.setSelectionRange(parseInt(cursorPosition), parseInt(cursorPosition));
                }
                
                // Clear stored values
                sessionStorage.removeItem('searchInputFocus');
                sessionStorage.removeItem('searchCursorPosition');
                sessionStorage.removeItem('searchValue');
            }
        }
        
        // Restore focus on page load
        restoreSearchFocus();
        
        // Show loading state
        function showLoadingState() {
            tableContainer.classList.add('loading-overlay');
            
            // Add loading spinner to search input
            const searchIcon = searchInput.parentElement.querySelector('i');
            const originalClass = searchIcon.className;
            searchIcon.className = 'ti ti-loader-2 animate-spin';
            
            // Reset after a delay (in case form submission fails)
            setTimeout(() => {
                tableContainer.classList.remove('loading-overlay');
                searchIcon.className = originalClass;
            }, 5000);
        }
        
        // Focus search input on Ctrl+K or Cmd+K
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });
    }

    function setupSortable() {
        @if(!request('search') && !request('status') && $items->count() > 1)
        const sortableTable = document.getElementById('sortable-tbody');
        if (sortableTable) {
            new Sortable(sortableTable, {
                handle: '.sortable-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
                    const orders = [];
                    const rows = sortableTable.querySelectorAll('tr[data-id]');
                    
                    rows.forEach((row, index) => {
                        orders.push({
                            id: row.dataset.id,
                            order: index + 1
                        });
                    });
                    
                    updateOrder(orders);
                }
            });
        }
        @endif
    }

    function updateOrder(orders) {
        fetch('{{ route('about-page.why-choose.update-order') }}', {
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
                    const row = document.querySelector(`tr[data-id="${item.id}"]`);
                    if (row) {
                        const orderElement = row.querySelector('.sortable-handle + small');
                        if (orderElement) {
                            orderElement.textContent = item.order;
                        }
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
                
                document.getElementById('edit-form').action = `{{ url('about-page/why-choose') }}/${itemId}`;
                document.getElementById('edit-title-input').value = button.getAttribute('data-title');
                document.getElementById('edit-description-input').value = button.getAttribute('data-description');
                document.getElementById('edit-icon-alt-input').value = button.getAttribute('data-icon-alt');
                document.getElementById('edit-is-active').checked = button.getAttribute('data-is-active') === '1';
                document.getElementById('edit-icon-preview-current').src = button.getAttribute('data-icon-url');
                
                // Update character counters
                updateCharacterCounter('edit-title-input', 'edit-title-count');
                
                const modal = new bootstrap.Modal(document.getElementById('edit-item-modal'));
                modal.show();
            }
        });

        // Form submission handlers
        const createForm = document.querySelector('#create-item-modal form');
        const editForm = document.getElementById('edit-form');
        const createSubmitBtn = document.getElementById('create-submit-btn');
        const editSubmitBtn = document.getElementById('edit-submit-btn');

        createForm.addEventListener('submit', function(e) {
            const iconInput = document.getElementById('create-icon-input');
            if (!iconInput.files.length) {
                e.preventDefault();
                showAlert(iconInput, 'danger', 'Please select an icon for the item.');
                return false;
            }

            createSubmitBtn.disabled = true;
            createSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
            createForm.classList.add('loading');
        });

        editForm.addEventListener('submit', function(e) {
            editSubmitBtn.disabled = true;
            editSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
            editForm.classList.add('loading');
        });

        // Reset modals when closed
        document.getElementById('create-item-modal').addEventListener('hidden.bs.modal', function() {
            createForm.reset();
            createForm.classList.remove('loading');
            createSubmitBtn.disabled = false;
            createSubmitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i>Create Item';
            document.getElementById('create-icon-preview').innerHTML = '';
            document.getElementById('create-title-count').textContent = '0';
        });

        document.getElementById('edit-item-modal').addEventListener('hidden.bs.modal', function() {
            editForm.reset();
            editForm.classList.remove('loading');
            editSubmitBtn.disabled = false;
            editSubmitBtn.innerHTML = '<i class="ti ti-device-floppy me-1"></i>Update Item';
            document.getElementById('edit-icon-preview').innerHTML = '';
        });
    }

    function setupCharacterCounters() {
        setupCharacterCounter('create-title-input', 'create-title-count', 255);
        setupCharacterCounter('edit-title-input', 'edit-title-count', 255);
    }

    function setupCharacterCounter(inputId, countId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(countId);
        
        if (input && counter) {
            input.addEventListener('input', function() {
                updateCharacterCounter(inputId, countId);
            });
        }
    }

    function updateCharacterCounter(inputId, countId) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(countId);
        
        if (input && counter) {
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            
            const percentage = (currentLength / 255) * 100;
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
        }
    }

    function setupImagePreviews() {
        const createIconInput = document.getElementById('create-icon-input');
        const editIconInput = document.getElementById('edit-icon-input');
        
        if (createIconInput) {
            createIconInput.addEventListener('change', function(e) {
                handleImagePreview(e, 'create-icon-preview', createIconInput, 2);
            });
        }
        
        if (editIconInput) {
            editIconInput.addEventListener('change', function(e) {
                handleImagePreview(e, 'edit-icon-preview', editIconInput, 2);
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
                    <div class="d-flex align-items-start gap-3">
                        <img src="${e.target.result}" class="icon-preview" alt="Preview">
                        <div class="flex-fill">
                            <h6 class="mb-1">${file.name}</h6>
                            <small class="text-secondary d-block">
                                ${(file.size / 1024 / 1024).toFixed(2)} MB
                            </small>
                            <small class="text-success">
                                <i class="ti ti-check me-1"></i>Ready to upload
                            </small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearImagePreview('${previewId}', '${inputElement.id}')">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = '';
        }
    }

    function highlightSearchResults(term) {
        const searchableElements = document.querySelectorAll('[data-searchable]');
        
        searchableElements.forEach(element => {
            const text = element.textContent;
            const lowerText = text.toLowerCase();
            
            if (lowerText.includes(term)) {
                const regex = new RegExp(`(${escapeRegExp(term)})`, 'gi');
                element.innerHTML = text.replace(regex, '<mark class="search-highlight">$1</mark>');
            }
        });
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\        <a href="{{ route('about-page.index')');
    }

    // Clear image preview function
    window.clearImagePreview = function(previewId, inputId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).innerHTML = '';
    };

    // Add loading animation CSS
    const style = document.createElement('style');
    style.textContent = `
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush