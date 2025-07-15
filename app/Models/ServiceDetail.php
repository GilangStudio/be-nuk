<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceDetail extends Model
{
    protected $guarded = ['id'];

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Accessors
    public function getHeaderImageUrlAttribute()
    {
        return $this->header_image_path ? asset('storage/' . $this->header_image_path) : null;
    }

    public function getContentImageUrlAttribute()
    {
        return $this->content_image_path ? asset('storage/' . $this->content_image_path) : null;
    }
}