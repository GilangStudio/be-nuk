<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProjectGallery::class);
    }

    // Accessors
    public function getMediaUrlAttribute()
    {
        return $this->media_path ? asset('storage/' . $this->media_path) : null;
    }

    public function getIsVideoAttribute()
    {
        return $this->media_type === 'video';
    }

    public function getIsImageAttribute()
    {
        return $this->media_type === 'image';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function scopeByService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    // Route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}