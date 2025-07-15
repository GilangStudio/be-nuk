<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageSetting extends Model
{
    protected $guarded = ['id'];

    // Accessors
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image_path ? asset('storage/' . $this->banner_image_path) : null;
    }

    public function getFormImageUrlAttribute()
    {
        return $this->form_image_path ? asset('storage/' . $this->form_image_path) : null;
    }

    public function getMetaTitleDisplayAttribute()
    {
        return $this->meta_title ?: 'Contact Us - Nusa Utama Konstruksi';
    }

    public function getMetaDescriptionDisplayAttribute()
    {
        return $this->meta_description ?: '';
    }

    public function getMetaKeywordsDisplayAttribute()
    {
        return $this->meta_keywords ?: '';
    }

    // Social Media Accessors
    public function getHasSocialMediaAttribute()
    {
        return $this->facebook_url || $this->instagram_url || $this->linkedin_url;
    }

    public function getSocialMediaLinksAttribute()
    {
        $links = [];
        
        if ($this->facebook_url) {
            $links['facebook'] = [
                'url' => $this->facebook_url,
                'icon' => 'fab fa-facebook-f',
                'name' => 'Facebook'
            ];
        }
        
        if ($this->instagram_url) {
            $links['instagram'] = [
                'url' => $this->instagram_url,
                'icon' => 'fab fa-instagram',
                'name' => 'Instagram'
            ];
        }
        
        if ($this->linkedin_url) {
            $links['linkedin'] = [
                'url' => $this->linkedin_url,
                'icon' => 'fab fa-linkedin-in',
                'name' => 'LinkedIn'
            ];
        }
        
        return $links;
    }
}