<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomePageSetting;
use App\Models\HomeBanner;
use App\Models\Service;
use App\Models\CompanyLogo;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function index()
    {
        $homePageSetting = HomePageSetting::first();
        $banners = HomeBanner::active()->ordered()->get();
        $services = Service::active()->get();
        $companyLogos = CompanyLogo::active()->ordered()->get();
        
        return view('pages.home-page.index', compact('homePageSetting', 'banners', 'services', 'companyLogos'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // About Section
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'about_image_alt_text' => 'nullable|string|max:255',
            
            // Banner Images
            'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'banner_alt_texts.*' => 'nullable|string|max:255',
            'banner_orders.*' => 'nullable|integer|min:1|max:999',
            'banner_is_active.*' => 'nullable|boolean',
            
            // Services to show in home
            'home_services' => 'nullable|array',
            'home_services.*' => 'exists:services,id',
            
            // Company Logos
            'company_logo_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'company_logo_alt_texts.*' => 'nullable|string|max:255',
            'company_logo_orders.*' => 'nullable|integer|min:1|max:999',
            'company_logo_is_active.*' => 'nullable|boolean',
            
            // SEO Meta
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ], [
            'about_title.required' => 'About title is required.',
            'about_title.max' => 'About title must not exceed 255 characters.',
            'about_description.required' => 'About description is required.',
            'about_image.image' => 'About image must be a valid image file.',
            'about_image.mimes' => 'About image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'about_image.max' => 'About image must not exceed 15MB.',
            'about_image_alt_text.max' => 'About image alt text must not exceed 255 characters.',
            
            'banner_images.*.image' => 'Banner image must be a valid image file.',
            'banner_images.*.mimes' => 'Banner image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'banner_images.*.max' => 'Banner image must not exceed 15MB.',
            'banner_alt_texts.*.max' => 'Banner alt text must not exceed 255 characters.',
            'banner_orders.*.integer' => 'Banner order must be a valid number.',
            'banner_orders.*.min' => 'Banner order must be at least 1.',
            'banner_orders.*.max' => 'Banner order must not exceed 999.',
            
            'home_services.*.exists' => 'Selected service does not exist.',
            
            'company_logo_images.*.image' => 'Company logo must be a valid image file.',
            'company_logo_images.*.mimes' => 'Company logo must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'company_logo_images.*.max' => 'Company logo must not exceed 15MB.',
            'company_logo_alt_texts.*.max' => 'Company name must not exceed 255 characters.',
            'company_logo_orders.*.integer' => 'Logo order must be a valid number.',
            'company_logo_orders.*.min' => 'Logo order must be at least 1.',
            'company_logo_orders.*.max' => 'Logo order must not exceed 999.',
            
            'meta_title.max' => 'Meta title must not exceed 255 characters.',
            'meta_description.max' => 'Meta description must not exceed 500 characters.',
            'meta_keywords.max' => 'Meta keywords must not exceed 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Get or create home page setting
            $homePageSetting = HomePageSetting::first();
            if (!$homePageSetting) {
                $homePageSetting = new HomePageSetting();
            }

            // Handle about image upload
            if ($request->hasFile('about_image')) {
                // Delete old image if exists
                ImageService::deleteFile($homePageSetting->about_image_path);
                
                // Upload new image
                $homePageSetting->about_image_path = ImageService::uploadAndCompress(
                    $request->file('about_image'), 
                    'home-page/about'
                );
            }

            // Update home page setting
            $homePageSetting->about_title = $request->about_title;
            $homePageSetting->about_description = $request->about_description;
            $homePageSetting->about_image_alt_text = $request->about_image_alt_text;
            $homePageSetting->meta_title = $request->meta_title;
            $homePageSetting->meta_description = $request->meta_description;
            $homePageSetting->meta_keywords = $request->meta_keywords;
            $homePageSetting->save();

            // Handle banner images
            if ($request->hasFile('banner_images')) {
                foreach ($request->file('banner_images') as $index => $bannerImage) {
                    if ($bannerImage) {
                        $bannerImagePath = ImageService::uploadAndCompress(
                            $bannerImage, 
                            'home-page/banners'
                        );
                        
                        HomeBanner::create([
                            'image_path' => $bannerImagePath,
                            'image_alt_text' => $request->banner_alt_texts[$index] ?? null,
                            'order' => $request->banner_orders[$index] ?? 1,
                            'is_active' => isset($request->banner_is_active[$index]) ? true : false,
                        ]);
                    }
                }
            }

            // Handle services to show in home
            Service::query()->update(['show_in_home' => false]);
            if ($request->home_services) {
                Service::whereIn('id', $request->home_services)->update(['show_in_home' => true]);
            }

            // Handle company logos
            if ($request->hasFile('company_logo_images')) {
                foreach ($request->file('company_logo_images') as $index => $logoImage) {
                    if ($logoImage) {
                        $logoImagePath = ImageService::uploadAndCompress(
                            $logoImage, 
                            'home-page/company-logos'
                        );
                        
                        CompanyLogo::create([
                            'image_path' => $logoImagePath,
                            'image_alt_text' => $request->company_logo_alt_texts[$index] ?? null,
                            'order' => $request->company_logo_orders[$index] ?? 1,
                            'is_active' => isset($request->company_logo_is_active[$index]) ? true : false,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Home page settings updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating home page settings: ' . $e->getMessage());
        }
    }

    public function deleteBanner($id)
    {
        try {
            $banner = HomeBanner::findOrFail($id);
            
            // Delete image file using ImageService
            ImageService::deleteFile($banner->image_path);
            
            $banner->delete();
            
            return redirect()->route('home-page.index')->with('success', 'Banner deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting banner: ' . $e->getMessage());
        }
    }

    public function deleteCompanyLogo($id)
    {
        try {
            $logo = CompanyLogo::findOrFail($id);
            
            // Delete image file using ImageService
            ImageService::deleteFile($logo->image_path);
            
            $logo->delete();
            
            return redirect()->route('home-page.index')->with('success', 'Company logo deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting company logo: ' . $e->getMessage());
        }
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'about_image_alt_text' => 'nullable|string|max:255',
            
            // SEO Meta
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ], [
            'about_title.required' => 'About title is required.',
            'about_title.max' => 'About title must not exceed 255 characters.',
            'about_description.required' => 'About description is required.',
            'about_image.image' => 'About image must be a valid image file.',
            'about_image.mimes' => 'About image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'about_image.max' => 'About image must not exceed 15MB.',
            'about_image_alt_text.max' => 'About image alt text must not exceed 255 characters.',
            'meta_title.max' => 'Meta title must not exceed 255 characters.',
            'meta_description.max' => 'Meta description must not exceed 500 characters.',
            'meta_keywords.max' => 'Meta keywords must not exceed 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Get or create home page setting
            $homePageSetting = HomePageSetting::first();
            if (!$homePageSetting) {
                $homePageSetting = new HomePageSetting();
            }

            // Handle about image upload
            if ($request->hasFile('about_image')) {
                // Delete old image if exists
                ImageService::deleteFile($homePageSetting->about_image_path);
                
                // Upload new image
                $homePageSetting->about_image_path = ImageService::uploadAndCompress(
                    $request->file('about_image'), 
                    'home-page/about'
                );
            }

            // Update home page setting
            $homePageSetting->about_title = $request->about_title;
            $homePageSetting->about_description = $request->about_description;
            $homePageSetting->about_image_alt_text = $request->about_image_alt_text;
            $homePageSetting->meta_title = $request->meta_title;
            $homePageSetting->meta_description = $request->meta_description;
            $homePageSetting->meta_keywords = $request->meta_keywords;
            $homePageSetting->save();

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'About section updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating about section: ' . $e->getMessage());
        }
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'image_alt_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'image.required' => 'Banner image is required.',
            'image.image' => 'Banner image must be a valid image file.',
            'image.mimes' => 'Banner image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'image.max' => 'Banner image must not exceed 15MB.',
            'image_alt_text.max' => 'Image alt text must not exceed 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Get next order number
            $maxOrder = HomeBanner::max('order');
            $order = $maxOrder ? $maxOrder + 1 : 1;

            // Upload banner image
            $imagePath = ImageService::uploadAndCompress(
                $request->file('image'), 
                'home-page/banners'
            );

            // Create banner
            HomeBanner::create([
                'image_path' => $imagePath,
                'image_alt_text' => $request->image_alt_text,
                'order' => $order,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Banner created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while creating banner: ' . $e->getMessage());
        }
    }

    public function updateBanner(Request $request, HomeBanner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'image_alt_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'image.image' => 'Banner image must be a valid image file.',
            'image.mimes' => 'Banner image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'image.max' => 'Banner image must not exceed 15MB.',
            'image_alt_text.max' => 'Image alt text must not exceed 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                ImageService::deleteFile($banner->image_path);
                
                // Upload new image
                $banner->image_path = ImageService::uploadAndCompress(
                    $request->file('image'), 
                    'home-page/banners'
                );
            }

            // Update banner
            $banner->image_alt_text = $request->image_alt_text;
            $banner->is_active = $request->has('is_active') ? true : false;
            $banner->save();

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Banner updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating banner: ' . $e->getMessage());
        }
    }

    public function destroyBanner(HomeBanner $banner)
    {
        try {
            DB::beginTransaction();

            // Delete image file
            ImageService::deleteFile($banner->image_path);
            
            // Delete banner
            $banner->delete();

            // Reorder remaining banners
            $remainingBanners = HomeBanner::orderBy('order')->get();
            foreach ($remainingBanners as $index => $remainingBanner) {
                $remainingBanner->update(['order' => $index + 1]);
            }

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Banner deleted successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while deleting banner: ' . $e->getMessage());
        }
    }

    public function updateServices(Request $request)
    {
        $request->validate([
            'home_services' => 'nullable|array',
            'home_services.*' => 'exists:services,id',
        ], [
            'home_services.*.exists' => 'Selected service does not exist.',
        ]);

        try {
            DB::beginTransaction();

            // Reset all services
            Service::query()->update(['show_in_home' => false]);
            
            // Update selected services
            if ($request->home_services) {
                Service::whereIn('id', $request->home_services)->update(['show_in_home' => true]);
            }

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Services selection updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating services: ' . $e->getMessage());
        }
    }

    public function storeLogo(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'image_alt_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'image.required' => 'Company logo is required.',
            'image.image' => 'Company logo must be a valid image file.',
            'image.mimes' => 'Company logo must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'image.max' => 'Company logo must not exceed 15MB.',
            'image_alt_text.max' => 'Company name must not exceed 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Get next order number
            $maxOrder = CompanyLogo::max('order');
            $order = $maxOrder ? $maxOrder + 1 : 1;

            // Upload logo image
            $imagePath = ImageService::uploadAndCompress(
                $request->file('image'), 
                'home-page/company-logos'
            );

            // Create logo
            CompanyLogo::create([
                'image_path' => $imagePath,
                'image_alt_text' => $request->image_alt_text,
                'order' => $order,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Company logo added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while adding company logo: ' . $e->getMessage());
        }
    }

    public function updateLogo(Request $request, CompanyLogo $logo)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'image_alt_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'image.image' => 'Company logo must be a valid image file.',
            'image.mimes' => 'Company logo must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'image.max' => 'Company logo must not exceed 15MB.',
            'image_alt_text.max' => 'Company name must not exceed 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                ImageService::deleteFile($logo->image_path);
                
                // Upload new image
                $logo->image_path = ImageService::uploadAndCompress(
                    $request->file('image'), 
                    'home-page/company-logos'
                );
            }

            // Update logo
            $logo->image_alt_text = $request->image_alt_text;
            $logo->is_active = $request->has('is_active') ? true : false;
            $logo->save();

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Company logo updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while updating company logo: ' . $e->getMessage());
        }
    }

    public function updateLogoOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:company_logos,id',
            'orders.*.order' => 'required|integer|min:1',
        ], [
            'orders.required' => 'Logo orders are required.',
            'orders.array' => 'Logo orders must be an array.',
            'orders.*.id.required' => 'Logo ID is required.',
            'orders.*.id.exists' => 'Logo does not exist.',
            'orders.*.order.required' => 'Order is required.',
            'orders.*.order.integer' => 'Order must be a valid number.',
            'orders.*.order.min' => 'Order must be at least 1.',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->orders as $orderData) {
                CompanyLogo::where('id', $orderData['id'])->update(['order' => $orderData['order']]);
            }
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Logo order updated successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'An error occurred while updating logo order.']);
        }
    }

    public function destroyLogo(CompanyLogo $logo)
    {
        try {
            DB::beginTransaction();

            // Delete image file
            ImageService::deleteFile($logo->image_path);
            
            // Delete logo
            $logo->delete();

            // Reorder remaining logos
            $remainingLogos = CompanyLogo::orderBy('order')->get();
            foreach ($remainingLogos as $index => $remainingLogo) {
                $remainingLogo->update(['order' => $index + 1]);
            }

            DB::commit();

            return redirect()->route('home-page.index')->with('success', 'Company logo deleted successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while deleting company logo: ' . $e->getMessage());
        }
    }

    public function toggleLogoStatus(CompanyLogo $logo)
    {
        try {
            $logo->is_active = !$logo->is_active;
            $logo->save();

            return response()->json([
                'success' => true, 
                'message' => 'Company logo status updated successfully!',
                'is_active' => $logo->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating logo status.']);
        }
    }

    // Legacy methods for backward compatibility
    public function toggleBannerStatus($id)
    {
        try {
            $banner = HomeBanner::findOrFail($id);
            $banner->is_active = !$banner->is_active;
            $banner->save();

            return response()->json([
                'success' => true, 
                'message' => 'Banner status updated successfully!',
                'is_active' => $banner->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating banner status.']);
        }
    }

    public function toggleCompanyLogoStatus($id)
    {
        try {
            $logo = CompanyLogo::findOrFail($id);
            $logo->is_active = !$logo->is_active;
            $logo->save();

            return response()->json([
                'success' => true, 
                'message' => 'Company logo status updated successfully!',
                'is_active' => $logo->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating company logo status.']);
        }
    }

    public function updateBannerOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:home_banners,id',
            'orders.*.order' => 'required|integer|min:1',
        ], [
            'orders.required' => 'Banner orders are required.',
            'orders.array' => 'Banner orders must be an array.',
            'orders.*.id.required' => 'Banner ID is required.',
            'orders.*.id.exists' => 'Banner does not exist.',
            'orders.*.order.required' => 'Order is required.',
            'orders.*.order.integer' => 'Order must be a valid number.',
            'orders.*.order.min' => 'Order must be at least 1.',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->orders as $orderData) {
                HomeBanner::where('id', $orderData['id'])->update(['order' => $orderData['order']]);
            }
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Banner order updated successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'An error occurred while updating banner order.']);
        }
    }
}