<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    protected $guarded = ['id'];

    // Accessors
    public function getAboutImageUrlAttribute()
    {
        return $this->about_image_path ? asset('storage/' . $this->about_image_path) : null;
    }

    public function getMetaTitleDisplayAttribute()
    {
        return $this->meta_title ?: 'Home - Nusa Utama Konstruksi';
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