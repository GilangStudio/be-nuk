<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $guarded = ['id'];

    // Logo Accessors
    public function getLogoHeaderUrlAttribute()
    {
        return $this->logo_header_path ? asset('storage/' . $this->logo_header_path) : null;
    }

    public function getLogoFooterUrlAttribute()
    {
        return $this->logo_footer_path ? asset('storage/' . $this->logo_footer_path) : null;
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon_path ? asset('storage/' . $this->favicon_path) : null;
    }

    // Footer Video Accessors
    public function getFooterVideoUrlAttribute()
    {
        return $this->footer_video_path ? asset('storage/' . $this->footer_video_path) : null;
    }

    public function getHasFooterVideoAttribute()
    {
        return !empty($this->footer_video_path);
    }

    // Logo Status Accessors
    public function getHasHeaderLogoAttribute()
    {
        return !empty($this->logo_header_path);
    }

    public function getHasFooterLogoAttribute()
    {
        return !empty($this->logo_footer_path);
    }

    public function getHasFaviconAttribute()
    {
        return !empty($this->favicon_path);
    }

    /**
     * Get single instance of website settings
     */
    public static function getSiteSettings()
    {
        return static::first() ?: new static();
    }
}