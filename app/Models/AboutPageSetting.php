<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageSetting extends Model
{
    protected $guarded = ['id'];

    // Accessors
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image_path ? asset('storage/' . $this->banner_image_path) : null;
    }

    public function getAboutImageUrlAttribute()
    {
        return $this->about_image_path ? asset('storage/' . $this->about_image_path) : null;
    }

    public function getDescriptionImageUrlAttribute()
    {
        return $this->description_image_path ? asset('storage/' . $this->description_image_path) : null;
    }

    public function getMetaTitleDisplayAttribute()
    {
        return $this->meta_title ?: 'About Us - Nusa Utama Konstruksi';
    }

    public function getMetaDescriptionDisplayAttribute()
    {
        return $this->meta_description ?: '';
    }

    public function getMetaKeywordsDisplayAttribute()
    {
        return $this->meta_keywords ?: '';
    }
}