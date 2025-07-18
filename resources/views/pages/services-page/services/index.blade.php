@extends('layouts.main')

@section('title', 'Services Management')

@push('styles')
<style>
    .service-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .service-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: var(--tblr-primary);
    }
    
    .service-image {
        height: 250px;
        object-fit: cover;
        background-color: #f8f9fa;
    }
    
    .service-icon {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f9fa;
        padding: 8px;
    }
    
    .sortable-handle {
        cursor: grab;
        color: #6c757d;
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255,255,255,0.95);
        border-radius: 8px;
        padding: 8px;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .sortable-handle:hover {
        color: var(--tblr-primary);
        background: rgba(255,255,255,1);
    }
    
    .sortable-ghost {
        opacity: 0.5;
    }
    
    .sortable-chosen {
        transform: rotate(2deg);
    }
    
    .service-status {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }
    
    .service-order {
        position: absolute;
        top: 15px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        z-index: 10;
    }
    
    .service-actions {
        position: absolute;
        bottom: 15px;
        right: 15px;
        z-index: 10;
    }
    
    .layout-preview {
        min-height: 280px;
        display: flex;
        align-items: center;
        gap: 2rem;
        padding: 1.5rem;
    }
    
    .layout-preview.image-right {
        flex-direction: row-reverse;
    }
    
    .preview-image-container {
        flex: 0 0 320px;
    }
    
    .preview-content-container {
        flex: 1;
        padding: 1rem;
    }
    
    .loading-overlay {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .filter-badge {
        font-size: 0.75rem;
    }
    
    .service-badge-container {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">Services Management</h2>
        <div class="page-subtitle">Manage your service offerings and content</div>
    </div>
    <div class="btn-list">
        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Back to Services Page
        </a>
        <a href="{{ route('services.services.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Add Service
        </a>
    </div>
</div>
@endsection

@section('content')

{{-- Search and Filter Form --}}
<div class="col-12">
    <form method="GET" action="{{ route('services.services.index') }}" id="filter-form">
        <div class="d-flex justify-content-start align-items-center gap-2 flex-wrap">
            <div class="input-icon" style="max-width: 350px;">
                <span class="input-icon-addon">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       placeholder="Search service name or description..." 
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
                <select class="form-select" name="layout" id="layout-filter" style="min-width: 140px;">
                    <option value="">All Layouts</option>
                    <option value="image_left" {{ request('layout') === 'image_left' ? 'selected' : '' }}>Image Left</option>
                    <option value="image_right" {{ request('layout') === 'image_right' ? 'selected' : '' }}>Image Right</option>
                </select>
                @if(request('search') || request('status') || request('layout'))
                <a href="{{ route('services.services.index') }}" class="btn btn-outline-secondary" title="Clear all filters">
                    <i class="ti ti-x"></i>
                </a>
                @endif
            </div>
        </div>
        
        {{-- Active Filters Display --}}
        @if(request('search') || request('status') || request('layout'))
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
            @if(request('layout'))
            <span class="badge bg-purple-lt filter-badge">
                <i class="ti ti-layout me-1"></i>
                Layout: {{ request('layout') === 'image_left' ? 'Image Left' : 'Image Right' }}
            </span>
            @endif
        </div>
        @endif
    </form>
</div>

{{-- Services Grid --}}
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ti ti-list me-2"></i>
                Services List
            </h3>
            <div class="card-actions">
                <div class="btn-list">
                    <span class="badge bg-blue-lt">
                        {{ $services->total() }} {{ Str::plural('service', $services->total()) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0" id="services-container">
            @if($services->count() > 0)
            <div class="row g-0" id="sortable-container">
                @foreach($services as $service)
                <div class="col-12" data-id="{{ $service->id }}">
                    <div class="service-card position-relative">
                        {{-- Drag Handle --}}
                        @if(!request('search') && !request('status') && !request('layout'))
                        <div class="sortable-handle" title="Drag to reorder">
                            <i class="ti ti-grip-vertical"></i>
                        </div>
                        @endif
                        
                        {{-- Badges Container --}}
                        <div class="service-badge-container">
                            {{-- Order Number --}}
                            <div class="service-order">{{ $service->order }}</div>
                            
                            {{-- Home Display Badge --}}
                            @if($service->show_in_home)
                            <span class="badge bg-warning text-white">
                                <i class="ti ti-home me-1"></i>Home
                            </span>
                            @endif
                        </div>
                        
                        {{-- Status Badge --}}
                        <div class="service-status">
                            @if($service->is_active)
                            <span class="badge bg-success text-white">Active</span>
                            @else
                            <span class="badge bg-secondary text-white">Inactive</span>
                            @endif
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="service-actions">
                            <div class="btn-list">
                                <a href="{{ route('services.services.edit', $service) }}" 
                                   class="btn btn-primary-lt btn-icon" 
                                   title="Edit Service">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-danger btn-icon delete-btn"
                                        data-id="{{ $service->id }}"
                                        data-name="{{ $service->name }}"
                                        data-url="{{ route('services.services.destroy', $service) }}"
                                        title="Delete Service">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Layout Preview --}}
                        <div class="layout-preview {{ $service->layout === 'image_right' ? 'image-right' : '' }}">
                            {{-- Image Container --}}
                            <div class="preview-image-container">
                                <img src="{{ $service->image_url }}" 
                                     class="service-image w-100 rounded" 
                                     alt="{{ $service->image_alt_text ?: $service->name }}">
                            </div>
                            
                            {{-- Content Container --}}
                            <div class="preview-content-container">
                                <div class="d-flex align-items-start mb-3">
                                    <img src="{{ $service->icon_url }}" 
                                         class="service-icon me-3" 
                                         alt="{{ $service->icon_alt_text ?: $service->name }}">
                                    <div class="flex-fill">
                                        <h4 class="card-title mb-2">{{ $service->name }}</h4>
                                        <div class="text-secondary">
                                            {{ Str::limit($service->short_description, 200) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-secondary small">
                                            <i class="ti ti-calendar me-1"></i>
                                            Created {{ $service->created_at->format('d M Y') }}
                                            @if($service->updated_at != $service->created_at)
                                            • Updated {{ $service->updated_at->format('d M Y') }}
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-blue-lt">
                                                <i class="ti ti-layout-{{ $service->layout === 'image_left' ? 'align-left' : 'align-right' }} me-1"></i>
                                                {{ $service->layout === 'image_left' ? 'Image Left' : 'Image Right' }}
                                            </span>
                                            @if($service->projects_count > 0)
                                            <span class="badge bg-green-lt">
                                                <i class="ti ti-folder me-1"></i>
                                                {{ $service->projects_count }} {{ Str::plural('project', $service->projects_count) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
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
                    @if(request('search') || request('status') || request('layout') || request('page'))
                    <i class="ti ti-search icon icon-lg"></i>
                    @else
                    <i class="ti ti-list icon icon-lg"></i>
                    @endif
                </div>
                <p class="empty-title h3">
                    @if(request('search') || request('status') || request('layout') || request('page'))
                    No services found
                    @else
                    No services yet
                    @endif
                </p>
                <p class="empty-subtitle text-secondary">
                    @if(request('search') || request('status') || request('layout') || request('page'))
                    Try adjusting your search terms or clear the filters to see all services.
                    @else
                    Get started by creating your first service.<br>
                    Services showcase your company's offerings and capabilities.
                    @endif
                </p>
                <div class="empty-action">
                    @if(request('search') || request('status') || request('layout') || request('page'))
                    <a href="{{ route('services.services.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x me-1"></i> Clear Filters
                    </a>
                    @else
                    <a href="{{ route('services.services.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Create First Service
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        {{-- Footer with Results Info and Pagination --}}
        @if($services->total() > 0 || request('search') || request('status') || request('layout'))
        <div class="card-footer d-flex align-items-center">
            <div class="text-secondary">
                @if($services->total() > 0)
                    Showing <strong>{{ $services->firstItem() }}</strong> to <strong>{{ $services->lastItem() }}</strong> 
                    of <strong>{{ $services->total() }}</strong> results
                    @if(request('search'))
                        for "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if($services->hasPages() && !request('search') && !request('status') && !request('layout'))
                    <br><small class="text-warning">
                        <i class="ti ti-info-circle me-1"></i>
                        Drag & drop reordering works within current page.
                    </small>
                    @endif
                @else
                    No results found
                    @if(request('search') || request('status') || request('layout'))
                        with current filters
                    @endif
                @endif
            </div>
            
            @include('components.pagination', ['paginator' => $services])
        </div>
        @endif
    </div>
</div>

{{-- Include Global Delete Modal --}}
@include('components.delete-modal')

@endsection

@push('scripts')
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
    document.addEventListener("DOMContentLoaded", function () {
        setupSearch();
        setupSortable();
    });

    function setupSearch() {
        const filterForm = document.getElementById('filter-form');
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const layoutFilter = document.getElementById('layout-filter');
        const servicesContainer = document.getElementById('services-container');
        
        let searchTimeout;
        
        // Search input with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                submitFilter();
            }, 600);
        });
        
        // Status and layout filter change
        statusFilter.addEventListener('change', function() {
            submitFilter();
        });
        
        layoutFilter.addEventListener('change', function() {
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
                searchInput.focus();
                
                if (cursorPosition !== null) {
                    searchInput.setSelectionRange(parseInt(cursorPosition), parseInt(cursorPosition));
                }
                
                sessionStorage.removeItem('searchInputFocus');
                sessionStorage.removeItem('searchCursorPosition');
                sessionStorage.removeItem('searchValue');
            }
        }
        
        // Restore focus on page load
        restoreSearchFocus();
        
        function showLoadingState() {
            servicesContainer.classList.add('loading-overlay');
            
            const searchIcon = searchInput.parentElement.querySelector('i');
            const originalClass = searchIcon.className;
            searchIcon.className = 'ti ti-loader-2 animate-spin';
            
            setTimeout(() => {
                servicesContainer.classList.remove('loading-overlay');
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
        @if(!request('search') && !request('status') && !request('layout') && $services->count() > 1)
        const sortableContainer = document.getElementById('sortable-container');
        if (sortableContainer) {
            new Sortable(sortableContainer, {
                handle: '.sortable-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
                    const orders = [];
                    const items = sortableContainer.querySelectorAll('.col-12[data-id]');
                    
                    items.forEach((item, index) => {
                        const id = item.getAttribute('data-id');
                        orders.push({
                            id: id,
                            order: index + 1
                        });
                    });
                    
                    updateServicesOrder(orders);
                }
            });
        }
        @endif
    }

    function updateServicesOrder(orders) {
        fetch('{{ route('services.services.update-order') }}', {
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
                // Update order numbers in UI
                orders.forEach((item, index) => {
                    const row = document.querySelector(`[data-id="${item.id}"]`);
                    if (row) {
                        const orderElement = row.querySelector('.service-order');
                        if (orderElement) {
                            orderElement.textContent = index + 1;
                        }
                    }
                });
            } else {
                showToast(data.message, 'error');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error updating order:', error);
            showToast('Failed to update order', 'error');
            location.reload();
        });
    }
</script>
@endpush