<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicesPageSetting;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Services\GeneratorService;

class ServiceController extends Controller
{
    /**
     * Display services management page
     */
    public function index(Request $request)
    {
        $query = Service::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Layout filter
        if ($request->filled('layout')) {
            $query->where('layout', $request->layout);
        }
        
        $services = $query->ordered()->paginate(10)->appends($request->query());
        
        return view('pages.services-page.services.index', compact('services'));
    }

    /**
     * Show create service form
     */
    public function create()
    {
        return view('pages.services-page.services.create');
    }

    /**
     * Store new service
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'icon' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'icon_alt_text' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:15360',
            'image_alt_text' => 'nullable|string|max:255',
            'layout' => 'required|in:image_left,image_right',
        ], [
            'name.required' => 'Service name is required',
            'name.max' => 'Service name cannot exceed 255 characters',
            'short_description.required' => 'Service description is required',
            'icon.required' => 'Service icon is required',
            'icon.image' => 'Icon must be an image file',
            'icon.max' => 'Icon size cannot exceed 2MB',
            'image.required' => 'Service image is required',
            'image.image' => 'File must be an image',
            'image.max' => 'Image size cannot exceed 15MB',
            'layout.required' => 'Layout is required',
            'layout.in' => 'Invalid layout selected',
        ]);

        try {
            // Generate slug
            $slug = GeneratorService::generateSlug(new Service(), $request->name);

            // Upload icon
            $iconPath = ImageService::uploadAndCompress(
                $request->file('icon'),
                'services/icons',
                85
            );

            // Upload image
            $imagePath = ImageService::uploadAndCompress(
                $request->file('image'),
                'services/images',
                85
            );

            // Generate order
            $order = GeneratorService::generateOrder(new Service());

            Service::create([
                'name' => $request->name,
                'short_description' => $request->short_description,
                'slug' => $slug,
                'icon_path' => $iconPath,
                'icon_alt_text' => $request->icon_alt_text,
                'image_path' => $imagePath,
                'image_alt_text' => $request->image_alt_text,
                'layout' => $request->layout,
                'order' => $order,
                'is_active' => $request->has('is_active'),
                'show_in_home' => $request->has('show_in_home'),
            ]);

            return redirect()->route('services.services.index')
                           ->with('success', 'Service created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

    /**
     * Show edit service form
     */
    public function edit(Service $service)
    {
        return view('pages.services-page.services.edit', compact('service'));
    }

    /**
     * Update service
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'icon_alt_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'image_alt_text' => 'nullable|string|max:255',
            'layout' => 'required|in:image_left,image_right',
        ], [
            'name.required' => 'Service name is required',
            'name.max' => 'Service name cannot exceed 255 characters',
            'short_description.required' => 'Service description is required',
            'icon.image' => 'Icon must be an image file',
            'icon.max' => 'Icon size cannot exceed 2MB',
            'image.image' => 'File must be an image',
            'image.max' => 'Image size cannot exceed 15MB',
            'layout.required' => 'Layout is required',
            'layout.in' => 'Invalid layout selected',
        ]);

        try {
            // Generate slug if name changed
            $slug = GeneratorService::generateSlug(new Service(), $request->name, $service->id);

            $data = [
                'name' => $request->name,
                'short_description' => $request->short_description,
                'slug' => $slug,
                'icon_alt_text' => $request->icon_alt_text,
                'image_alt_text' => $request->image_alt_text,
                'layout' => $request->layout,
                'is_active' => $request->has('is_active'),
                'show_in_home' => $request->has('show_in_home'),
            ];

            // Update icon if new file provided
            if ($request->hasFile('icon')) {
                $data['icon_path'] = ImageService::updateImage(
                    $request->file('icon'),
                    $service->icon_path,
                    'services/icons',
                    85
                );
            }

            // Update image if new file provided
            if ($request->hasFile('image')) {
                $data['image_path'] = ImageService::updateImage(
                    $request->file('image'),
                    $service->image_path,
                    'services/images',
                    85
                );
            }

            $service->update($data);

            return redirect()->route('services.services.index')
                           ->with('success', 'Service updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to update service: ' . $e->getMessage());
        }
    }

    /**
     * Delete service
     */
    public function destroy(Service $service)
    {
        try {
            // Check if service has related projects
            if ($service->projects()->count() > 0) {
                return redirect()->route('services.services.index')
                               ->with('error', 'Cannot delete service with existing projects. Please delete projects first.');
            }

            // Delete images
            if ($service->icon_path) {
                ImageService::deleteFile($service->icon_path);
            }
            if ($service->image_path) {
                ImageService::deleteFile($service->image_path);
            }

            $service->delete();

            // Reorder remaining services
            GeneratorService::reorderAfterDelete(new Service());

            return redirect()->route('services.services.index')
                           ->with('success', 'Service deleted successfully');

        } catch (\Exception $e) {
            return redirect()->route('services.services.index')
                           ->with('error', 'Failed to delete service: ' . $e->getMessage());
        }
    }

    /**
     * Update services order via AJAX
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:services,id',
            'orders.*.order' => 'required|integer|min:1',
        ]);

        try {
            foreach ($request->orders as $item) {
                Service::where('id', $item['id'])
                       ->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Service order updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to update service order: ' . $e->getMessage()
            ], 500);
        }
    }
}