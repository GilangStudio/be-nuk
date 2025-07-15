<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_home' => 'boolean',
    ];

    // Relationships
    public function detail(): HasOne
    {
        return $this->hasOne(ServiceDetail::class);
    }

    public function excellences(): HasMany
    {
        return $this->hasMany(ServiceExcellence::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ServiceGallery::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    // Accessors
    public function getIconUrlAttribute()
    {
        return $this->icon_path ? asset('storage/' . $this->icon_path) : null;
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image_path ? asset('storage/' . $this->banner_image_path) : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeShowInHome($query)
    {
        return $query->where('show_in_home', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    // Route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}