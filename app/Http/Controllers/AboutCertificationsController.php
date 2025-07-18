<?php

namespace App\Http\Controllers;

use App\Models\AboutCertification;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Services\GeneratorService;

class AboutCertificationsController extends Controller
{
    public function index(Request $request)
    {
        $query = AboutCertification::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('image_alt_text', 'LIKE', '%' . $searchTerm . '%');
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $items = $query->ordered()->paginate(12)->appends($request->query());
        
        return view('pages.about-page.certifications.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:15360',
            'image_alt_text' => 'nullable|string|max:255',
        ], [
            'image.required' => 'Certification image is required',
            'image.image' => 'File must be an image',
            'image.max' => 'Image size cannot exceed 15MB',
            'image_alt_text.max' => 'Alt text cannot exceed 255 characters',
        ]);

        try {
            $imagePath = ImageService::uploadAndCompress(
                $request->file('image'),
                'about-page/certifications',
                85
            );

            $order = GeneratorService::generateOrder(new AboutCertification());

            AboutCertification::create([
                'image_path' => $imagePath,
                'image_alt_text' => $request->image_alt_text,
                'order' => $order,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('about-page.certifications.index')
                           ->with('success', 'Certification created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to create certification: ' . $e->getMessage());
        }
    }

    public function update(Request $request, AboutCertification $certification)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'image_alt_text' => 'nullable|string|max:255',
        ], [
            'image.image' => 'File must be an image',
            'image.max' => 'Image size cannot exceed 15MB',
            'image_alt_text.max' => 'Alt text cannot exceed 255 characters',
        ]);

        try {
            $data = [
                'image_alt_text' => $request->image_alt_text,
                'is_active' => $request->has('is_active'),
            ];

            if ($request->hasFile('image')) {
                $data['image_path'] = ImageService::updateImage(
                    $request->file('image'),
                    $certification->image_path,
                    'about-page/certifications',
                    85
                );
            }

            $certification->update($data);

            return redirect()->route('about-page.certifications.index')
                           ->with('success', 'Certification updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to update certification: ' . $e->getMessage());
        }
    }

    public function destroy(AboutCertification $certification)
    {
        try {
            if ($certification->image_path) {
                ImageService::deleteFile($certification->image_path);
            }

            $certification->delete();
            GeneratorService::reorderAfterDelete(new AboutCertification());

            return redirect()->route('about-page.certifications.index')
                           ->with('success', 'Certification deleted successfully');

        } catch (\Exception $e) {
            return redirect()->route('about-page.certifications.index')
                           ->with('error', 'Failed to delete certification: ' . $e->getMessage());
        }
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:about_certifications,id',
            'orders.*.order' => 'required|integer|min:1',
        ]);

        try {
            foreach ($request->orders as $item) {
                AboutCertification::where('id', $item['id'])
                                 ->update(['order' => $item['order']]);
            }

            return response()->json(['success' => true, 'message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update order: ' . $e->getMessage()]);
        }
    }
}