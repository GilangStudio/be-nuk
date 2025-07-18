<?php

namespace App\Http\Controllers;

use App\Models\AboutPageSetting;
use App\Models\AboutCertification;
use App\Models\AboutWhatDifferentItem;
use App\Models\AboutWhyChooseItem;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

class AboutPageController extends Controller
{
    public function index()
    {
        $aboutPage = AboutPageSetting::first();
        $certifications = AboutCertification::active()->ordered()->take(8)->get();
        $whatDifferentItems = AboutWhatDifferentItem::active()->ordered()->take(6)->get();
        $whyChooseItems = AboutWhyChooseItem::active()->ordered()->take(6)->get();

        return view('pages.about-page.index', compact(
            'aboutPage',
            'certifications',
            'whatDifferentItems',
            'whyChooseItems'
        ));
    }

    public function updateOrCreate(Request $request)
    {
        $aboutPage = AboutPageSetting::first();
        $isUpdate = !is_null($aboutPage);

        $request->validate([
            'banner_image' => $isUpdate ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360' : 'required|image|mimes:jpg,jpeg,png,webp|max:15360',
            'banner_image_alt_text' => 'nullable|string|max:255',
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string',
            'about_image' => $isUpdate ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360' : 'required|image|mimes:jpg,jpeg,png,webp|max:15360',
            'about_image_alt_text' => 'nullable|string|max:255',
            'description_title' => 'required|string|max:255',
            'description_content' => 'required|string',
            'description_image' => $isUpdate ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360' : 'required|image|mimes:jpg,jpeg,png,webp|max:15360',
            'description_image_alt_text' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ], [
            'banner_image.required' => 'Banner image is required',
            'banner_image.image' => 'Banner image must be a valid image file',
            'banner_image.max' => 'Banner image size cannot exceed 15MB',
            'about_title.required' => 'About title is required',
            'about_description.required' => 'About description is required',
            'about_image.required' => 'About image is required',
            'about_image.image' => 'About image must be a valid image file',
            'about_image.max' => 'About image size cannot exceed 15MB',
            'description_title.required' => 'Description title is required',
            'description_content.required' => 'Description content is required',
            'description_image.required' => 'Description image is required',
            'description_image.image' => 'Description image must be a valid image file',
            'description_image.max' => 'Description image size cannot exceed 15MB',
            'meta_title.max' => 'Meta title cannot exceed 255 characters',
            'meta_description.max' => 'Meta description cannot exceed 500 characters',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'banner_image_alt_text' => $request->banner_image_alt_text,
                'about_title' => $request->about_title,
                'about_description' => $request->about_description,
                'about_image_alt_text' => $request->about_image_alt_text,
                'description_title' => $request->description_title,
                'description_content' => $request->description_content,
                'description_image_alt_text' => $request->description_image_alt_text,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ];

            if ($isUpdate) {
                // Update existing images
                if ($request->hasFile('banner_image')) {
                    $data['banner_image_path'] = ImageService::updateImage(
                        $request->file('banner_image'),
                        $aboutPage->banner_image_path,
                        'about-page/banner',
                        85
                    );
                }

                if ($request->hasFile('about_image')) {
                    $data['about_image_path'] = ImageService::updateImage(
                        $request->file('about_image'),
                        $aboutPage->about_image_path,
                        'about-page/about',
                        85
                    );
                }

                if ($request->hasFile('description_image')) {
                    $data['description_image_path'] = ImageService::updateImage(
                        $request->file('description_image'),
                        $aboutPage->description_image_path,
                        'about-page/description',
                        85
                    );
                }

                $aboutPage->update($data);
                $message = 'About page updated successfully';
            } else {
                // Create new with required images
                $data['banner_image_path'] = ImageService::uploadAndCompress(
                    $request->file('banner_image'),
                    'about-page/banner',
                    85
                );

                $data['about_image_path'] = ImageService::uploadAndCompress(
                    $request->file('about_image'),
                    'about-page/about',
                    85
                );

                $data['description_image_path'] = ImageService::uploadAndCompress(
                    $request->file('description_image'),
                    'about-page/description',
                    85
                );

                AboutPageSetting::create($data);
                $message = 'About page created successfully';
            }

            DB::commit();

            return redirect()->route('about-page.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to save about page: ' . $e->getMessage());
        }
    }
}