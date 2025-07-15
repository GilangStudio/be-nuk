<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsPageSetting extends Model
{
    protected $guarded = ['id'];

    // Accessors
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image_path ? asset('storage/' . $this->banner_image_path) : null;
    }

    public function getMetaTitleDisplayAttribute()
    {
        return $this->meta_title ?: 'Our Projects - Nusa Utama Konstruksi';
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